<?php

namespace App\Http\Controllers;

use App\Helpers\MailHelper;
use App\Mail\AdminDepositNotification;
use App\Models\AdminBankAccount;
use App\Models\CoinPurchase;
use App\Models\User;
use App\Services\CoinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoinController extends Controller
{
    protected CoinService $coinService;

    public function __construct(CoinService $coinService)
    {
        $this->middleware('auth');
        $this->coinService = $coinService;
    }

    /**
     * Show user coin balance and transaction history
     */
    public function index()
    {
        $user = auth()->user();
        $balance = $this->coinService->getBalance($user);
        
        $transactions = $user->coinTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $purchases = $user->coinPurchases()
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('coins.index', compact('balance', 'transactions', 'purchases'));
    }

    /**
     * Show P2P marketplace (buy coins)
     */
    public function buyForm()
    {
        $vendors = AdminBankAccount::getActiveOrdered();
        
        if ($vendors->isEmpty()) {
            return redirect()->route('coins.index')
                ->with('error', 'Coin purchases are temporarily unavailable. Please try again later.');
        }

        // Check if user has active pending order
        $hasActiveOrder = auth()->user()->coinPurchases()
            ->where('status', 'pending')
            ->exists();

        return view('coins.p2p_marketplace', compact('vendors', 'hasActiveOrder'));
    }

    /**
     * Process buy coins request
     */
    public function buy(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:100|max:100000', // Min 100 Naira, Max 100k
            'bank_account_id' => 'required|exists:admin_bank_accounts,id',
            'proof_image' => 'required|image|max:5120', // Max 5MB
        ]);

        $user = auth()->user();

        // Store proof image
        $proofPath = $request->file('proof_image')->store('payment_proofs', 'public');

        // Create purchase request
        $purchase = $this->coinService->createPurchaseRequest(
            $user,
            $validated['amount'],
            $validated['bank_account_id'],
            $proofPath
        );

        if (!$purchase) {
            Storage::disk('public')->delete($proofPath);
            return back()->with('error', 'Invalid bank account selected.');
        }

        // Notify admins of new deposit
        $this->notifyAdminsOfDeposit($purchase);

        return redirect()->route('coins.index')
            ->with('success', 'Payment submitted successfully! Your coins will be credited after admin verification.');
    }

    /**
     * Send email notification to admins about new deposit
     */
    private function notifyAdminsOfDeposit(CoinPurchase $purchase): void
    {
        // Get all admin users
        $admins = User::where('role', 'admin')->orWhere('is_admin', true)->get();
        
        if ($admins->isEmpty()) {
            return;
        }

        // Send to all admins
        foreach ($admins as $admin) {
            if ($admin->email) {
                MailHelper::send($admin->email, new AdminDepositNotification($purchase));
            }
        }
    }

    /**
     * Show user's P2P orders
     */
    public function orders()
    {
        $orders = auth()->user()->coinPurchases()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('coins.orders', compact('orders'));
    }

    /**
     * Show transaction details
     */
    public function transaction($id)
    {
        $transaction = auth()->user()
            ->coinTransactions()
            ->findOrFail($id);

        return view('coins.transaction', compact('transaction'));
    }
}
