<?php

namespace Tabby\Models;

class CheckoutSession
{
    private string $id;
    private Configuration $configuration;
    private Payment $payment;
    private string $status; // Enum: "created" "rejected" "expired" "approved"
    private ?string $token;
    private array $merchantUrls;
    private string $lang;
    private Merchant $merchant;
    private string $merchantCode;

    public function __construct(
        string $id,
        Configuration $configuration,
        Payment $payment,
        string $status,
        array $merchantUrls,
        string $lang,
        Merchant $merchant,
        string $merchantCode,
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
        ];
    }

    // Populate the object from an array
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            Configuration::fromArray($data['configuration']),
            Payment::fromArray($data['payment']),
            $data['status'],
            $data['merchant_urls'],
            $data['lang'],
            Merchant::fromArray($data['merchant']),
            $data['merchant_code'],
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
}
