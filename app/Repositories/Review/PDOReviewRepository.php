<?php

namespace App\Repositories\Review;

use App\Connection;
use App\Models\Review;

class PDOReviewRepository implements ReviewRepository
{

    public function showReviews(int $apartmentId): array
    {
        $reviewsQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('reviews')
            ->where('apartment_id = ?')
            ->orderBy('created_at','desc')
            ->setParameter(0, $apartmentId)
            ->executeQuery()
            ->fetchAllAssociative();

        $reviews=[];

        foreach ($reviewsQuery as $reviewData)
        {
            $userQuery = Connection::connection()
                ->createQueryBuilder()
                ->select('*')
                ->from('users')
                ->where('id = ?')
                ->setParameter(0, $reviewData['user_id'])
                ->executeQuery()
                ->fetchAssociative();

            $reviews[] = new Review(
                $userQuery['name'],
                $userQuery['surname'],
                $reviewData['user_id'],
                $reviewData['review'],
                $reviewData['apartment_id'],
                $reviewData['created_at'],
                $reviewData['id'],
            );
        }
        return $reviews;
    }

    public function storeReview(int $userId, int $apartmentId, string $review): void
    {
        Connection::connection()
            ->insert('reviews', [
                'apartment_id' => $apartmentId,
                'user_id' => $userId,
                'review' => $review,
            ]);
    }

    public function deleteReview(int $reviewId): void
    {
        Connection::connection()
            ->delete('reviews', ['id' => $reviewId]);
    }
}
