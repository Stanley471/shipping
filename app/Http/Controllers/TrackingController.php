<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'tracking_id' => 'required|string|max:20',
        ]);

        $trackingId = strtoupper($validated['tracking_id']);

        $shipment = Shipment::with(['trackingUpdates' => function ($query) {
            $query->orderBy('occurred_at', 'desc');
        }])
        ->where('tracking_id', $trackingId)
        ->first();

        if (! $shipment) {
            return back()->withErrors(['tracking_id' => 'Shipment not found. Please check your tracking ID.'])->withInput();
        }

        return view('tracking.result', compact('shipment'));
    }
}