<?php

namespace App\Services\Reservation\Show;

class ShowReservationRequest
{
    private string $reservedFrom;
    private string $reservedTill;

    public function __construct(string $reservedFrom, string $reservedTill)
    {
        $this->reservedFrom = $reservedFrom;
        $this->reservedTill = $reservedTill;
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