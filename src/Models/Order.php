<?php

namespace Tabby\Models;

use Illuminate\Support\Carbon;

class Order
{
    private float $taxAmount;
    private float $shippingAmount;
    private float $discountAmount;
    private string $updatedAt;
    private string $referenceId;
    private array $items;

    public function __construct(
        string $referenceId,
        array $items = [],
        float $taxAmount = 0.00,
        float $shippingAmount = 0.00,
        float $discountAmount = 0.00,
        ?string $updatedAt = null,
    ) {
        $this->taxAmount = $taxAmount;
        $this->shippingAmount = $shippingAmount;
        $this->discountAmount = $discountAmount;
        $this->updatedAt = $updatedAt ?? Carbon::now()->toIso8601ZuluString();
        $this->referenceId = $referenceId;
        $this->items = $items;
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            'tax_amount' => number_format($this->taxAmount, 2, '.', ''),
            'shipping_amount' => number_format($this->shippingAmount, 2, '.', ''),
            'discount_amount' => number_format($this->discountAmount, 2, '.', ''),
            'updated_at' => $this->updatedAt,
            'reference_id' => $this->referenceId,
            'items' => array_map(
                fn($item) => $item->toArray(),
                $this->items
            ),
        ];
    }

    // Populate the object from an array
    public static function fromArray(array $data): self
    {
        $items = array_map(
            fn($itemData) => OrderItem::fromArray($itemData),
            $data['items'] ?? []
        );

        return new self(
            $data['reference_id'] ?? '',
            $items,
            floatval($data['tax_amount'] ?? 0.00),
            floatval($data['shipping_amount'] ?? 0.00),
            floatval($data['discount_amount'] ?? 0.00),
            $data['updated_at'] ?? null,
        );
    }

    // Getters
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

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
