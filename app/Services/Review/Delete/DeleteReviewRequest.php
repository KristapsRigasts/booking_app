<?php

namespace App\Services\Review\Delete;

class DeleteReviewRequest
{
    private int $reviewId;

    public function __construct(int $reviewId)
    {
        $this->reviewId = $reviewId;
    }

    public function getReviewId(): int
    {
        return $this->reviewId;
    }
}