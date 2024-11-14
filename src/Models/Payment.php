<?php

namespace Tabby\Models;

use Illuminate\Support\Carbon;

class Payment
{
    private ?string $id; // Unique identifier for the payment (UUID), assigned by Tabby. Save it on your side!
    private ?string $createdAt;
    private ?string $expiresAt;
    private string $status; // Enum: "CREATED" "AUTHORIZED" "CLOSED" "REJECTED" "EXPIRED"
    private bool $isTest;
    private float $amount;
    private string $currency; // Enum: "AED" "SAR" "KWD" "BHD" "QAR"
    private ?string $description;
    private Buyer $buyer;
    private ShippingAddress $shippingAddress;
    private Order $order;
    private array $captures; // Array of objects (PaymentCapture)
    private array $refunds; // Array of objects (PaymentRefund)
    private BuyerHistory $buyerHistory;
    private array $orderHistory; // Array of objects (OrderHistory)
    private ?array $meta; // Merchant-defined data about the payment. This field is a key-value map.
    private ?array $attachment; // ["body" => json_encode("value"), "content_type" => "application/vnd.tabby.v1+json"]

    public function __construct(
        ?string $id = null,
        float $amount,
        Buyer $buyer,
        ShippingAddress $shippingAddress,
        Order $order,
        bool $isTest = false,
        string $currency = 'SAR',
        ?string $createdAt = null,
        ?string $expiresAt = null,
        string $status = 'CREATED',
        ?string $description = null,
        array $captures = [],
        array $refunds = [],
        BuyerHistory $buyerHistory,
        array $orderHistory = [],
        ?array $meta = null,
        ?array $attachment = null
    ) {
        $this->id = $id;
        $this->createdAt = $createdAt ?? Carbon::now()->toIso8601ZuluString();
        $this->expiresAt = $expiresAt;
        $this->status = $status;
        $this->isTest = $isTest;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->buyer = $buyer;
        $this->shippingAddress = $shippingAddress;
        $this->order = $order;
        $this->captures = $captures;
        $this->refunds = $refunds;
        $this->buyerHistory = $buyerHistory;
        $this->orderHistory = $orderHistory;
        $this->meta = $meta;
        $this->attachment = $attachment;
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->createdAt,
            'expires_at' => $this->expiresAt,
            'status' => $this->status,
            'is_test' => $this->isTest,
            'amount' => number_format($this->amount, 2, '.', ''),
            'currency' => $this->currency,
            'description' => $this->description,
            'buyer' => $this->buyer->toArray(),
            'shipping_address' => $this->shippingAddress->toArray(),
            'order' => $this->order->toArray(),
            'captures' => array_map(fn($capture) => $capture->toArray(), $this->captures),
            'refunds' => array_map(fn($refund) => $refund->toArray(), $this->refunds),
            'buyer_history' => $this->buyerHistory->toArray(),
            'order_history' => array_map(fn($history) => $history->toArray(), $this->orderHistory),
            'meta' => $this->meta,
            'attachment' => $this->attachment,
        ];
    }

    // Populate the object from an array
    public static function fromArray(array $data): self
    {
        $captures = array_map(fn($captureData) => PaymentCapture::fromArray($captureData), $data['captures'] ?? []);
        $refunds = array_map(fn($refundData) => PaymentRefund::fromArray($refundData), $data['refunds'] ?? []);
        $orderHistory = array_map(fn($historyData) => OrderHistory::fromArray($historyData), $data['order_history'] ?? []);

        return new self(
            $data['id'] ?? null,
            floatval($data['amount'] ?? 0.00),
            Buyer::fromArray($data['buyer'] ?? []),
            ShippingAddress::fromArray($data['shipping_address'] ?? []),
            Order::fromArray($data['order'] ?? []),
            $data['is_test'] ?? false,
            $data['currency'] ?? 'SAR',
            $data['created_at'] ?? null,
            $data['expires_at'] ?? null,
            $data['status'] ?? 'CREATED',
            $data['description'] ?? null,
            $captures,
            $refunds,
            BuyerHistory::fromArray($data['buyer_history'] ?? []),
            $orderHistory,
            $data['meta'] ?? null,
            $data['attachment'] ?? null
        );
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getExpiresAt(): string
    {
        return $this->expiresAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isTest(): bool
    {
        return $this->isTest;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function getShippingAddress(): ShippingAddress
    {
        return $this->shippingAddress;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getCaptures(): array
    {
        return $this->captures;
    }

    public function getRefunds(): array
    {
        return $this->refunds;
    }

    public function getBuyerHistory(): BuyerHistory
    {
        return $this->buyerHistory;
    }

    public function getOrderHistory(): array
    {
        return $this->orderHistory;
    }

    public function getMeta(): ?array
    {
        return $this->meta;
    }

    public function getAttachment(): ?array
    {
        return $this->attachment;
    }
}
