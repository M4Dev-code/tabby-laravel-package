<?php

namespace Tabby\Models;

class TabbyOrderItem
{
    protected string $title;
    protected string $description;
    protected int $quantity;
    protected float $unitPrice;
    protected string $referenceId;
    protected ?string $productUrl;
    protected string $category;
    protected ?float $discountAmount;

    public function __construct(
        string $title,
        string $description,
        int $quantity,
        float $unitPrice,
        string $referenceId,
        string $category,
        ?string $productUrl = null,
        ?float $discountAmount = null
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->referenceId = $referenceId;
        $this->productUrl = $productUrl;
        $this->category = $category;
        $this->discountAmount = $discountAmount;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit_price' => number_format($this->unitPrice, 2),
            'reference_id' => $this->referenceId,
            'product_url' => $this->productUrl,
            'category' => $this->category,
            'discount_amount' => $this->discountAmount ? number_format($this->discountAmount, 2) : null,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['quantity'] ?? 1,
            $data['unit_price'] ?? 0.0,
            $data['reference_id'] ?? '',
            $data['category'] ?? '',
            $data['product_url'] ?? null,
            $data['discount_amount'] ?? null
        );
    }
}
