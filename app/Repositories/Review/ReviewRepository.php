<?php

namespace App\Repositories\Review;

interface ReviewRepository
{
    public function showReviews(int $apartmentId);
    public function storeReview(int $userId, int $apartmentId, string $review);
    public function deleteReview(int $reviewId);
}