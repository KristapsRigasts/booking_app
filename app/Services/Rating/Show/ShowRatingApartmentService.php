<?php

namespace App\Services\Rating\Show;

use App\Repositories\Rating\PDORatingRepository;
use App\Repositories\Rating\RatingRepository;

class ShowRatingApartmentService
{
    private RatingRepository $ratingRepository;

    public function __construct()
    {
        $this->ratingRepository = new PDORatingRepository();
    }

    public function execute(ShowRatingApartmentRequest $request): int
    {
        return $this->ratingRepository->showRating($request->getApartmentId());
    }
}