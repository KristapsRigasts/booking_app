<?php

namespace App\Controllers;

use App\Connection;
use App\Exceptions\FormValidationException;
use App\Models\Apartment;
use App\Models\Review;
use App\Redirect;
use App\Validation\Errors;
use App\Validation\FormValidator;
use App\View;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ApartmentsReservationController
{
    public function index(array $vars)
    {
        $apartmentQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments')
            ->where('id = ?')
            ->setParameter(0, (int) $vars['id'])
            ->executeQuery()
            ->fetchAssociative();

        $apartment = new Apartment(
            $apartmentQuery['name'],
            $apartmentQuery['address'],
            $apartmentQuery['description'],
            $apartmentQuery['available_from'],
            $apartmentQuery['available_till'],
            $apartmentQuery['user_id'],
            $apartmentQuery['id']
        );

        $today=Carbon::today()->format('m-d-Y');
        $endingDay = Carbon::createFromFormat('Y-m-d', $apartmentQuery['available_till'])->format('m-d-Y');


        $reservationData =[

        ];

        $apartmentsReservationQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments_reservations')
            ->where('apartment_id = ?')
            ->setParameter(0, (int) $vars['id'])
            ->executeQuery()
            ->fetchAllAssociative();


        foreach ($apartmentsReservationQuery as $apartmentData )
        {
            $reservationData[]=[$apartmentData['reserved_from'],$apartmentData['reserved_till']];

        }

        $dates =[];

        foreach ($reservationData as $record){

            [$startDate, $endDate] = $record;
            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $dates[]= $date->format('m-d-Y');
            }
        }
        return  new View('Reservations/reservation', [
            'apartment' => $apartment,
            'id' => $vars['id'],
            'userName'=> $_SESSION['name'],
            'userId' =>$_SESSION['userid'],
            'reservations' => $dates,
            'startingDate' => $today ,
            'endingDate' => $endingDay,
            'errors' => Errors::getAll(),
            'wrong' => $_SESSION['wrong'] ,
            'inputs' => $_SESSION['inputs'] ?? []
        ]);

    }

    public function show(array $vars)
    {


        try {
            $validator =(new FormValidator($_POST, [
                'reservation_from' => ['required'],
                'reservation_till' => ['required']
            ]));
            $validator->passes();
            $reservedFrom=Carbon::createFromFormat('m/d/Y', $_POST['reservation_from'])->format('Y-m-d');
            $reservedTill=Carbon::createFromFormat('m/d/Y', $_POST['reservation_till'])->format('Y-m-d');

            $availabilityF = Carbon::createFromFormat('Y-m-d', $reservedFrom);
            $availabilityT = Carbon::createFromFormat('Y-m-d', $reservedTill);
            if($availabilityF->gte($availabilityT))
            {
                $_SESSION['wrong'] = 'Wrong dates provided';
                return new Redirect('/apartments/' . $vars["id"] . '/apartmentsreservations');
            }
            $apartmentsReservationQuery = Connection::connection()
                ->createQueryBuilder()
                ->select('*')
                ->from('apartments_reservations')
                ->where('reserved_from BETWEEN :availableFrom and :availableTill')
                ->orWhere('reserved_till BETWEEN :availableFrom and :availableTill')
                ->setParameter('availableFrom', $reservedFrom)
                ->setParameter('availableTill', $reservedTill)
                ->executeQuery()
                ->fetchAllAssociative();

            $reservations =[];

            foreach($apartmentsReservationQuery as $reservations)
            {
                $reservations [] = $reservations['apartment_id'];
            }

            if(in_array($vars['id'],$reservations))
            {
                $_SESSION['wrong'] = 'Wrong dates provided';
                return new Redirect('/apartments/' . $vars["id"] . '/apartmentsreservations');
            }

            $_SESSION['availability_from']=$reservedFrom;
            $_SESSION['availability_till']=$reservedTill;

            Connection::connection()
                ->insert('apartments_reservations', [
                    'user_id' => $_SESSION['userid'],
                    'apartment_id' => (int)$vars['id'],
                    'reserved_from' => $reservedFrom,
                    'reserved_till' => $reservedTill,

                ]);

            return new Redirect('/apartments/' . $vars['id'] . '/confirmation');

        } catch (FormValidationException $exception) {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['inputs'] = $_POST;

            return new Redirect('/apartments/' . $vars["id"] . '/apartmentsreservations');
        }
    }
    
}