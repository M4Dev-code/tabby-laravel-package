<?php

namespace Tabby\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Tabby\Models\TabbyBuyer;
use Tabby\Models\TabbyBuyerHistory;
use Tabby\Models\TabbyOrder;
use Tabby\Models\TabbyOrderHistory;
use Tabby\Models\TabbyShippingAddress;

class TabbyService
{
    private const BASE_URI = 'https://api.tabby.ai/api/v2';

    // Properties
    private string $merchantCode;
    private string $publicKey;
    private string $secretkey;
    private string $currency;

    public function __construct(
        string $merchantCode,
        string $publicKey,
        string $secretKey,
        string $currency = 'SAR'
    ) {
        $this->merchantCode = $merchantCode;
        $this->publicKey = $publicKey;
        $this->secretkey = $secretKey;
        $this->currency = $currency;
    }

    public function createSession(
        $amount,
        TabbyBuyer $buyer,
        TabbyOrder $order,
        TabbyShippingAddress $shippingAddress,
        $description = '',
        $successCallback = null,
        $cancelCallback = null,
        $failureCallback = null,
        $lang = 'ar',
        ?TabbyBuyerHistory $buyerHistory = null,
        ?TabbyOrderHistory $orderHistory = null,
    ) {
        try {
            // Request Endpoint
            $requestEndpoint = self::BASE_URI . '/checkout';

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->publicKey}",
            ];

            // Set default values
            $buyerHistory ??= new TabbyBuyerHistory();
            $orderHistory ??= new TabbyOrderHistory($amount);

            $payment = [
                'amount' => number_format($amount, 2),
                'currency' => $this->currency,
                'description' => $description,
                'buyer' => $buyer->toArray(),
                'buyer_history' => $buyerHistory->toArray(),
                'order' => $order->toArray(),
                'order_history' => [$orderHistory->toArray()],
                'shipping_address' => $shippingAddress->toArray(),
                // 'meta' => $meta,
                // 'attachment' => $attachment,
            ];

            // Request body parameters
            $requestBody = [
                'payment' => $payment,
                'lang' => $lang,
                'merchant_code' => $this->merchantCode,
                'merchant_urls' => [
                    'success' => $successCallback,
                    'cancel' => $cancelCallback,
                    'failure' => $failureCallback,
                ]
            ];

            // Send a POST request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->post($requestEndpoint, $requestBody);

            // Check if the request was successful
            if ($response->failed()) {
                $errorData = $response->json();
                $errorMsg = $errorData['error'] ?? 'Failed to create session';
                throw new Exception($errorMsg, $response->status());
            }

            // Decode the JSON response and extract the token
            return $response->json();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function retrievePayment(string $paymentId)
    {
        try {
            $paymentId = trim($paymentId);

            if (empty($paymentId)) {
                throw new Exception('Payment ID is required', 400);
            }

            // Request Endpoint
            $requestEndpoint = self::BASE_URI . '/payments/' . $paymentId;

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
                $errorData = $response->json();
                $errorMsg = $errorData['error'] ?? 'Failed to retrieve payment';
                throw new Exception($errorMsg, $response->status());
            }

            // Decode the JSON response
            return $response->json();
        } catch (Exception $e) {
            throw $e;
        }
    }

    // ---------------------------------------------------------------------------------
    public function getPaymentUrl($sessionResponse): string
    {
        try {
            // Check if the web URL for installments is set and not empty
            $isWebUrlAvailable = !empty($sessionResponse['configuration']['available_products']['installments'][0]['web_url']);

            if (!$isWebUrlAvailable) {
                // Determine the appropriate error message
                $errorMsg = strtolower($sessionResponse['status'] ?? '') === 'rejected'
                    ? 'The session request was rejected.'
                    : 'Web URL missing in the response.';

                // Override error message with warning if available
                if (!empty($sessionResponse['warnings'][0]['message'])) {
                    $errorMsg = $sessionResponse['warnings'][0]['message'];
                }

                // Throw an exception with the determined error message
                throw new Exception($errorMsg, 500);
            }

            return $sessionResponse['configuration']['available_products']['installments'][0]['web_url'];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
