<?php

namespace Tabby\Traits;

use Illuminate\Support\Facades\Http;

use Tabby\Exceptions\TabbyApiException;
use Tabby\Models\Payment;
use Tabby\Models\PaymentCapture;
use Tabby\Models\PaymentRefund;

trait PaymentTrait
{
    public function retrievePayment(string $paymentId): Payment
    {
        try {
            $paymentId = trim($paymentId);

            if (empty($paymentId)) {
                throw new \Exception('Payment ID is required', 400);
            }

            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V2 . "/payments/$paymentId";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
            ];

            // Send a POST request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->get($requestEndpoint);

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $payment = $response->json();

            return Payment::fromArray($payment ?? []);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updatePayment(
        string $paymentId,
        ?string $referenceId = null,
        array $deliveryTracking = []
    ): Payment {
        try {
            $paymentId = trim($paymentId);

            if (empty($paymentId)) {
                throw new \Exception('Payment ID is required', 400);
            }

            $data = [];

            if ($referenceId) {
                $data['order'] = ['reference_id' => $referenceId];
            }

            $deliveryTracking = array_filter($deliveryTracking, function ($item) {
                return isset($item['tracking_number'], $item['courier_code']);
            });

            if ($deliveryTracking) {
                $data['delivery_tracking'] = $deliveryTracking;
            }

            if (empty($data)) {
                throw new \Exception('One of the following fields is required: order, delivery_tracking', 400);
            }

            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V2 . "/payments/$paymentId";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
            ];

            // Send a POST request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->put($requestEndpoint, $data);

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $payment = $response->json();

            return Payment::fromArray($payment ?? []);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function capturePayment(
        string $paymentId,
        float $amount,
        ?string $referenceId = null,
        float $taxAmount = 0.00,
        float $shippingAmount = 0.00,
        float $discountAmount = 0.00,
        array $items = [],
    ): Payment {
        try {
            $paymentId = trim($paymentId);

            if (empty($paymentId)) {
                throw new \Exception('Payment ID is required', 400);
            }

            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V2 . "/payments/$paymentId/captures";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
            ];

            // Request body
            $paymentCapture = PaymentCapture::fromArray(
                [
                    'amount' => $amount,
                    'reference_id' => $referenceId,
                    'tax_amount' => $taxAmount,
                    'shipping_amount' => $shippingAmount,
                    'discount_amount' => $discountAmount,
                    'items' => $items ?? []
                ]
            );

            // Send a POST request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->post($requestEndpoint, $paymentCapture->toArray());

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $payment = $response->json();

            return Payment::fromArray($payment ?? []);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function refundPayment(
        string $paymentId,
        float $amount,
        ?string $referenceId = null,
        ?string $reason = null,
        array $items = [],
    ): Payment {
        try {
            $paymentId = trim($paymentId);

            if (empty($paymentId)) {
                throw new \Exception('Payment ID is required', 400);
            }

            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V2 . "/payments/$paymentId/refunds";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
            ];

            // Request body
            $paymentRefund = PaymentRefund::fromArray(
                [
                    'amount' => $amount,
                    'reference_id' => $referenceId,
                    'reason' => $reason,
                    'items' => $items ?? []
                ]
            );

            // Send a POST request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->post($requestEndpoint, $paymentRefund->toArray());

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $payment = $response->json();

            return Payment::fromArray($payment ?? []);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function closePayment(string $paymentId): Payment
    {
        try {
            $paymentId = trim($paymentId);

            if (empty($paymentId)) {
                throw new \Exception('Payment ID is required', 400);
            }

            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V2 . "/payments/$paymentId/close";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
            ];

            // Send a POST request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->post($requestEndpoint);

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $payment = $response->json();

            return Payment::fromArray($payment ?? []);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function listPayments(
        ?string $createdAtGte = null,
        ?string $createdAtLte = null,
        ?string $status = null, // Enum: "authorized" "closed" "rejected" "new" "captured" "refunded" "cancelled"
        ?int $limit = null,
        int $offset = 0,
    ) {
        try {

            // Validate limit
            if ($limit !== null && $limit > 20) {
                $limit = 20;
            }

            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V2 . "/payments";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
            ];

            // Request Query
            $query = array_filter(
                [
                    'created_at_gte' => $createdAtGte,
                    'created_at_lte' => $createdAtLte,
                    'status' => $status,
                    'limit' => $limit,
                    'offset' => $offset
                ],
                fn($value) => $value !== null
            );

            // Send a POST request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->get($requestEndpoint, $query);

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $responseData = $response->json();

            // Pagination
            $pagination = $responseData['pagination'] ?? [];
            $pagination['limit'] ??= 0;
            $pagination['offset'] ??= 0;
            $pagination['total_count'] ??= 0;

            // Payments
            $payments = array_map(function ($payment) {
                return Payment::fromArray($payment);
            }, $responseData['payments'] ?? []);

            // Return results
            return [$payments, $pagination];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
