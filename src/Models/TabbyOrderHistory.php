<?php

namespace Tabby\Models;

use Illuminate\Support\Carbon;

class TabbyOrderHistory
{
    protected float $amount;
    protected string $paymentMethod;
    protected string $status;
    protected string $purchasedAt;

    public function __construct(
        float $amount,
        string $paymentMethod = 'card',
        string $status = 'new',
        ?string $purchasedAt = null,
    ) {
        $this->amount = $amount;
        $this->paymentMethod = $paymentMethod;
        $this->status = $status;
        $this->purchasedAt = $purchasedAt ?? Carbon::now()->toIso8601ZuluString();
    }

    public function toArray(): array
    {
        return [
            'purchased_at' => $this->purchasedAt,
            'amount' => number_format($this->amount, 2),
            'payment_method' => $this->paymentMethod,
            'status' => $this->status,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['amount'] ?? 0.0,
            $data['payment_method'] ?? 'card',
            $data['status'] ?? 'new',
            $data['purchased_at'] ?? Carbon::now()->toIso8601ZuluString(),
        );
    }
}
