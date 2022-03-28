<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\Rating\Add\AddRatingApartmentRequest;
use App\Services\Rating\Add\AddRatingApartmentService;

class ApartmentsRatingsController
{
    public function rate(array $vars): Redirect
    {
        $service = new AddRatingApartmentService();
        $service->execute(new AddRatingApartmentRequest($_SESSION['userid'], $vars['id'], $_POST['rating']));

        return new Redirect('/apartments/' . $vars['id']);
    }
}