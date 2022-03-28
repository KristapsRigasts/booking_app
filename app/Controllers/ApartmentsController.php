<?php

namespace App\Controllers;

use App\Exceptions\FormValidationException;
use App\Redirect;
use App\Services\Apartment\Availability\AvailabilityApartmentRequest;
use App\Services\Apartment\Availability\AvailabilityApartmentService;
use App\Services\Apartment\Confirmation\ConfirmationApartmentRequest;
use App\Services\Apartment\Confirmation\ConfirmationApartmentService;
use App\Services\Apartment\Edit\EditApartmentRequest;
use App\Services\Apartment\Edit\EditApartmentService;
use App\Services\Apartment\Index\IndexApartmentService;
use App\Services\Apartment\Reservation\ReservationApartmentRequest;
use App\Services\Apartment\Reservation\ReservationApartmentService;
use App\Services\Apartment\Show\ShowApartmentRequest;
use App\Services\Apartment\Show\ShowApartmentService;
use App\Services\Apartment\Store\StoreApartmentRequest;
use App\Services\Apartment\Store\StoreApartmentService;
use App\Services\Apartment\Update\UpdateApartmentRequest;
use App\Services\Apartment\Update\UpdateApartmentService;
use App\Services\Rating\Show\ShowRatingApartmentRequest;
use App\Services\Rating\Show\ShowRatingApartmentService;
use App\Services\Review\Show\ShowReviewRequest;
use App\Services\Review\Show\ShowReviewService;
use App\Validation\Errors;
use App\Validation\FormValidator;
use App\View;
use Carbon\Carbon;
use App\Services\Apartment\Delete;

class ApartmentsController
{
    public function index(): View
    {
        $service = new IndexApartmentService();
        $apartments = $service->execute();

        return new View('Apartments/index', [
            'apartments' => $apartments,
            'userName' => $_SESSION['name'],
            'userId' => $_SESSION['userid'],
        ]);
    }

    public function show(array $vars): View
    {
        $showService = new ShowApartmentService();
        $apartment = $showService->execute(new ShowApartmentRequest((int)$vars['id']));

        $reviewService = new ShowReviewService();
        $reviews = $reviewService->execute(new ShowReviewRequest($vars['id']));

        $ratingService = new ShowRatingApartmentService();
        $apartmentRateQuery = $ratingService->execute(new ShowRatingApartmentRequest((int)$vars['id']));

        return new View('Apartments/show', [
            'apartment' => $apartment,
            'reviews' => $reviews ?? [],
            'apartmentRating' => $apartmentRateQuery,
            'id' => $vars['id'],
            'userName' => $_SESSION['name'],
            'userId' => $_SESSION['userid'],
            'errors' => Errors::getAll(),
            'inputs' => $_SESSION['inputs'] ?? []
        ]);
    }

    public function create(): View
    {
        return new View('Apartments/create', [
            'userName' => $_SESSION['name'],
            'userId' => $_SESSION['userid'],
            'errors' => Errors::getAll(),
            'inputs' => $_SESSION['inputs'] ?? []
        ]);
    }

    public function store(): Redirect
    {
        try {
            $validator = (new FormValidator($_POST, [
                'name' => ['required'],
                'address' => ['required'],
                'description' => ['required'],
                'available_from' => ['required'],
                'available_till' => ['required'],
                'rate' => ['required']
            ]));
            $validator->passes();

            $service = new StoreApartmentService();
            $service->execute(new StoreApartmentRequest(
                $_POST['name'],
                $_POST['address'],
                $_POST['description'],
                $_POST['available_from'],
                $_POST['available_till'],
                $_SESSION['userid'],
                $_POST['rate']
            ));

            return new Redirect('/apartments');

        } catch (FormValidationException $exception) {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['inputs'] = $_POST;

            return new Redirect('/apartments/create');
        }
    }

    public function delete(array $vars): Redirect
    {
        $service = new Delete\DeleteApartmentService();
        $service->execute(new Delete\DeleteApartmentRequest($vars['id']));

        return new Redirect('/apartments');
    }

    public function edit(array $vars): View
    {
        $service = new EditApartmentService();
        $apartment = $service->execute(new EditApartmentRequest($vars['id']));

        return new View('Apartments/edit', [
            'apartment' => $apartment,
            'userName' => $_SESSION['name'],
            'userId' => $_SESSION['userid']
        ]);
    }

    public function update(array $vars): Redirect
    {
        $service = new UpdateApartmentService();
        $service->execute(new UpdateApartmentRequest(
            $_POST['name'],
            $_POST['address'],
            $_POST['description'],
            (int)$vars['id']
        ));

        return new Redirect('/apartments/' . $vars['id']);
    }

    public function check(array $vars): Redirect
    {
        $_SESSION['availability_from'] = $_POST['available_from'];
        $_SESSION['availability_till'] = $_POST['available_till'];

        return new Redirect('/apartments/availability');
    }

    public function availability(array $vars): View
    {
        if ($_SESSION['availability_from'] == "") {
            $availabilityFrom = Carbon::today()->toDateString();
        } else {
            $availabilityFrom = $_SESSION['availability_from'];
        }

        $availabilityF = Carbon::createFromFormat('Y-m-d', $availabilityFrom);

        if ($_SESSION['availability_till'] == "") {
            $availabilityTill = $availabilityF->addDay()->toDateString();
        } else {
            $availabilityTill = $_SESSION['availability_till'];
        }

        $availabilityT = Carbon::createFromFormat('Y-m-d', $availabilityTill);

        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        if ($availabilityF->lt($today)) {
            $availabilityFrom = Carbon::today()->toDateString();
        }

        if ($availabilityT->lt($tomorrow)) {
            $availabilityTill = Carbon::tomorrow()->toDateString();
        }

        $_SESSION['availability_from'] = $availabilityFrom;
        $_SESSION['availability_till'] = $availabilityTill;

        $service = new AvailabilityApartmentService();
        $apartments = $service->execute(new AvailabilityApartmentRequest($availabilityFrom, $availabilityTill));

        return new View ('Apartments/availability', [
            'availabilityFrom' => $availabilityFrom,
            'availabilityTill' => $availabilityTill,
            'apartments' => $apartments,
            'userName' => $_SESSION['name'],
            'userId' => $_SESSION['userid']
        ]);
    }

    public function reservation(array $vars): Redirect
    {
        $service = new ReservationApartmentService();
        $service->execute(new ReservationApartmentRequest(
            $_SESSION['userid'],
            (int)$vars['id'],
            $_SESSION['availability_from'],
            $_SESSION['availability_till']
        ));

        return new Redirect("/apartments/{$vars['id']}/confirmation");
    }

    public function confirmation(array $vars): View
    {
        $service = new ConfirmationApartmentService();
        $apartment = $service->execute(new ConfirmationApartmentRequest((int)$vars['id']));

        $reservationStartingDay = $_SESSION['availability_from'];
        $reservationEndingDay = $_SESSION['availability_till'];

        $startingDay = strtotime($reservationStartingDay);
        $endingDay = strtotime($reservationEndingDay);
        $totalAmount = ($endingDay - $startingDay) / 86400 * $apartment->getRate();

        unset($_SESSION['availability_from']);
        unset($_SESSION['availability_till']);

        return new View('Reservations/confirmation', [
            'apartment' => $apartment,
            'startingDay' => $reservationStartingDay,
            'endingDay' => $reservationEndingDay,
            'totalAmount' => $totalAmount,
            'userName' => $_SESSION['name'],
            'userId' => $_SESSION['userid']
        ]);
    }
}
