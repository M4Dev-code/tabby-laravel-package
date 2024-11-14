<?php

namespace Tabby\Models;

class WebhookPayload
{
    public string $id;
    public string $createdAt;
    public string $expiresAt;
    public string $closedAt;
    public string $status;
    public bool $isTest;
    public bool $isExpired;
    public float $amount;
    public string $currency;
    public Order $order;
    public array $captures;
    public array $refunds;

    public function __construct(
        ?string $id = null,
        ?string $createdAt = null,
        ?string $expiresAt = null,
        ?string $closedAt = null,
        ?string $status = null,
        ?bool $isTest = null,
        ?bool $isExpired = null,
        ?float $amount = null,
        ?string $currency = null,
        ?Order $order = null,
        ?array $captures = [],
        ?array $refunds = [],
    ) {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->expiresAt = $expiresAt;
        $this->closedAt = $closedAt;
        $this->status = $status;
        $this->isTest = $isTest;
        $this->isExpired = $isExpired;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->order = $order;
        $this->captures = $captures;
        $this->refunds = $refunds;
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->createdAt,
            'expires_at' => $this->expiresAt,
            'closed_at' => $this->closedAt,
            'status' => $this->status,
            'is_test' => $this->isTest,
            'is_expired' => $this->isExpired,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'order' => $this->order?->toArray(),
            'captures' => array_map(fn($capture) => $capture->toArray(), $this->captures),
            'refunds' => array_map(fn($refund) => $refund->toArray(), $this->refunds),
        ];
    }

    // Populate the object from an array
    public static function fromArray(array $data): self
    {
        $captures = array_map(function ($capture) {
            return PaymentCapture::fromArray($capture);
        }, $data['captures'] ?? []);

        $refunds = array_map(function ($refund) {
            return PaymentRefund::fromArray($refund);
        }, $data['refunds'] ?? []);

        return new self(
            id: $data['id'] ?? null,
            createdAt: $data['created_at'] ?? null,
            expiresAt: $data['expires_at'] ?? null,
            closedAt: $data['closed_at'] ?? null,
            status: $data['status'] ?? null,
            isTest: $data['is_test'] ?? null,
            isExpired: $data['is_expired'] ?? null,
            amount: floatval($data['amount'] ?? 0.00),
            currency: $data['currency'] ?? null,
            order: $data['order'] ? Order::fromArray($data['order']) : null,
            captures: $captures ?? [],
            refunds: $refunds ?? [],
        );
    }
}