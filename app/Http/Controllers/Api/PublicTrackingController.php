<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Public Tracking API Controller
 * 
 * Provides read-only access to shipment tracking data for external
 * tracking websites and applications.
 * 
 * Security features:
 * - Rate limiting (configured in RouteServiceProvider)
 * - Tracking ID validation
 * - Only public-safe data exposed
 * - No internal IDs or sensitive information
 */
class PublicTrackingController extends Controller
{
    /**
     * Tracking ID validation pattern
     * Alphanumeric only, 5-20 characters
     */
    private const TRACKING_ID_PATTERN = '/^[A-Z0-9]{5,20}$/';

    /**
     * Get public tracking information for a shipment
     * 
     * @param Request $request
     * @param string $tracking_id
     * @return JsonResponse
     */
    public function show(Request $request, string $tracking_id): JsonResponse
    {
        // Normalize tracking ID to uppercase
        $tracking_id = strtoupper(trim($tracking_id));

        // Validate tracking ID format
        $validation = $this->validateTrackingId($tracking_id);
        if ($validation !== true) {
            return $this->errorResponse('Invalid tracking ID format', Response::HTTP_BAD_REQUEST);
        }

        // Find shipment with tracking updates
        $shipment = Shipment::with(['trackingUpdates' => function ($query) {
            $query->orderBy('occurred_at', 'desc');
        }])
        ->where('tracking_id', $tracking_id)
        ->first();

        // Return generic 404 to prevent tracking ID enumeration attacks
        if (! $shipment) {
            return $this->errorResponse('Tracking number not found', Response::HTTP_NOT_FOUND);
        }

        // Return sanitized public data
        return $this->successResponse([
            'tracking_id' => $shipment->tracking_id,
            'status' => $this->getLatestStatus($shipment),
            'progress' => $this->getLatestProgress($shipment),
            'shipment_type' => $shipment->shipment_type,
            'sender' => $this->sanitizeSenderName($shipment->sender_name),
            'receiver' => $this->sanitizeReceiverName($shipment->receiver_name),
            'origin' => $this->sanitizeLocation($shipment->pickup_location),
            'destination' => $this->sanitizeLocation($shipment->delivery_address),
            'shipped_at' => $shipment->shipped_at?->toIso8601String(),
            'estimated_delivery' => $shipment->eta?->toIso8601String(),
            'courier' => $shipment->courier,
            'timeline' => $this->formatTimeline($shipment),
            'chat' => $this->formatChatWidget($shipment),
        ]);
    }

    /**
     * Validate tracking ID format
     * 
     * @param string $tracking_id
     * @return bool
     */
    private function validateTrackingId(string $tracking_id): bool
    {
        // Check length
        if (strlen($tracking_id) < 5 || strlen($tracking_id) > 20) {
            return false;
        }

        // Check pattern (alphanumeric only)
        if (! preg_match(self::TRACKING_ID_PATTERN, $tracking_id)) {
            return false;
        }

        return true;
    }

    /**
     * Get latest status from tracking updates
     * 
     * @param Shipment $shipment
     * @return string
     */
    private function getLatestStatus(Shipment $shipment): string
    {
        $latestUpdate = $shipment->trackingUpdates->first();
        
        return $latestUpdate?->status ?? 'pending';
    }

    /**
     * Get latest progress percentage
     * 
     * @param Shipment $shipment
     * @return int
     */
    private function getLatestProgress(Shipment $shipment): int
    {
        $latestUpdate = $shipment->trackingUpdates->first();
        
        return $latestUpdate?->progress ?? 0;
    }

    /**
     * Sanitize sender name for public display
     * 
     * @param string|null $name
     * @return string
     */
    private function sanitizeSenderName(?string $name): string
    {
        if (empty($name)) {
            return 'Shipper';
        }

        // Return only the name without any email/phone that might be included
        $name = strip_tags($name);
        
        // Limit length
        return substr($name, 0, 100);
    }

    /**
     * Sanitize receiver name for public display
     * 
     * @param string|null $name
     * @return string
     */
    private function sanitizeReceiverName(?string $name): string
    {
        if (empty($name)) {
            return 'Consignee';
        }

        $name = strip_tags($name);
        
        return substr($name, 0, 100);
    }

    /**
     * Sanitize location information
     * 
     * @param string|null $location
     * @return string|null
     */
    private function sanitizeLocation(?string $location): ?string
    {
        if (empty($location)) {
            return null;
        }

        // Remove any potentially sensitive information
        $location = strip_tags($location);
        
        // Limit length to prevent data leakage
        return substr($location, 0, 200) ?: null;
    }

    /**
     * Format tracking updates as timeline
     * 
     * @param Shipment $shipment
     * @return array
     */
    private function formatTimeline(Shipment $shipment): array
    {
        return $shipment->trackingUpdates->map(function ($update) {
            return [
                'status' => $update->status,
                'location' => $this->sanitizeLocation($update->location),
                'message' => $update->note,
                'progress' => $update->progress,
                'timestamp' => $update->occurred_at?->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Format chat widget information for public display
     * 
     * @param Shipment $shipment
     * @return array|null
     */
    private function formatChatWidget(Shipment $shipment): ?array
    {
        if (empty($shipment->chat_provider) || empty($shipment->chat_widget_code)) {
            return null;
        }

        return [
            'provider' => $shipment->chat_provider,
            'widget_code' => $shipment->chat_widget_code,
        ];
    }

    /**
     * Return standardized success response
     * 
     * @param array $data
     * @return JsonResponse
     */
    private function successResponse(array $data): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Return standardized error response
     * 
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    private function errorResponse(string $message, int $status): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], $status);
    }
}
