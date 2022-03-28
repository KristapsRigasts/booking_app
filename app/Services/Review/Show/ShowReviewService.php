<?php

namespace App\Services\Review\Show;

use App\Repositories\Review\PDOReviewRepository;
use App\Repositories\Review\ReviewRepository;

class ShowReviewService
{
    private ReviewRepository $reviewRepository;

    public function __construct()
    {
        $this->reviewRepository = new PDOReviewRepository();
    }
    public function execute(ShowReviewRequest $request): array
    {
        return $this->reviewRepository->showReviews($request->getApartmentId());
    }
}