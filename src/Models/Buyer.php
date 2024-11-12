<?php

namespace Tabby\Models;

class Buyer
{
    protected string $phone;
    protected string $email;
    protected string $name;
    protected ?string $dob;

    public function __construct(
        string $phone,
        string $email,
        string $name,
        ?string $dob = null
    ) {
        $this->phone = $phone;
        $this->email = $email;
        $this->name = $name;
        $this->dob = $dob;
    }

    public function toArray(): array
    {
        return [
            'phone' => $this->phone,
            'email' => $this->email,
            'name' => $this->name,
            'dob' => $this->dob,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['phone'] ?? '',
            $data['email'] ?? '',
            $data['name'] ?? '',
            $data['dob'] ?? null,
        );
    }
}
