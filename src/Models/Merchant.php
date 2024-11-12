<?php

namespace Tabby\Models;

class Merchant
{
    private string $name;
    private string $address;
    private string $logo;

    public function __construct(
        string $name,
        string $address,
        string $logo
    ) {
        $this->name = $name;
        $this->address = $address;
        $this->logo = $logo;
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'logo' => $this->logo,
        ];
    }

    // Populate the object from an array
    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'] ?? '',
            $data['address'] ?? '',
            $data['logo'] ?? '',
        );
    }

    // Getters
    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }
}