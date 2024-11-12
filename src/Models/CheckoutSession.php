<?php

namespace Tabby\Models;

class CheckoutSession
{
    private ?string $id;
    private ?Configuration $configuration;
    private ?Payment $payment;
    private string $status; // Enum: "created" "rejected" "expired" "approved"
    private ?string $token;
    private array $merchantUrls;
    private string $lang;
    private ?Merchant $merchant;
    private string $merchantCode;
    private array $warnings;

    public function __construct(
        ?string $id = null,
        ?Configuration $configuration,
        ?Payment $payment,
        string $status,
        array $merchantUrls,
        string $lang,
        ?Merchant $merchant,
        string $merchantCode,
        array $warnings = [],
        ?string $token,
    ) {
        $this->id = $id;
        $this->configuration = $configuration;
        $this->payment = $payment;
        $this->status = $status;
        $this->token = $token;
        $this->merchantUrls = $merchantUrls;
        $this->lang = $lang;
        $this->merchant = $merchant;
        $this->merchantCode = $merchantCode;
        $this->warnings = $warnings;
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'configuration' => $this->configuration->toArray(),
            'payment' => $this->payment->toArray(),
            'status' => $this->status,
            'token' => $this->token,
            'merchant_urls' => $this->merchantUrls,
            'lang' => $this->lang,
            'merchant' => $this->merchant->toArray(),
            'merchant_code' => $this->merchantCode,
            'warnings' => $this->warnings,
        ];
    }

    // Populate the object from an array
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            Configuration::fromArray($data['configuration'] ?? []),
            Payment::fromArray($data['payment'] ?? []),
            $data['status'] ?? 'CREATED',
            $data['merchant_urls'] ?? [],
            $data['lang'] ?? 'en',
            Merchant::fromArray($data['merchant'] ?? []),
            $data['merchant_code'] ?? '',
            $data['warnings'] ?? [],
            $data['token'] ?? null,
        );
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getMerchantUrls(): array
    {
        return $this->merchantUrls;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getMerchant(): Merchant
    {
        return $this->merchant;
    }

    public function getMerchantCode(): string
    {
        return $this->merchantCode;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function getPaymentUrl(): string
    {
        try {
            // Check if the web URL for installments is set and not empty
            $isWebUrlAvailable = !empty($this->configuration->getAvailableProducts()['installments'][0]['web_url']);

            if (!$isWebUrlAvailable) {
                // Determine the appropriate error message
                $errorMsg = strtolower($this->status ?? '') === 'rejected'
                    ? 'The session request was rejected.'
                    : 'Web URL missing in the response.';

                // Override error message with warning if available
                if (!empty($this->warnings[0]['message'])) {
                    $errorMsg = $this->warnings[0]['message'];
                }

                // Throw an exception with the determined error message
                throw new \Exception($errorMsg, 500);
            }

            return $this->configuration->getAvailableProducts()['installments'][0]['web_url'];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
