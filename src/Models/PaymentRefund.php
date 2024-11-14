<?php

namespace Tabby\Models;

use Illuminate\Support\Carbon;

class PaymentRefund
{
    private ?string $id;
    private float $amount;
    private ?string $referenceId;
    private ?string $reason;
    private ?string $createdAt;
    private array $items;

    public function __construct(
        ?string $id = null,
        float $amount,
        string $referenceId = null,
        string $reason = null,
        string $createdAt = null,
        array $items = []
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->referenceId = $referenceId;
        $this->reason = $reason;
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
            'reason' => $this->reason,
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
            $data['reason'] ?? null,
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

    public function getReason(): string
    {
        return $this->reason;
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
