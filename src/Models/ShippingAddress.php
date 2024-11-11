<?php

namespace Tabby\Models;

class ShippingAddress
{
    protected string $city;
    protected string $address;
    protected string $zip;

    public function __construct(string $city, string $address, string $zip)
    {
        $this->city = $city;
        $this->address = $address;
        $this->zip = $zip;
    }

    public function toArray(): array
    {
        return [
            'city' => $this->city,
            'address' => $this->address,
            'zip' => $this->zip,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['city'] ?? '',
            $data['address'] ?? '',
            $data['zip'] ?? ''
        );
    }
}
