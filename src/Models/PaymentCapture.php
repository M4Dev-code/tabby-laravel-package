<?php

namespace Tabby\Models;

use Illuminate\Support\Carbon;

class PaymentCapture
{
    private ?string $id;
    private float $amount;
    private ?string $referenceId; // Idempotency key. Used to avoid similar capture requests.
    private float $taxAmount;
    private float $shippingAmount;
    private float $discountAmount;
    private ?string $createdAt;
    private array $items;

    public function __construct(
        ?string $id = null,
        float $amount,
        string $referenceId = null,
        float $taxAmount = 0.00,
        float $shippingAmount = 0.00,
        float $discountAmount = 0.00,
        string $createdAt = null,
        array $items = []
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->referenceId = $referenceId;
        $this->taxAmount = $taxAmount;
        $this->shippingAmount = $shippingAmount;
        $this->discountAmount = $discountAmount;
        $this->createdAt = $createdAt ?? Carbon::now()->toIso8601ZuluString();
        $this->items = $items;
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'amount' => number_format($this->amount, 2, '.', ''),
            'reference_id' => $this->referenceId,
            'tax_amount' => number_format($this->taxAmount, 2, '.', ''),
            'shipping_amount' => number_format($this->shippingAmount, 2, '.', ''),
            'discount_amount' => number_format($this->discountAmount, 2, '.', ''),
            'created_at' => $this->createdAt,
            'items' => array_map(function ($item) {
                return $item->toArray();
            }, $this->items),
        ];
    }

    // Populate the object from an array
    public static function fromArray(array $data): self
    {
        $items = array_map(function ($itemData) {
            return OrderItem::fromArray($itemData);
        }, $data['items'] ?? []);

        return new self(
            $data['id'] ?? null,
            floatval($data['amount'] ?? 0.00),
            $data['reference_id'] ?? null,
            floatval($data['tax_amount'] ?? 0.00),
            floatval($data['shipping_amount'] ?? 0.00),
            floatval($data['discount_amount'] ?? 0.00),
            $data['created_at'] ?? null,
            $items
        );
    }

    // Getters and setters (if needed)
    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function getTaxAmount(): float
    {
        return $this->taxAmount;
    }

    public function getShippingAmount(): float
    {
        return $this->shippingAmount;
    }

    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
