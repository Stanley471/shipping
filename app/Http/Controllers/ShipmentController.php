<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\TrackingUpdate;
use App\Services\TrackingIdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Auth::user()->shipments()->latest()->paginate(10);

        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        return view('shipments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'receiver_email' => 'nullable|email|max:255',
            'pickup_location' => 'required|string',
            'delivery_address' => 'required|string',
            'shipped_at' => 'required|date',
            'status' => 'required|string|in:pending,in_transit,out_for_delivery,delivered,cancelled',
            'location' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'progress' => 'required|integer|min:0|max:100',
            'occurred_at' => 'required|date',
            'shipment_type' => 'required|string|in:air_freight,sea_freight,road_freight,express_delivery',
            'eta' => 'nullable|date|after:shipped_at',
        ]);

        $shipment = Shipment::create([
            'user_id' => Auth::id(),
            'tracking_id' => TrackingIdGenerator::generate(),
            'sender_name' => $validated['sender_name'],
            'receiver_name' => $validated['receiver_name'],
            'receiver_email' => $validated['receiver_email'],
            'pickup_location' => $validated['pickup_location'],
            'delivery_address' => $validated['delivery_address'],
            'shipped_at' => $validated['shipped_at'],
            'shipment_type' => $validated['shipment_type'],
            'eta' => $validated['eta'],
        ]);

        TrackingUpdate::create([
            'shipment_id' => $shipment->id,
            'status' => $validated['status'],
            'location' => $validated['location'],
            'note' => $validated['note'],
            'progress' => $validated['progress'],
            'occurred_at' => $validated['occurred_at'],
        ]);

        return redirect()->route('shipments.index')->with('success', 'Shipment created successfully.');
    }

    public function show(Shipment $shipment)
    {
        $this->authorize('view', $shipment);

        $shipment->load(['trackingUpdates' => function ($query) {
            $query->orderBy('occurred_at', 'desc');
        }]);

        return view('shipments.show', compact('shipment'));
    }
}