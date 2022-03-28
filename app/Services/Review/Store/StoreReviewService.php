<?php

namespace App\Services\Review\Store;

use App\Repositories\Review\PDOReviewRepository;
use App\Repositories\Review\ReviewRepository;

class StoreReviewService
{
    private ReviewRepository $reviewRepository;

    public function __construct()
    {
        $this->reviewRepository = new PDOReviewRepository();
    }

    public function execute(StoreReviewRequest $request): void
    {
        $this->reviewRepository->storeReview($request->getUserId(), $request->getApartmentId(), $request->getReview());
    }
}