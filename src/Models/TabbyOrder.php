<?php

namespace Tabby\Models;

class TabbyOrder
{
    protected string $referenceId;
    /** @var TabbyOrderItem[] */
    protected array $items;
    protected ?float $taxAmount;
    protected ?float $shippingAmount;
    protected ?float $discountAmount;
    protected ?string $updatedAt;

    public function __construct(
        string $referenceId,
        array $items,
        ?float $taxAmount = null,
        ?float $shippingAmount = null,
        ?float $discountAmount = null,
        ?string $updatedAt = null
    ) {
        foreach ($items as $item) {
            if (!$item instanceof TabbyOrderItem) {
                throw new \InvalidArgumentException("Each item must be an instance of TabbyOrderItem");
            }
        }

        $this->referenceId = $referenceId;
        $this->items = $items;
        $this->taxAmount = $taxAmount;
        $this->shippingAmount = $shippingAmount;
        $this->discountAmount = $discountAmount;
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        $itemsArr = [];
        foreach ($this->items as $item) {
            $itemsArr[] = $item->toArray();
        }

        return [
            'reference_id' => $this->referenceId,
            'items' => $itemsArr,
            'tax_amount' => $this->taxAmount,
            'shipping_amount' => $this->shippingAmount,
            'discount_amount' => $this->discountAmount,
            'updated_at' => $this->updatedAt,
        ];
    }

    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = TabbyOrderItem::fromArray($item);
        }

        return new self(
            $data['reference_id'] ?? '',
            $items,
            $data['tax_amount'] ?? null,
            $data['shipping_amount'] ?? null,
            $data['discount_amount'] ?? null,
            $data['updated_at'] ?? null
        );
    }
}
