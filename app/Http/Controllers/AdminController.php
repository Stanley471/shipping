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
}