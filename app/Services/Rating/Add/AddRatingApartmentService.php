<?php

namespace App\Services\Rating\Add;


use App\Repositories\Rating\PDORatingRepository;
use App\Repositories\Rating\RatingRepository;

class AddRatingApartmentService
{
    private RatingRepository $ratingRepository;

    public function __construct()
    {
        $this->ratingRepository = new PDORatingRepository();
    }

    public function execute(AddRatingApartmentRequest $request): void
    {
        $this->ratingRepository->addRating($request->getUserId(), $request->getApartmentId(), $request->getRating());

    }
}