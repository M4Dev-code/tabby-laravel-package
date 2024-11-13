<?php

namespace Tabby\Traits;

use Illuminate\Support\Facades\Http;

use Tabby\Models\Buyer;
use Tabby\Models\BuyerHistory;
use Tabby\Models\CheckoutSession;
use Tabby\Models\Configuration;
use Tabby\Models\Order;
use Tabby\Models\OrderHistory;
use Tabby\Models\Payment;
use Tabby\Models\ShippingAddress;
use Tabby\Models\Merchant;
use Tabby\Exceptions\TabbyApiException;

trait CheckoutTrait
{
    public function createSession(
        float $amount,
        Buyer $buyer,
        Order $order,
        ShippingAddress $shippingAddress,
        string $description = '',
        string $successCallback = null,
        string $cancelCallback = null,
        string $failureCallback = null,
        string $lang = 'ar',
        ?BuyerHistory $buyerHistory = null,
        ?OrderHistory $orderHistory = null,
    ): CheckoutSession {
        try {
            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V2 . '/checkout';

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->publicKey}",
            ];

            // Set default values
            $buyerHistory ??= new BuyerHistory();
            $orderHistory ??= new OrderHistory(0, $buyer, $shippingAddress);

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
                ],
                'token' => null,
            ];

            // Send a POST request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->post($requestEndpoint, $requestBody);

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $sessionData = $response->json();

            return new CheckoutSession(
                id: $sessionData['id'],
                configuration: Configuration::fromArray($sessionData['configuration'] ?? []),
                payment: Payment::fromArray($sessionData['payment'] ?? []),
                status: $sessionData['status'],
                merchantUrls: $sessionData['merchant_urls'] ?? [],
                lang: $sessionData['lang'],
                merchant: Merchant::fromArray($sessionData['merchant'] ?? []),
                merchantCode: $sessionData['merchant_code'],
                token: $sessionData['token'],
                warnings: $sessionData['warnings'] ?? [],
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function retrieveCheckoutSession(string $sessionId): CheckoutSession
    {
        try {
            $sessionId = trim($sessionId);

            if (empty($sessionId)) {
                throw new \Exception('Session ID is required', 400);
            }

            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V2 . "/checkout/{$sessionId}";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretKey}",
            ];

            // Send a GET request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->get($requestEndpoint);

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $sessionData = $response->json();

            return new CheckoutSession(
                id: $sessionData['id'],
                configuration: Configuration::fromArray($sessionData['configuration'] ?? []),
                payment: Payment::fromArray($sessionData['payment'] ?? []),
                status: $sessionData['status'],
                merchantUrls: $sessionData['merchant_urls'] ?? [],
                lang: $sessionData['lang'],
                merchant: Merchant::fromArray($sessionData['merchant'] ?? []),
                merchantCode: $sessionData['merchant_code'],
                token: $sessionData['token'],
                warnings: $sessionData['warnings'] ?? [],
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
