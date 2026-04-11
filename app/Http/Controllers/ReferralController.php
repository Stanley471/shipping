<?php

namespace App\Http\Controllers;

use App\Models\ReferralSetting;
use App\Models\ReferralTransaction;
use App\Services\ReferralService;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    protected ReferralService $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->middleware('auth');
        $this->referralService = $referralService;
    }

    /**
     * Show referral dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Ensure user has referral code
        $this->referralService->generateReferralCode($user);
        
        $stats = $this->referralService->getStats($user);
        $settings = ReferralSetting::getSettings();
        
        $referrals = $user->referrals()
            ->select('id', 'name', 'created_at', 'total_referral_earnings')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $transactions = $user->referralTransactions()
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('referrals.index', compact('stats', 'settings', 'referrals', 'transactions'));
    }

    /**
     * Convert referral coins to normal coins
     */
    public function convert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();

        try {
            $transaction = $this->referralService->convertToCoins(
                $user,
                $request->input('amount')
            );

            return back()->with('success', 'Referral coins converted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Request withdrawal
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:20',
            'account_name' => 'required|string|max:100',
        ]);

        $user = auth()->user();

        $bankDetails = [
            'bank_name' => $request->input('bank_name'),
            'account_number' => $request->input('account_number'),
            'account_name' => $request->input('account_name'),
        ];

        try {
            $transaction = $this->referralService->requestWithdrawal(
                $user,
                $request->input('amount'),
                $bankDetails
            );

            return back()->with('success', 'Withdrawal request submitted. You will be notified once approved.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show transaction history
     */
    public function transactions()
    {
        $transactions = auth()->user()
            ->referralTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('referrals.transactions', compact('transactions'));
    }
}
