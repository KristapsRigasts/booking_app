<?php

namespace App\Services\Reservation\Add;

class AddReservationRequest
{
    private int $userId;
    private int $apartmentId;
    private string $reservedFrom;
    private string $reservedTill;

    public function __construct(int $userId, int $apartmentId, string $reservedFrom, string $reservedTill)
    {
        $this->userId = $userId;
        $this->apartmentId = $apartmentId;
        $this->reservedFrom = $reservedFrom;
        $this->reservedTill = $reservedTill;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getApartmentId(): int
    {
        return $this->apartmentId;
    }

    public function getReservedFrom(): string
    {
        return $this->reservedFrom;
    }

    public function getReservedTill(): string
    {
        return $this->reservedTill;
    }
}

