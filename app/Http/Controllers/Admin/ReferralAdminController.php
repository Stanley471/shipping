<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralSetting;
use App\Models\ReferralTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show referral settings page
     */
    public function settings()
    {
        $settings = ReferralSetting::getSettings();
        
        return view('admin.referrals.settings', compact('settings'));
    }

    /**
     * Update referral settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'signup_bonus_amount' => 'required|integer|min:0|max:10000',
            'purchase_commission_percent' => 'required|numeric|min:0|max:100',
            'min_withdrawal_amount' => 'required|integer|min:100|max:100000',
            'conversion_rate' => 'required|numeric|min:0.01|max:10',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        ReferralSetting::getSettings()->update($validated);

        return back()->with('success', 'Referral settings updated successfully.');
    }

    /**
     * Show referral statistics
     */
    public function statistics()
    {
        $stats = [
            'total_referrals' => User::whereNotNull('referred_by')->count(),
            'total_referrers' => User::where('total_referrals', '>', 0)->count(),
            'total_earnings' => ReferralTransaction::where('amount', '>', 0)->sum('amount'),
            'pending_withdrawals' => ReferralTransaction::where('type', 'withdrawal')->where('status', 'pending')->count(),
            'total_withdrawn' => ReferralTransaction::where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            'top_referrers' => User::where('total_referrals', '>', 0)
                ->orderBy('total_referral_earnings', 'desc')
                ->take(10)
                ->get(['id', 'name', 'email', 'total_referrals', 'total_referral_earnings']),
        ];

        return view('admin.referrals.statistics', compact('stats'));
    }

    /**
     * Show pending withdrawals
     */
    public function withdrawals()
    {
        $withdrawals = ReferralTransaction::with(['user'])
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.referrals.withdrawals', compact('withdrawals'));
    }

    /**
     * Approve withdrawal
     */
    public function approveWithdrawal(ReferralTransaction $transaction)
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            return back()->with('error', 'Invalid transaction.');
        }

        $referralService = app(\App\Services\ReferralService::class);
        
        try {
            $referralService->approveWithdrawal($transaction);
            return back()->with('success', 'Withdrawal approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject withdrawal
     */
    public function rejectWithdrawal(Request $request, ReferralTransaction $transaction)
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            return back()->with('error', 'Invalid transaction.');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $referralService = app(\App\Services\ReferralService::class);
        
        try {
            $referralService->rejectWithdrawal($transaction, $request->input('reason'));
            return back()->with('success', 'Withdrawal rejected and coins refunded.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show all referral transactions
     */
    public function transactions()
    {
        $transactions = ReferralTransaction::with(['user', 'referredUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.referrals.transactions', compact('transactions'));
    }
}
