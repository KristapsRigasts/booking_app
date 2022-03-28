<?php

namespace App\Services\Apartment\Store;

class StoreApartmentRequest
{
    private string $name;
    private string $address;
    private string $description;
    private string $availableFrom;
    private string $availableTill;
    private int $userId;
    private int $ratePerNight;

    public function __construct(string $name, string $address, string $description,
                                string $availableFrom, string $availableTill, int $userId, int $ratePerNight)
    {
        $this->name = $name;
        $this->address = $address;
        $this->description = $description;
        $this->availableFrom = $availableFrom;
        $this->availableTill = $availableTill;
        $this->userId = $userId;
        $this->ratePerNight = $ratePerNight;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAvailableFrom(): string
    {
        return $this->availableFrom;
    }

    public function getAvailableTill(): string
    {
        return $this->availableTill;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRatePerNight(): int
    {
        return $this->ratePerNight;
    }
}