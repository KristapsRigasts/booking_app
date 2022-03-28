<?php

namespace App\Controllers;

use App\Exceptions\FormValidationException;
use App\Redirect;
use App\Services\Apartment\Show\ShowApartmentRequest;
use App\Services\Apartment\Show\ShowApartmentService;
use App\Services\Reservation\Add\AddReservationRequest;
use App\Services\Reservation\Add\AddReservationService;
use App\Services\Reservation\Index\IndexReservationRequest;
use App\Services\Reservation\Index\IndexReservationService;
use App\Services\Reservation\Show\ShowReservationRequest;
use App\Services\Reservation\Show\ShowReservationService;
use App\Validation\Errors;
use App\Validation\FormValidator;
use App\View;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ApartmentsReservationController
{
    public function index(array $vars)
    {
        $showService = new ShowApartmentService();
        $apartment = $showService->execute(new ShowApartmentRequest((int)$vars['id']));

        $today = Carbon::today()->format('m-d-Y');
        $endingDay = Carbon::createFromFormat('Y-m-d', $apartment->getAvailableTill())->format('m-d-Y');

        $reservationService = new IndexReservationService();
        $reservationData = $reservationService->execute(new IndexReservationRequest((int)$vars['id']));

        $dates = [];

        foreach ($reservationData as $record) {

            [$startDate, $endDate] = $record;
            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $dates[] = $date->format('m-d-Y');
            }
        }

        return new View('Reservations/reservation', [
            'apartment' => $apartment,
            'id' => $vars['id'],
            'userName' => $_SESSION['name'],
            'userId' => $_SESSION['userid'],
            'reservations' => $dates,
            'startingDate' => $today,
            'endingDate' => $endingDay,
            'errors' => Errors::getAll(),
            'wrong' => $_SESSION['wrong'],
            'inputs' => $_SESSION['inputs'] ?? []
        ]);
    }

    public function show(array $vars): Redirect
    {
        try {
            $validator = (new FormValidator($_POST, [
                'reservation_from' => ['required'],
                'reservation_till' => ['required']
            ]));
            $validator->passes();
            $reservedFrom = Carbon::createFromFormat('m/d/Y', $_POST['reservation_from'])->format('Y-m-d');
            $reservedTill = Carbon::createFromFormat('m/d/Y', $_POST['reservation_till'])->format('Y-m-d');

            $availabilityF = Carbon::createFromFormat('Y-m-d', $reservedFrom);
            $availabilityT = Carbon::createFromFormat('Y-m-d', $reservedTill);
            if ($availabilityF->gte($availabilityT)) {
                $_SESSION['wrong'] = 'Wrong dates provided';
                return new Redirect('/apartments/' . $vars["id"] . '/apartmentsreservations');
            }
            $reservationService = new ShowReservationService();
            $reservations = $reservationService->execute(new ShowReservationRequest($reservedFrom, $reservedTill));

            if (in_array($vars['id'], $reservations)) {
                $_SESSION['wrong'] = 'Wrong dates provided';
                return new Redirect('/apartments/' . $vars["id"] . '/apartmentsreservations');
            }

            $_SESSION['availability_from'] = $reservedFrom;
            $_SESSION['availability_till'] = $reservedTill;

            $service = new AddReservationService();
            $service->execute(new AddReservationRequest(
                $_SESSION['userid'],
                (int)$vars['id'],
                $reservedFrom,
                $reservedTill
            ));

            return new Redirect('/apartments/' . $vars['id'] . '/confirmation');

        } catch (FormValidationException $exception) {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['inputs'] = $_POST;

            return new Redirect('/apartments/' . $vars["id"] . '/apartmentsreservations');
        }
    }
}