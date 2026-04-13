<?php

namespace App\Http\Controllers;

use App\Helpers\MailHelper;
use App\Mail\TrackingUpdate as TrackingUpdateMail;
use App\Models\Shipment;
use App\Models\TrackingUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingUpdateController extends Controller
{
    public function store(Request $request, Shipment $shipment)
    {
        if ($shipment->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:pending,in_transit,out_for_delivery,delivered,cancelled',
            'location' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'progress' => 'required|integer|min:0|max:100',
            'occurred_at' => 'required|date',
        ]);

        $update = TrackingUpdate::create([
            'shipment_id' => $shipment->id,
            'status' => $validated['status'],
            'location' => $validated['location'],
            'note' => $validated['note'],
            'progress' => $validated['progress'],
            'occurred_at' => $validated['occurred_at'],
        ]);

        // Send email notification if requested
        if ($request->boolean('send_email')) {
            // Send to user who created the shipment
            MailHelper::send(Auth::user()->email, new TrackingUpdateMail($shipment, $update));
            
            // Send to receiver if email provided
            if ($shipment->receiver_email) {
                MailHelper::send($shipment->receiver_email, new TrackingUpdateMail($shipment, $update));
            }
        }

        return redirect()->route('shipments.show', $shipment)->with('success', 'Tracking update added.');
    }
}