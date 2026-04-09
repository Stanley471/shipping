<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalShipments' => Shipment::count(),
            'recentShipments' => Shipment::with('user')->latest()->take(5)->get(),
        ]);
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend yourself.');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'suspended';
        return back()->with('success', "User {$status} successfully.");
    }

    public function shipments()
    {
        $shipments = Shipment::with('user')->latest()->paginate(20);
        return view('admin.shipments', compact('shipments'));
    }

    /**
     * Toggle vendor status for a user
     */
    public function toggleVendorStatus(User $user)
    {
        $user->is_vendor = ! $user->is_vendor;
        $user->save();

        $status = $user->is_vendor ? 'approved as vendor' : 'removed as vendor';
        
        if ($user->is_vendor) {
            return back()->with('success', "User has been {$status}. They can now set up their vendor profile at /vendor/setup");
        } else {
            // Deactivate their bank account too
            if ($user->vendorBankAccount) {
                $user->vendorBankAccount->update(['is_active' => false]);
            }
            return back()->with('success', "User has been {$status}. Their vendor account has been deactivated.");
        }
    }
}