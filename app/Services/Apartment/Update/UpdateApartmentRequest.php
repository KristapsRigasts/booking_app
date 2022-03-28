<?php

namespace App\Services\Apartment\Update;

class UpdateApartmentRequest
{
    private string $name;
    private string $address;
    private string $description;
    private int $apartment_id;

    public function __construct(string $name, string $address, string $description, int $apartment_id)
    {
        $this->name = $name;
        $this->address = $address;
        $this->description = $description;
        $this->apartment_id = $apartment_id;
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

    public function getApartmentId(): int
    {
        return $this->apartment_id;
    }
}