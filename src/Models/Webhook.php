<?php

namespace Tabby\Models;

class Webhook
{
    private ?string $id;
    private string $url;
    private bool $isTest;
    private ?string $headerTitle;
    private ?string $headerValue;

    public function __construct(
        ?string $id = null,
        string $url,
        bool $isTest = true,
        ?string $headerTitle = null,
        ?string $headerValue = null,
    ) {
        $this->id = $id;
        $this->url = $url;
        $this->isTest = $isTest;
        $this->headerTitle = $headerTitle;
        $this->headerValue = $headerValue;
    }

    // Convert the object to an array
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'is_test' => $this->isTest,
            'header' => [
                'title' => $this->headerTitle,
                'value' => $this->headerValue,
            ],
        ];
    }

    // Populate the object from an array
    public static function fromArray(array $data): self
    {
        if (isset($data['header'])) {
            $headerTitle = $data['header']['title'] ?? null;
            $headerValue = $data['header']['value'] ?? null;
        }

        return new self(
            $data['id'] ?? null,
            $data['url'] ?? '',
            $data['is_test'] ?? true,
            $headerTitle ?? null,
            $headerValue ?? null
        );
    }

    // Getters and setters (if needed)
    public function getId(): string
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isTest(): bool
    {
        return $this->isTest;
    }

    public function getHeader(): ?array
    {
        return [
            'title' => $this->headerTitle,
            'value' => $this->headerValue,
        ];
    }
}
