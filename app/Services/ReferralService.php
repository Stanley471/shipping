<?php

namespace App\Services;

use App\Models\User;
use App\Models\ReferralSetting;
use App\Models\ReferralCoin;
use App\Models\ReferralTransaction;
use App\Models\CoinPurchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    /**
     * Process referral signup - give bonus to referrer
     */
    public function processSignup(User $newUser, ?string $referralCode): void
    {
        if (!$referralCode || !ReferralSetting::isActive()) {
            // Still generate referral code for new user
            $this->generateReferralCode($newUser);
            return;
        }

        $referrer = User::where('referral_code', strtoupper($referralCode))->first();
        
        if (!$referrer || $referrer->id === $newUser->id) {
            $this->generateReferralCode($newUser);
            return;
        }

        DB::transaction(function () use ($newUser, $referrer) {
            // Link new user to referrer
            $newUser->update([
                'referred_by' => $referrer->id,
            ]);

            // Generate referral code for new user
            $this->generateReferralCode($newUser);

            // Give signup bonus to referrer
            $settings = ReferralSetting::getSettings();
            $bonusAmount = $settings->signup_bonus_amount;

            if ($bonusAmount > 0) {
                $this->creditReferralCoins(
                    $referrer,
                    $bonusAmount,
                    'signup_bonus',
                    $newUser,
                    "Signup bonus for referring {$newUser->name}"
                );

                // Update referrer stats
                $referrer->increment('total_referrals');
                $referrer->increment('total_referral_earnings', $bonusAmount);
            }
        });
    }

    /**
     * Process purchase commission - give percentage to referrer
     */
    public function processPurchaseCommission(CoinPurchase $purchase): void
    {
        if (!ReferralSetting::isActive()) {
            return;
        }

        $buyer = $purchase->user;
        $referrer = $buyer->referrer;

        if (!$referrer) {
            return;
        }

        $settings = ReferralSetting::getSettings();
        $commissionPercent = $settings->purchase_commission_percent;

        if ($commissionPercent <= 0) {
            return;
        }

        // Calculate commission based on coin amount purchased
        $commissionAmount = ($purchase->amount_coins * $commissionPercent) / 100;

        if ($commissionAmount <= 0) {
            return;
        }

        DB::transaction(function () use ($referrer, $buyer, $purchase, $commissionAmount, $commissionPercent) {
            $this->creditReferralCoins(
                $referrer,
                $commissionAmount,
                'purchase_commission',
                $buyer,
                "{$commissionPercent}% commission from {$buyer->name}'s purchase of {$purchase->amount_coins} coins",
                $purchase
            );

            // Update referrer stats
            $referrer->increment('total_referral_earnings', $commissionAmount);
        });
    }

    /**
     * Credit referral coins to user
     */
    protected function creditReferralCoins(
        User $user,
        float $amount,
        string $type,
        ?User $referredUser = null,
        ?string $description = null,
        ?CoinPurchase $purchase = null
    ): void {
        $referralCoin = ReferralCoin::forUser($user);
        
        $newBalance = $referralCoin->balance + $amount;
        $newTotalEarned = $referralCoin->total_earned + $amount;

        $referralCoin->update([
            'balance' => $newBalance,
            'total_earned' => $newTotalEarned,
        ]);

        ReferralTransaction::create([
            'user_id' => $user->id,
            'referred_user_id' => $referredUser?->id,
            'type' => $type,
            'amount' => $amount,
            'balance_after' => $newBalance,
            'description' => $description,
            'coin_purchase_id' => $purchase?->id,
            'status' => 'completed',
        ]);

        Log::info("Referral coins credited", [
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => $type,
            'new_balance' => $newBalance,
        ]);
    }

    /**
     * Convert referral coins to normal coins
     */
    public function convertToCoins(User $user, float $amount): ?CoinTransaction
    {
        $referralCoin = ReferralCoin::forUser($user);

        if ($referralCoin->balance < $amount) {
            throw new \Exception('Insufficient referral coins');
        }

        $settings = ReferralSetting::getSettings();
        $conversionRate = $settings->conversion_rate;
        $normalCoins = $amount * $conversionRate;

        return DB::transaction(function () use ($user, $referralCoin, $amount, $normalCoins, $conversionRate) {
            // Deduct from referral balance
            $newBalance = $referralCoin->balance - $amount;
            $newTotalConverted = $referralCoin->total_converted + $amount;

            $referralCoin->update([
                'balance' => $newBalance,
                'total_converted' => $newTotalConverted,
            ]);

            // Add to normal coin balance
            $coinService = app(CoinService::class);
            $transaction = $coinService->addCoins(
                $user,
                $normalCoins,
                'deposit',
                "Converted {$amount} referral coins to {$normalCoins} normal coins",
                null
            );

            // Record referral transaction
            ReferralTransaction::create([
                'user_id' => $user->id,
                'type' => 'converted_to_coins',
                'amount' => -$amount,
                'balance_after' => $newBalance,
                'description' => "Converted to {$normalCoins} normal coins (rate: {$conversionRate})",
                'coin_transaction_id' => $transaction?->id,
                'status' => 'completed',
            ]);

            return $transaction;
        });
    }

    /**
     * Request withdrawal of referral coins
     */
    public function requestWithdrawal(User $user, float $amount, array $bankDetails): ReferralTransaction
    {
        $referralCoin = ReferralCoin::forUser($user);
        $minAmount = $referralCoin->minWithdrawal();

        if ($amount < $minAmount) {
            throw new \Exception("Minimum withdrawal amount is {$minAmount} coins");
        }

        if ($referralCoin->balance < $amount) {
            throw new \Exception('Insufficient referral coins');
        }

        return DB::transaction(function () use ($user, $referralCoin, $amount, $bankDetails) {
            // Deduct from balance
            $newBalance = $referralCoin->balance - $amount;
            $newTotalWithdrawn = $referralCoin->total_withdrawn + $amount;

            $referralCoin->update([
                'balance' => $newBalance,
                'total_withdrawn' => $newTotalWithdrawn,
            ]);

            // Create pending withdrawal transaction
            $transaction = ReferralTransaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => -$amount,
                'balance_after' => $newBalance,
                'description' => "Withdrawal request for {$amount} coins",
                'status' => 'pending',
                'metadata' => [
                    'bank_details' => $bankDetails,
                    'requested_at' => now()->toIso8601String(),
                ],
            ]);

            return $transaction;
        });
    }

    /**
     * Approve withdrawal (admin only)
     */
    public function approveWithdrawal(ReferralTransaction $transaction): void
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            throw new \Exception('Invalid transaction');
        }

        $transaction->update([
            'status' => 'completed',
            'description' => $transaction->description . ' (Approved)',
        ]);
    }

    /**
     * Reject withdrawal (admin only)
     */
    public function rejectWithdrawal(ReferralTransaction $transaction, string $reason): void
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            throw new \Exception('Invalid transaction');
        }

        DB::transaction(function () use ($transaction, $reason) {
            $user = $transaction->user;
            $referralCoin = ReferralCoin::forUser($user);
            $amount = abs($transaction->amount);

            // Refund the coins
            $newBalance = $referralCoin->balance + $amount;
            $newTotalWithdrawn = $referralCoin->total_withdrawn - $amount;

            $referralCoin->update([
                'balance' => $newBalance,
                'total_withdrawn' => $newTotalWithdrawn,
            ]);

            $transaction->update([
                'status' => 'rejected',
                'description' => "Withdrawal rejected: {$reason}",
                'balance_after' => $newBalance,
            ]);
        });
    }

    /**
     * Generate unique referral code for user
     */
    public function generateReferralCode(User $user): void
    {
        if ($user->referral_code) {
            return;
        }

        $code = User::generateReferralCode();
        $user->update(['referral_code' => $code]);
    }

    /**
     * Get referral statistics for user
     */
    public function getStats(User $user): array
    {
        $referralCoin = ReferralCoin::forUser($user);
        $settings = ReferralSetting::getSettings();

        return [
            'referral_code' => $user->referral_code,
            'referral_link' => $user->getReferralLink(),
            'total_referrals' => $user->total_referrals,
            'referral_balance' => $referralCoin->balance,
            'total_earned' => $referralCoin->total_earned,
            'total_converted' => $referralCoin->total_converted,
            'total_withdrawn' => $referralCoin->total_withdrawn,
            'min_withdrawal' => $settings->min_withdrawal_amount,
            'conversion_rate' => $settings->conversion_rate,
            'can_withdraw' => $referralCoin->canWithdraw(),
        ];
    }
}
