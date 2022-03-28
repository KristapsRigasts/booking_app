<?php

namespace App\Repositories\Rating;

interface RatingRepository
{
    public function addRating(int $userId, int $apartmentId, float $rating);
    public function showRating(int $apartmentId);
}