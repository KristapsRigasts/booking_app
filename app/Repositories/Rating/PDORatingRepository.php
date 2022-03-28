<?php

namespace App\Repositories\Rating;

use App\Connection;
use App\Redirect;

class PDORatingRepository implements RatingRepository
{
    public function addRating(int $userId, int $apartmentId, float $rating): void
    {
        $apartmentRateQuery=Connection::connection()
            ->createQueryBuilder()
            ->select('id')
            ->from('apartments_ratings')
            ->where('apartments_id = ?', 'user_id = ?')
            ->setParameter(0, $apartmentId)
            ->setParameter(1,  $userId)
            ->executeQuery()
            ->fetchAllAssociative();

        if(empty($apartmentRateQuery))
        {
            Connection::connection()
                ->insert('apartments_ratings', [
                    'user_id' => $userId,
                    'apartments_id' => $apartmentId,
                    'rating' => $rating,
                ]);}

    }

    public function showRating(int $apartmentId): int
    {
        $apartmentRateQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('avg(rating)')
            ->from('apartments_ratings')
            ->where('apartments_id = ?')
            ->setParameter(0, $apartmentId)
            ->executeQuery()
            ->fetchOne();

        if($apartmentRateQuery == null)
        {
            $apartmentRateQuery = 5;
        }

        return $apartmentRateQuery;
    }
}