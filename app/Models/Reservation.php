<?php

namespace App\Models;

class Reservation
{
    private string $name;
    private string $address;
    private string $reservedFrom;
    private string $reservedTill;
    private int $ratePerNight;
    private int $totalAmount;
    private ?int $userId;
    private ?int $apartmentId;

    public function __construct(string $name, string $address, string $reservedFrom,
                                string $reservedTill, int $ratePerNight, int $totalAmount,
                                ?int $userId = null, ?int $apartmentId = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->reservedFrom = $reservedFrom;
        $this->reservedTill = $reservedTill;
        $this->ratePerNight = $ratePerNight;
        $this->totalAmount = $totalAmount;
        $this->userId = $userId;
        $this->apartmentId = $apartmentId;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getAddress(): string
    {
        return $this->address;
    }


    public function getReservedFrom(): string
    {
        return $this->reservedFrom;
    }


    public function getReservedTill(): string
    {
        return $this->reservedTill;
    }


    public function getRatePerNight(): int
    {
        return $this->ratePerNight;
    }


    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getApartmentId(): ?int
    {
        return $this->apartmentId;
    }
}