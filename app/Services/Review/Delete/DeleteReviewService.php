<?php

namespace App\Services\Review\Delete;

use App\Repositories\Review\PDOReviewRepository;
use App\Repositories\Review\ReviewRepository;

class DeleteReviewService
{
    private ReviewRepository $reviewRepository;

    public function __construct()
    {
        $this->reviewRepository = new PDOReviewRepository();
    }

    public function execute(DeleteReviewRequest $request): void
    {
        $this->reviewRepository->deleteReview($request->getReviewId());
    }
}