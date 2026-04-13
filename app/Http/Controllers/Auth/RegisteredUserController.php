<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\MailHelper;
use App\Mail\AdminNewUserNotification;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Store referral code in session if provided
        if ($request->has('ref')) {
            session(['referral_code' => $request->input('ref')]);
        }
        
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Process referral
        $referralService = app(ReferralService::class);
        $referralCode = session('referral_code') ?? $request->input('referral_code');
        $referralService->processSignup($user, $referralCode);
        
        // Clear session
        session()->forget('referral_code');

        event(new Registered($user));

        // Notify admin of new registration
        $this->notifyAdminOfNewUser($user);

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Send email notification to admin about new user registration
     */
    private function notifyAdminOfNewUser(User $user): void
    {
        // Get all admin users
        $admins = User::where('role', 'admin')->orWhere('is_admin', true)->get();
        
        if ($admins->isEmpty()) {
            return;
        }

        // Send to all admins
        foreach ($admins as $admin) {
            if ($admin->email) {
                MailHelper::send($admin->email, new AdminNewUserNotification($user));
            }
        }
    }
}
