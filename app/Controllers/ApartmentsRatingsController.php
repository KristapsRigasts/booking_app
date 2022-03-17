<?php

namespace  App\Controllers;

use App\Connection;
use App\Redirect;

class ApartmentsRatingsController
{
    public function rate(array $vars): Redirect
    {

        $articleRateQuery=Connection::connection()
            ->createQueryBuilder()
            ->select('id')
            ->from('apartments_ratings')
            ->where('apartments_id = ?', 'user_id = ?')
            ->setParameter(0, (int) $vars['id'])
            ->setParameter(1, (int) $_SESSION['userid'])
            ->executeQuery()
            ->fetchAllAssociative();

        if(!empty($articleRateQuery))
        {
            return new Redirect('/apartments/'. $vars['id'] . '?error=youalreadyratedthispost');
        }
        else {
            Connection::connection()
                ->insert('apartments_ratings', [
                    'user_id' => $_SESSION['userid'],
                    'apartments_id' => $vars['id'],
                    'rating' => $_POST['rating'],
                ]);
            return new Redirect('/apartments/'. $vars['id']);
        }

    }

}