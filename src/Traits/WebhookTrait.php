<?php

namespace Tabby\Traits;

use Illuminate\Support\Facades\Http;

use Tabby\Exceptions\TabbyApiException;
use Tabby\Models\Webhook;

trait WebhookTrait
{
    public function registerWebhook(
        string $url,
        bool $isTest = true,
        ?string $headerTitle = null,
        ?string $headerValue = null,
    ): Webhook {
        try {
            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V1 . "/webhooks";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
                'X-Merchant-Code' => $this->merchantCode,
            ];

            // Request body
            $webhookObj = new Webhook(
                id: null,
                url: $url,
                isTest: $isTest,
                headerTitle: $headerTitle,
                headerValue: $headerValue
            );

            // Send a POST request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->post($requestEndpoint, $webhookObj->toArray());

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $webhook = $response->json();

            return Webhook::fromArray($webhook ?? []);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function retrieveAllWebhooks(): array
    {
        try {
            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V1 . "/webhooks";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
                'X-Merchant-Code' => $this->merchantCode,
            ];

            // Send a GET request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->get($requestEndpoint);

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $webhookList = $response->json();

            // Convert the webhook list to an array of Webhook objects
            return array_map(
                fn($webhook) => Webhook::fromArray($webhook),
                $webhookList ?? []
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function retrieveWebhook(string $webhookId): Webhook
    {
        try {
            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V1 . "/webhooks/{$webhookId}";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
                'X-Merchant-Code' => $this->merchantCode,
            ];

            // Send a GET request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->get($requestEndpoint);

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $webhook = $response->json();

            return Webhook::fromArray($webhook ?? []);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateWebhook(
        string $webhookId,
        ?string $url = null,
        ?bool $isTest = null,
    ): Webhook {
        try {
            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V1 . "/webhooks/{$webhookId}";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
                'X-Merchant-Code' => $this->merchantCode,
            ];

            // Request body
            $requestBody = array_filter(
                [
                    'url' => $url,
                    'isTest' => $isTest,
                ],
                fn($value) => $value !== null
            );

            // Send a PUT request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->put($requestEndpoint, $requestBody);

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            // Decode the JSON response
            $webhook = $response->json();

            return Webhook::fromArray($webhook ?? []);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function deleteWebhook($webhookId): bool
    {
        try {
            // Request Endpoint
            $requestEndpoint = static::BASE_URI_V1 . "/webhooks/{$webhookId}";

            // Request headers
            $requestHeaders = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->secretkey}",
                'X-Merchant-Code' => $this->merchantCode,
            ];

            // Send a GET request to the authentication endpoint
            $response = Http::withHeaders($requestHeaders)->delete($requestEndpoint);

            // Check if the request was successful
            if ($response->failed()) {
                throw TabbyApiException::fromResponse($response);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
