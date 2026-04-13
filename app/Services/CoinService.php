<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserCoin;
use App\Models\CoinTransaction;
use App\Models\CoinPurchase;
use App\Models\Service;
use App\Models\AdminBankAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoinService
{
    /**
     * Get or create user coin balance
     */
    public function getOrCreateBalance(User $user): UserCoin
    {
        $coins = $user->coins;
        
        if (!$coins) {
            $coins = $user->coins()->create([
                'balance' => 0,
                'total_earned' => 0,
                'total_spent' => 0,
            ]);
        }
        
        return $coins;
    }

    /**
     * Check if user has enough coins
     */
    public function hasEnoughCoins(User $user, int $amount): bool
    {
        return $this->getBalance($user) >= $amount;
    }

    /**
     * Get user balance
     */
    public function getBalance(User $user): int
    {
        return $this->getOrCreateBalance($user)->balance;
    }

    /**
     * Add coins to user (deposit, admin add, refund)
     */
    public function addCoins(
        User $user, 
        int $amount, 
        string $type, 
        string $description, 
        ?string $reference = null,
        ?array $metadata = null,
        ?User $processedBy = null
    ): CoinTransaction {
        return DB::transaction(function () use ($user, $amount, $type, $description, $reference, $metadata, $processedBy) {
            $coins = $this->getOrCreateBalance($user);
            
            $oldBalance = $coins->balance;
            $newBalance = $oldBalance + $amount;
            
            // Update balance
            $coins->balance = $newBalance;
            $coins->total_earned += $amount;
            $coins->save();
            
            // Create transaction record
            $transaction = CoinTransaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount, // Positive for credit
                'balance_after' => $newBalance,
                'description' => $description,
                'reference' => $reference,
                'metadata' => $metadata,
                'processed_by' => $processedBy?->id,
            ]);
            
            Log::info("Coins added to user {$user->id}", [
                'amount' => $amount,
                'type' => $type,
                'new_balance' => $newBalance,
            ]);
            
            return $transaction;
        });
    }

    /**
     * Deduct coins from user (spend)
     */
    public function deductCoins(
        User $user, 
        int $amount, 
        string $description, 
        ?string $reference = null,
        ?array $metadata = null
    ): ?CoinTransaction {
        if (!$this->hasEnoughCoins($user, $amount)) {
            return null;
        }
        
        return DB::transaction(function () use ($user, $amount, $description, $reference, $metadata) {
            $coins = $this->getOrCreateBalance($user);
            
            $oldBalance = $coins->balance;
            $newBalance = $oldBalance - $amount;
            
            // Update balance
            $coins->balance = $newBalance;
            $coins->total_spent += $amount;
            $coins->save();
            
            // Create transaction record
            $transaction = CoinTransaction::create([
                'user_id' => $user->id,
                'type' => CoinTransaction::TYPE_SPEND,
                'amount' => -$amount, // Negative for debit
                'balance_after' => $newBalance,
                'description' => $description,
                'reference' => $reference,
                'metadata' => $metadata,
            ]);
            
            Log::info("Coins deducted from user {$user->id}", [
                'amount' => $amount,
                'new_balance' => $newBalance,
            ]);
            
            return $transaction;
        });
    }

    /**
     * Refund coins (for failed operations)
     */
    public function refundCoins(
        User $user, 
        int $amount, 
        string $description, 
        ?string $reference = null,
        ?array $metadata = null
    ): CoinTransaction {
        return $this->addCoins(
            $user,
            $amount,
            CoinTransaction::TYPE_REFUND,
            $description,
            $reference,
            $metadata
        );
    }

    /**
     * Admin: Add coins manually
     */
    public function adminAddCoins(
        User $user,
        int $amount,
        string $reason,
        User $admin
    ): CoinTransaction {
        return $this->addCoins(
            $user,
            $amount,
            CoinTransaction::TYPE_ADMIN_ADD,
            "Admin credit: {$reason}",
            null,
            ['admin_id' => $admin->id, 'reason' => $reason],
            $admin
        );
    }

    /**
     * Admin: Deduct coins manually
     */
    public function adminDeductCoins(
        User $user,
        int $amount,
        string $reason,
        User $admin
    ): ?CoinTransaction {
        if (!$this->hasEnoughCoins($user, $amount)) {
            return null;
        }
        
        return DB::transaction(function () use ($user, $amount, $reason, $admin) {
            $coins = $this->getOrCreateBalance($user);
            
            $oldBalance = $coins->balance;
            $newBalance = $oldBalance - $amount;
            
            // Update balance
            $coins->balance = $newBalance;
            $coins->save();
            
            // Create transaction record
            $transaction = CoinTransaction::create([
                'user_id' => $user->id,
                'type' => CoinTransaction::TYPE_ADMIN_DEDUCT,
                'amount' => -$amount,
                'balance_after' => $newBalance,
                'description' => "Admin deduction: {$reason}",
                'reference' => null,
                'metadata' => ['admin_id' => $admin->id, 'reason' => $reason],
                'processed_by' => $admin->id,
            ]);
            
            Log::info("Admin deducted coins from user {$user->id}", [
                'amount' => $amount,
                'admin_id' => $admin->id,
                'reason' => $reason,
            ]);
            
            return $transaction;
        });
    }

    /**
     * Create a coin purchase request (P2P)
     */
    public function createPurchaseRequest(
        User $user,
        int $amountCoins,
        int $adminBankAccountId
    ): ?CoinPurchase {
        $bankAccount = AdminBankAccount::find($adminBankAccountId);
        
        if (!$bankAccount || !$bankAccount->is_active) {
            return null;
        }
        
        return CoinPurchase::create([
            'user_id' => $user->id,
            'amount_coins' => $amountCoins,
            'amount_naira' => $amountCoins, // 1:1 ratio
            'bank_name' => $bankAccount->bank_name,
            'account_number' => $bankAccount->account_number,
            'account_name' => $bankAccount->account_name,
            'status' => CoinPurchase::STATUS_PENDING,
        ]);
    }

    /**
     * Admin: Approve a purchase request
     */
    public function approvePurchase(
        CoinPurchase $purchase,
        User $admin,
        ?string $note = null
    ): bool {
        if (!$purchase->isPending()) {
            return false;
        }
        
        return DB::transaction(function () use ($purchase, $admin, $note) {
            // Update purchase status
            $purchase->update([
                'status' => CoinPurchase::STATUS_APPROVED,
                'admin_note' => $note,
                'processed_by' => $admin->id,
                'processed_at' => now(),
            ]);
            
            // Add coins to user
            $this->addCoins(
                $purchase->user,
                $purchase->amount_coins,
                CoinTransaction::TYPE_DEPOSIT,
                "P2P Purchase approved",
                "PURCHASE:{$purchase->id}",
                ['purchase_id' => $purchase->id, 'bank' => $purchase->bank_name],
                $admin
            );
            
            return true;
        });
    }

    /**
     * Admin: Reject a purchase request
     */
    public function rejectPurchase(
        CoinPurchase $purchase,
        User $admin,
        string $reason
    ): bool {
        if (!$purchase->isPending()) {
            return false;
        }
        
        $purchase->update([
            'status' => CoinPurchase::STATUS_REJECTED,
            'admin_note' => $reason,
            'processed_by' => $admin->id,
            'processed_at' => now(),
        ]);
        
        return true;
    }

    /**
     * Get service cost
     */
    public function getServiceCost(string $serviceSlug): int
    {
        $service = Service::getBySlug($serviceSlug);
        
        if (!$service || !$service->is_active) {
            return 0; // Free if service doesn't exist
        }
        
        return $service->getCost();
    }

    /**
     * Check if user can afford a service
     */
    public function canAffordService(User $user, string $serviceSlug): bool
    {
        $cost = $this->getServiceCost($serviceSlug);
        
        if ($cost === 0) {
            return true;
        }
        
        return $this->hasEnoughCoins($user, $cost);
    }

    /**
     * Pay for a service
     */
    public function payForService(
        User $user,
        string $serviceSlug,
        string $description,
        ?string $reference = null
    ): ?CoinTransaction {
        $cost = $this->getServiceCost($serviceSlug);
        
        if ($cost === 0) {
            return null; // Free service, no transaction needed
        }
        
        return $this->deductCoins($user, $cost, $description, $reference, [
            'service' => $serviceSlug,
            'cost' => $cost,
        ]);
    }
}
