<?php

namespace Tabby\Services;

// Tabby Traits
use Tabby\Traits\CheckoutTrait;
use Tabby\Traits\PaymentTrait;
use Tabby\Traits\WebhookTrait;

class TabbyService
{
    use CheckoutTrait, PaymentTrait, WebhookTrait;

    protected const BASE_URI_V1 = 'https://api.tabby.ai/api/v1';
    protected const BASE_URI_V2 = 'https://api.tabby.ai/api/v2';

    // Properties
    protected string $merchantCode;
    protected string $publicKey;
    protected string $secretkey;
    protected string $currency;

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
}
