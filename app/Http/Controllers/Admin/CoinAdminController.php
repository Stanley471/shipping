<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoinPurchase;
use App\Models\CoinTransaction;
use App\Models\Service;
use App\Models\User;
use App\Models\AdminBankAccount;
use App\Services\CoinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoinAdminController extends Controller
{
    protected CoinService $coinService;

    public function __construct(CoinService $coinService)
    {
        $this->middleware(['auth', 'admin']);
        $this->coinService = $coinService;
    }

    /**
     * Dashboard with stats
     */
    public function dashboard()
    {
        $stats = [
            'total_coins_in_circulation' => \App\Models\UserCoin::sum('balance'),
            'total_earned' => \App\Models\UserCoin::sum('total_earned'),
            'total_spent' => \App\Models\UserCoin::sum('total_spent'),
            'pending_purchases' => CoinPurchase::pending()->count(),
            'total_transactions_today' => CoinTransaction::whereDate('created_at', today())->count(),
        ];

        return view('admin.coins.dashboard', compact('stats'));
    }

    /**
     * List pending purchases
     */
    public function pendingPurchases()
    {
        $purchases = CoinPurchase::pending()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.coins.pending', compact('purchases'));
    }

    /**
     * View purchase details
     */
    public function viewPurchase(CoinPurchase $purchase)
    {
        $purchase->load('user', 'processor');
        return view('admin.coins.purchase_detail', compact('purchase'));
    }

    /**
     * Approve purchase
     */
    public function approvePurchase(Request $request, CoinPurchase $purchase)
    {
        $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        $success = $this->coinService->approvePurchase(
            $purchase,
            auth()->user(),
            $request->input('note')
        );

        if ($success) {
            return redirect()->route('admin.coins.pending')
                ->with('success', "Purchase approved. {$purchase->amount_coins} coins credited to user.");
        }

        return back()->with('error', 'Could not approve purchase.');
    }

    /**
     * Reject purchase
     */
    public function rejectPurchase(Request $request, CoinPurchase $purchase)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $success = $this->coinService->rejectPurchase(
            $purchase,
            auth()->user(),
            $request->input('reason')
        );

        if ($success) {
            return redirect()->route('admin.coins.pending')
                ->with('success', 'Purchase rejected.');
        }

        return back()->with('error', 'Could not reject purchase.');
    }

    /**
     * Manual adjustment form
     */
    public function adjustmentForm()
    {
        return view('admin.coins.adjustment');
    }

    /**
     * Process manual adjustment
     */
    public function adjustment(Request $request)
    {
        $validated = $request->validate([
            'user_email' => 'required|email|exists:users,email',
            'type' => 'required|in:add,deduct',
            'amount' => 'required|integer|min:1|max:100000',
            'reason' => 'required|string|max:500',
        ]);

        $user = User::where('email', $validated['user_email'])->first();
        $admin = auth()->user();

        if ($validated['type'] === 'add') {
            $this->coinService->adminAddCoins($user, $validated['amount'], $validated['reason'], $admin);
            $message = "Added {$validated['amount']} coins to {$user->name}'s account.";
        } else {
            $result = $this->coinService->adminDeductCoins($user, $validated['amount'], $validated['reason'], $admin);
            if (!$result) {
                return back()->with('error', 'User does not have enough coins to deduct.');
            }
            $message = "Deducted {$validated['amount']} coins from {$user->name}'s account.";
        }

        return redirect()->route('admin.coins.dashboard')
            ->with('success', $message);
    }

    /**
     * Transaction logs
     */
    public function transactions(Request $request)
    {
        $query = CoinTransaction::with('user', 'processor')
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->user}%")
                  ->orWhere('name', 'like', "%{$request->user}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(50);
        $types = [
            'deposit' => 'P2P Purchase',
            'spend' => 'Service Payment',
            'refund' => 'Refund',
            'admin_add' => 'Admin Credit',
            'admin_deduct' => 'Admin Deduction',
        ];

        return view('admin.coins.transactions', compact('transactions', 'types'));
    }

    /**
     * Service pricing settings
     */
    public function services()
    {
        $services = Service::orderBy('name')->get();
        return view('admin.coins.services', compact('services'));
    }

    /**
     * Update service pricing
     */
    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'is_free' => 'boolean',
            'coin_cost' => 'required_if:is_free,0|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $service->update([
            'is_free' => $request->boolean('is_free'),
            'coin_cost' => $validated['coin_cost'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.coins.services')
            ->with('success', "{$service->name} settings updated.");
    }

    /**
     * Bank accounts management
     */
    public function bankAccounts()
    {
        $accounts = AdminBankAccount::orderBy('sort_order')->get();
        return view('admin.coins.bank_accounts', compact('accounts'));
    }

    /**
     * Add bank account
     */
    public function addBankAccount(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:20',
            'account_name' => 'required|string|max:100',
        ]);

        AdminBankAccount::create($validated);

        return redirect()->route('admin.coins.bank_accounts')
            ->with('success', 'Bank account added.');
    }

    /**
     * Update bank account
     */
    public function updateBankAccount(Request $request, AdminBankAccount $account)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:20',
            'account_name' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $account->update($validated);

        return redirect()->route('admin.coins.bank_accounts')
            ->with('success', 'Bank account updated.');
    }

    /**
     * Delete bank account
     */
    public function deleteBankAccount(AdminBankAccount $account)
    {
        $account->delete();
        return redirect()->route('admin.coins.bank_accounts')
            ->with('success', 'Bank account deleted.');
    }
}
