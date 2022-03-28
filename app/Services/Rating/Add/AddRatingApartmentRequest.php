<?php

namespace App\Services\Rating\Add;

class AddRatingApartmentRequest
{
    private int $userId;
    private int $apartmentId;
    private float $rating;

    public function __construct(int $userId, int $apartmentId, float $rating)
    {
        $this->userId = $userId;
        $this->apartmentId = $apartmentId;
        $this->rating = $rating;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getApartmentId(): int
    {
        return $this->apartmentId;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

}