<?php

namespace App\Services\Review\Store;

class StoreReviewRequest
{
    private int $userId;
    private int $apartmentId;
    private string $review;

    public function __construct(int $userId, int $apartmentId, string $review)
    {
        $this->userId = $userId;
        $this->apartmentId = $apartmentId;
        $this->review = $review;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getApartmentId(): int
    {
        return $this->apartmentId;
    }

    public function getReview(): string
    {
        return $this->review;
    }
}