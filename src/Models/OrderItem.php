<?php

namespace Tabby\Models;

class OrderItem
{
    private string $title;
    private ?string $description;
    private int $quantity;
    private float $unitPrice;
    private float $discountAmount;
    private ?string $referenceId;
    private ?string $imageUrl;
    private ?string $productUrl;
    private ?string $gender;
    private string $category;
    private ?string $color;
    private ?string $productMaterial;
    private ?string $sizeType;
    private ?string $size;
    private ?string $brand;
    private bool $isRefundable;

    public function __construct(
        string $title,
        string $category,
        float $unitPrice,
        float $discountAmount = 0.00,
        int $quantity = 1,
        bool $isRefundable = false,
        ?string $description = null,
        ?string $referenceId = null,
        ?string $imageUrl = null,
        ?string $productUrl = null,
        ?string $gender = null,
        ?string $color = null,
        ?string $productMaterial = null,
        ?string $sizeType = null,
        ?string $size = null,
        ?string $brand = null,
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->discountAmount = $discountAmount;
        $this->referenceId = $referenceId;
        $this->imageUrl = $imageUrl;
        $this->productUrl = $productUrl;
        $this->gender = $gender;
        $this->category = $category;
        $this->color = $color;
        $this->productMaterial = $productMaterial;
        $this->sizeType = $sizeType;
        $this->size = $size;
        $this->brand = $brand;
        $this->isRefundable = $isRefundable;
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit_price' => number_format($this->unitPrice, 2, '.', ''),
            'discount_amount' => number_format($this->discountAmount, 2, '.', ''),
            'reference_id' => $this->referenceId,
            'image_url' => $this->imageUrl,
            'product_url' => $this->productUrl,
            'gender' => $this->gender,
            'category' => $this->category,
            'color' => $this->color,
            'product_material' => $this->productMaterial,
            'size_type' => $this->sizeType,
            'size' => $this->size,
            'brand' => $this->brand,
            'is_refundable' => $this->isRefundable,
        ];
    }

    // Populate the object from an array
    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'] ?? '',
            $data['category'] ?? '',
            floatval($data['unit_price'] ?? 0.00),
            floatval($data['discount_amount'] ?? 0.00),
            $data['quantity'] ?? 1,
            $data['is_refundable'] ?? false,
            $data['description'] ?? null,
            $data['reference_id'] ?? null,
            $data['image_url'] ?? null,
            $data['product_url'] ?? null,
            $data['gender'] ?? null,
            $data['color'] ?? null,
            $data['product_material'] ?? null,
            $data['size_type'] ?? null,
            $data['size'] ?? null,
            $data['brand'] ?? null,
        );
    }

    // Getters
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getProductUrl(): string
    {
        return $this->productUrl;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getProductMaterial(): ?string
    {
        return $this->productMaterial;
    }

    public function getSizeType(): ?string
    {
        return $this->sizeType;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function isRefundable(): bool
    {
        return $this->isRefundable;
    }
}
