<?php

namespace App\Http\Controllers;

use App\Models\AdminBankAccount;
use App\Models\CoinPurchase;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('vendor'); // Only vendors can access
    }

    /**
     * Vendor Dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get or create vendor bank account
        $vendor = AdminBankAccount::byUser($user->id)->first();
        
        if (!$vendor) {
            // Vendor hasn't set up bank details yet
            return redirect()->route('vendor.setup')
                ->with('info', 'Please set up your bank account to start receiving payments.');
        }

        // Get stats
        $stats = [
            'total_sales' => $vendor->total_sales,
            'pending_orders' => CoinPurchase::where('account_number', $vendor->account_number)
                ->where('status', 'pending')
                ->count(),
            'total_earnings' => CoinPurchase::where('account_number', $vendor->account_number)
                ->where('status', 'approved')
                ->sum('amount_naira'),
            'rating' => $vendor->rating,
        ];

        // Get recent orders
        $recentOrders = CoinPurchase::where('account_number', $vendor->account_number)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('vendor.dashboard', compact('vendor', 'stats', 'recentOrders'));
    }

    /**
     * Setup vendor bank account (for approved vendors only)
     */
    public function setup()
    {
        $user = auth()->user();
        
        // Check if already has bank account
        if (AdminBankAccount::byUser($user->id)->exists()) {
            return redirect()->route('vendor.dashboard');
        }

        return view('vendor.setup');
    }

    /**
     * Store vendor bank account
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Check if already has bank account
        if (AdminBankAccount::byUser($user->id)->exists()) {
            return redirect()->route('vendor.dashboard');
        }

        $validated = $request->validate([
            'display_name' => 'required|string|max:100',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:20|unique:admin_bank_accounts',
            'account_name' => 'required|string|max:100',
            'vendor_info' => 'nullable|string|max:500',
            'vendor_notes' => 'nullable|string|max:1000',
            'rate' => 'required|numeric|min:0.5|max:10',
            'min_limit' => 'required|integer|min:100|max:10000',
            'max_limit' => 'required|integer|min:1000|max:500000',
        ]);

        $validated['user_id'] = $user->id;
        $validated['is_active'] = true;
        $validated['sort_order'] = 0;

        AdminBankAccount::create($validated);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Bank account set up successfully! You can now receive payments.');
    }

    /**
     * Edit vendor profile
     */
    public function edit()
    {
        $user = auth()->user();
        $vendor = AdminBankAccount::byUser($user->id)->firstOrFail();

        return view('vendor.edit', compact('vendor'));
    }

    /**
     * Update vendor profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $vendor = AdminBankAccount::byUser($user->id)->firstOrFail();

        $validated = $request->validate([
            'display_name' => 'required|string|max:100',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:20|unique:admin_bank_accounts,account_number,' . $vendor->id,
            'account_name' => 'required|string|max:100',
            'vendor_info' => 'nullable|string|max:500',
            'vendor_notes' => 'nullable|string|max:1000',
            'rate' => 'required|numeric|min:0.5|max:10',
            'min_limit' => 'required|integer|min:100|max:10000',
            'max_limit' => 'required|integer|min:1000|max:500000',
            'avg_response_time' => 'required|integer|min:1|max:120',
        ]);

        $vendor->update($validated);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * View all orders for vendor
     */
    public function orders()
    {
        $user = auth()->user();
        $vendor = AdminBankAccount::byUser($user->id)->firstOrFail();

        $orders = CoinPurchase::where('account_number', $vendor->account_number)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('vendor.orders', compact('vendor', 'orders'));
    }

    /**
     * Vendor confirms they received payment
     */
    public function confirmOrder(CoinPurchase $order)
    {
        $user = auth()->user();
        $vendor = AdminBankAccount::byUser($user->id)->firstOrFail();

        // Verify this order belongs to this vendor
        if ($order->account_number !== $vendor->account_number) {
            abort(403);
        }

        // Only confirm pending orders
        if (!$order->isPending()) {
            return back()->with('error', 'Order is not pending.');
        }

        // Update order status to approved
        $order->update([
            'status' => 'approved',
            'admin_note' => 'Payment confirmed by vendor',
            'processed_at' => now(),
        ]);

        // Update vendor stats
        $vendor->increment('total_sales');
        
        // Credit coins to buyer
        $buyer = $order->user;
        $buyerCoinBalance = $buyer->coins ?? $buyer->coins()->create([
            'balance' => 0,
            'total_earned' => 0,
            'total_spent' => 0,
        ]);
        
        $buyerCoinBalance->balance += $order->amount_coins;
        $buyerCoinBalance->total_earned += $order->amount_coins;
        $buyerCoinBalance->save();

        // Process referral commission
        $referralService = app(ReferralService::class);
        try {
            $referralService->processPurchaseCommission($order);
        } catch (\Exception $e) {
            \Log::error('Referral commission failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
        }

        return back()->with('success', 'Order confirmed and coins credited to buyer.');
    }
}
