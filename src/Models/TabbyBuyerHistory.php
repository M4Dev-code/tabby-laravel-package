<?php

namespace Tabby\Models;

use Illuminate\Support\Carbon;

class TabbyBuyerHistory
{
    protected string $registeredSince;
    protected int $loyaltyLevel;
    protected int $wishlistCount;
    protected bool $isSocialNetworksConnected;
    protected bool $isPhoneNumberVerified;
    protected bool $isEmailVerified;

    public function __construct(
        ?string $registeredSince = null,
        int $loyaltyLevel = 0,
        int $wishlistCount = 0,
        bool $isSocialNetworksConnected = false,
        bool $isPhoneNumberVerified = false,
        bool $isEmailVerified = false
    ) {
        $this->registeredSince = $registeredSince ?? Carbon::now()->toIso8601ZuluString();
        $this->loyaltyLevel = $loyaltyLevel;
        $this->wishlistCount = $wishlistCount;
        $this->isSocialNetworksConnected = $isSocialNetworksConnected;
        $this->isPhoneNumberVerified = $isPhoneNumberVerified;
        $this->isEmailVerified = $isEmailVerified;
    }

    public function toArray(): array
    {
        return [
            'registered_since' => $this->registeredSince,
            'loyalty_level' => $this->loyaltyLevel,
            'wishlist_count' => $this->wishlistCount,
            'is_social_networks_connected' => $this->isSocialNetworksConnected,
            'is_phone_number_verified' => $this->isPhoneNumberVerified,
            'is_email_verified' => $this->isEmailVerified,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['registered_since'] ?? Carbon::now()->toIso8601ZuluString(),
            $data['loyalty_level'] ?? 0,
            $data['wishlist_count'] ?? 0,
            $data['is_social_networks_connected'] ?? false,
            $data['is_phone_number_verified'] ?? false,
            $data['is_email_verified'] ?? false
        );
    }
}
