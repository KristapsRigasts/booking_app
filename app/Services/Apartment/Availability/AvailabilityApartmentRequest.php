<?php

namespace App\Services\Apartment\Availability;

class AvailabilityApartmentRequest
{
    private string $availabilityFrom;
    private string $availabilityTill;

    public function __construct(string $availabilityFrom, string $availabilityTill)
    {
        $this->availabilityFrom = $availabilityFrom;
        $this->availabilityTill = $availabilityTill;
    }

    public function getAvailabilityFrom(): string
    {
        return $this->availabilityFrom;
    }

    public function getAvailabilityTill(): string
    {
        return $this->availabilityTill;
    }
}