<?php

namespace App\Http\Controllers;

use App\Models\AdminBankAccount;
use App\Models\CoinPurchase;
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
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('coins.index', compact('balance', 'transactions', 'purchases'));
    }

    /**
     * Show buy coins form
     */
    public function buyForm()
    {
        $bankAccounts = AdminBankAccount::getActiveOrdered();
        
        if ($bankAccounts->isEmpty()) {
            return redirect()->route('coins.index')
                ->with('error', 'Coin purchases are temporarily unavailable. Please try again later.');
        }

        return view('coins.buy', compact('bankAccounts'));
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

        return redirect()->route('coins.index')
            ->with('success', 'Payment submitted successfully! Your coins will be credited after admin verification.');
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
