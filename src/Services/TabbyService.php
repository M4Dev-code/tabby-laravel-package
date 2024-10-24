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
        TabbyBuyerHistory $buyerHistory,
        TabbyOrder $order,
        TabbyOrderHistory $orderHistory,
        TabbyShippingAddress $shippingAddress,
        $successCallback = null,
        $cancelCallback = null,
        $failureCallback = null,
        $lang = 'ar',
    ): string {
        try {
            // Request Endpoint
            $requestEndpoint = self::BASE_URI . '/checkout';

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->publicKey}",
            ];

            $payment = [
                'amount' => $amount,
                'currency' => $this->currency,
                // 'description' => 'string',
                'buyer' => $buyer->toArray(),
                'buyer_history' => $buyerHistory->toArray(),
                'order' => $order->toArray(),
                'order_history' => $orderHistory->toArray(),
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
                $this->handleResponseError($response, 'Failed to create session');
            }

            // Decode the JSON response and extract the token
            $responseData = $response->json();

            if (empty(isset($responseData['configuration']['available_products']['installments'][0]['web_url']))) {
                throw new Exception('Web Url missing in the response.');
            }

            return $responseData['configuration']['available_products']['installments'][0]['web_url'];
        } catch (Exception $e) {
            throw $e;
        }
    }


    /** --------------------------------------------- HELPERS --------------------------------------------- */
    /**
     * Handle errors in the response.
     *
     * @param \Illuminate\Http\Client\Response $response
     * @throws \Exception
     */
    private function handleResponseError($response, string $defaultErrorMsg)
    {
        // Try to extract error details from the response body
        $responseData = $response->json();
        $errorMsg = $responseData['detail'] ?? $responseData['title'] ?? $responseData['error'] ?? $response->body();

        if (empty($errorMsg)) {
            $errorMsg = $defaultErrorMsg;
        }

        // Include the status code in the error message for debugging purposes
        $errorMsg .= ", Status code: {$response->status()}";

        throw new Exception($errorMsg, $response->status());
    }
}
