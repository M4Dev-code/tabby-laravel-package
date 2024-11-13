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

    public function getRejectionReason(): array
    {
        if (!$this->isRejected()) {
            return [];
        }

        // Get the rejection reason key from configuration
        $hasReason = !empty($this->configuration->getProducts()['installments']['rejection_reason']);
        $rejectionKey = $hasReason ? $this->configuration->getProducts()['installments']['rejection_reason'] : 'unknown';

        // Define a mapping of rejection reasons
        $rejectionReasons = [
            'not_available' => \Tabby\Constants::REJECTION_REASON_NOT_AVAILABLE,
            'order_amount_too_high' => \Tabby\Constants::REJECTION_REASON_ORDER_AMOUNT_TOO_HIGH,
            'order_amount_too_low' => \Tabby\Constants::REJECTION_REASON_ORDER_AMOUNT_TOO_LOW,
        ];

        // Return the mapped rejection reason or a default message if unknown
        $reason = $rejectionReasons[$rejectionKey] ?? $rejectionReasons['not_available'];
        $reason['en-ar'] = $reason['en'] . ', ' . $reason['ar'];
        return $reason;
    }

    public function getPaymentUrl(): string
    {
        try {
            // Check if the web URL for installments is set and not empty
            $isWebUrlAvailable = !empty($this->configuration->getAvailableProducts()['installments'][0]['web_url']);

            if (!$isWebUrlAvailable) {
                // Determine the appropriate error message
                $errorMsg = 'Web URL missing in the response.';

                if ($this->isRejected()) {
                    $errorMsg = $this->getRejectionReason()['en-ar'];
                }

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

    public function isRejected(): bool
    {
        return strtolower($this->status ?? '') === 'rejected';
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
}
