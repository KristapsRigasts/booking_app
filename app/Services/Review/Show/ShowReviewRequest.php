<?php

namespace App\Services\Review\Show;

class ShowReviewRequest
{
    private int $apartmentId;

    public function __construct(int $apartmentId)
    {
        $this->apartmentId = $apartmentId;
    }

    public function getApartmentId(): int
    {
        return $this->apartmentId;
    }
}