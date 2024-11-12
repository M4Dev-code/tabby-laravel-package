<?php

namespace Tabby\Models;

class Configuration
{
    private array $availableProducts;
    private ?string $expiresAt;
    private array $products;

    public function __construct(
        array $availableProducts,
        ?string $expiresAt = null,
        array $products
    ) {
        $this->availableProducts = $availableProducts;
        $this->expiresAt = $expiresAt;
        $this->products = $products;
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            'available_products' => $this->availableProducts,
            'expires_at' => $this->expiresAt,
            'products' => $this->products,
        ];
    }

    // Populate the object from an array
    public static function fromArray(array $data): self
    {
        return new self(
            $data['available_products'] ?? [],
            $data['expires_at'] ?? null,
            $data['products'] ?? [],
        );
    }

    // Getters
    public function getAvailableProducts(): array
    {
        return $this->availableProducts;
    }

    public function getExpiresAt(): string
    {
        return $this->expiresAt;
    }

    public function getProducts(): array
    {
        return $this->products;
    }
}