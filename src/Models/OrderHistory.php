<?php

namespace Tabby\Models;

use Illuminate\Support\Carbon;

class OrderHistory
{
    private string $purchasedAt;
    private string $amount;
    private string $paymentMethod; // Enum: "card" "cod"
    private string $status; // Enum: "new" "processing" "complete" "refunded" "canceled" "unknown"
    private Buyer $buyer;
    private ShippingAddress $shippingAddress;
    private array $items;

    public function __construct(
        string $amount,
        Buyer $buyer,
        ShippingAddress $shippingAddress,
        string $purchasedAt = null,
        string $paymentMethod = "card",
        string $status = "new",
        array $items = []
    ) {
        $this->purchasedAt = $purchasedAt ?? Carbon::now()->toIso8601ZuluString();
        $this->amount = $amount;
        $this->paymentMethod = $paymentMethod;
        $this->status = $status;
        $this->buyer = $buyer;
        $this->shippingAddress = $shippingAddress;
        $this->items = $items;
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            'purchased_at' => $this->purchasedAt,
            'amount' => $this->amount,
            'payment_method' => $this->paymentMethod,
            'status' => $this->status,
            'buyer' => $this->buyer->toArray(),
            'shipping_address' => $this->shippingAddress->toArray(),
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
            $data['amount'] ?? 0.00,
            Buyer::fromArray($data['buyer'] ?? []),
            ShippingAddress::fromArray($data['shipping_address'] ?? []),
            $data['purchased_at'] ?? null,
            $data['payment_method'] ?? "card",
            $data['status'] ?? "new",
            $items
        );
    }

    // Getters and setters (if needed)
    public function getPurchasedAt(): string
    {
        return $this->purchasedAt;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function getShippingAddress(): ShippingAddress
    {
        return $this->shippingAddress;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
