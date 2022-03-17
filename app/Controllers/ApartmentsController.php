<?php

namespace App\Controllers;

use App\Connection;

use App\Exceptions\FormValidationException;
use App\Models\Apartment;
use App\Models\Review;
use App\Models\User;
use App\Redirect;
use App\Validation\Errors;
use App\Validation\FormValidator;
use App\View;
use Carbon\Carbon;

class ApartmentsController
{
    public function index(): View
    {
        $apartmentsQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments')
            ->executeQuery()
            ->fetchAllAssociative();

        $apartments=[];

        foreach ($apartmentsQuery as $apartmentData )
        {

            $apartments[] = new Apartment(
                $apartmentData['name'],
                $apartmentData['address'],
                $apartmentData['description'],
                $apartmentData['available_from'],
                $apartmentData['available_till'],
                $apartmentData['user_id'],
                $apartmentData['id'],
                'status',
                $apartmentData['rate_per_night']
            ) ;

        }

        return  new View('Apartments/index', [
            'apartments' => $apartments,
            'userName'=> $_SESSION['name'],
            'userId' =>$_SESSION['userid'],

        ]);
    }

    public function show(array $vars): View
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

        $reviewsQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('reviews')
            ->where('apartment_id = ?')
            ->orderBy('created_at','desc')
            ->setParameter(0, $vars['id'])
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
            $apartmentRateQuery = Connection::connection()
                ->createQueryBuilder()
                ->select('avg(rating)')
                ->from('apartments_ratings')
                ->where('apartments_id = ?')
                ->setParameter(0, (int) $vars['id'])
                ->executeQuery()
                ->fetchOne();


            if($apartmentRateQuery == null)
            {
                $apartmentRateQuery =5;
            }


        return  new View('Apartments/show', [
            'apartment' => $apartment,
            'reviews' => $reviews?? [],
            'apartmentRating' => $apartmentRateQuery,
            'id' => $vars['id'],
            'userName'=> $_SESSION['name'],
            'userId' =>$_SESSION['userid'],
            'errors' => Errors::getAll(),
            'inputs' => $_SESSION['inputs'] ?? []
        ]);
    }

    public function create(): View
    {
        return new View('Apartments/create',[
            'userName'=> $_SESSION['name'],
            'userId' =>$_SESSION['userid'],
            'errors' => Errors::getAll(),
            'inputs' => $_SESSION['inputs'] ?? []
        ]);
    }

    public function store(): Redirect
    {
        try {
            $validator =(new FormValidator($_POST, [
                'name' => ['required'],
                'address' => ['required'],
                'description' => ['required'],
                'available_from' => ['required'],
            'available_till' => ['required'],
                'rate' => ['required']
            ]));
            $validator->passes();

        Connection::connection()
            ->insert('apartments', [
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'description' => $_POST['description'],
                'available_from' => $_POST['available_from'],
                'available_till' => $_POST['available_till'],
                'user_id' => $_SESSION['userid'],
                'rate_per_night' => $_POST['rate']

            ]);
            return new Redirect('/apartments');

        } catch (FormValidationException $exception) {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['inputs'] = $_POST;

            return new Redirect('/apartments/create');
        }


    }

    public function delete(array $vars)
    {
        Connection::connection()
            ->delete('apartments', ['id' => (int)$vars['id']]);

        return new Redirect('/apartments');
    }

    public function edit(array $vars)
    {
                $apartmentQuery = Connection::connection()
                    ->createQueryBuilder()
                    ->select('*')
                    ->from('apartments')
                    ->where('id = ?')
                    ->setParameter(0, (int)$vars['id'])
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

                return new View('Apartments/edit', [
                    'apartment' => $apartment,
                    'userName'=> $_SESSION['name'],
                    'userId' => $_SESSION['userid']
                ]);

    }

    public function update(array $vars):Redirect
    {
        Connection::connection()
            ->update('apartments', [
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'description' => $_POST['description']
            ], ['id' => (int)$vars['id']]);

        return new Redirect('/apartments/' . $vars['id']);
    }

    public function check(array $vars)
    {
        $_SESSION['availability_from']=$_POST['available_from'];
        $_SESSION['availability_till']=$_POST['available_till'];

        return new Redirect('/apartments/availability');
    }

    public function availability(array $vars)
    {
        if($_SESSION['availability_from']== "")
        {
            $availabilityFrom =Carbon::today()->toDateString();
        }
        else{
            $availabilityFrom = $_SESSION['availability_from'];
        }
        $availabilityF= Carbon::createFromFormat('Y-m-d', $availabilityFrom);

        if($_SESSION['availability_till'] == "")
        {
            $availabilityTill= $availabilityF->addDay()->toDateString();

//            $availabilityTill = Carbon::tomorrow()->toDateString();
        }
        else{
            $availabilityTill =  $_SESSION['availability_till'];
        }


//        $availabilityT= Carbon::createFromFormat('Y-m-d', $availabilityTill);

        $today = Carbon::today();
//        $tomorrow = Carbon::tomorrow();

        if($availabilityF->lt($today))
        {
            $availabilityFrom =Carbon::today()->toDateString();
        }

//        if($availabilityT->lt($tomorrow))
//        {
//            $availabilityTill = Carbon::tomorrow()->toDateString();
//        }

        $_SESSION['availability_from']=$availabilityFrom;
        $_SESSION['availability_till']=$availabilityTill;

        $apartmentsReservations=[];

        $apartmentsReservationQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments_reservations')
            ->where('reserved_from BETWEEN :availableFrom and :availableTill')
            ->orWhere('reserved_till BETWEEN :availableFrom and :availableTill')
            ->setParameter('availableFrom', $availabilityFrom)
            ->setParameter('availableTill', $availabilityTill)
            ->executeQuery()
            ->fetchAllAssociative();


        foreach($apartmentsReservationQuery as $apartments)
        {
            $apartmentsReservations[] = $apartments['apartment_id'];
        }

        $apartmentsQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments')
            ->executeQuery()
            ->fetchAllAssociative();

        $apartments=[];

        $reservationAvailabilityF= Carbon::createFromFormat('Y-m-d', $_SESSION['availability_from']);
        $reservationAvailabilityT= Carbon::createFromFormat('Y-m-d', $_SESSION['availability_till']);


        foreach ($apartmentsQuery as $apartmentData )
        {
            $availabilityF = Carbon::createFromFormat('Y-m-d', $apartmentData['available_from']);
            $availabilityT = Carbon::createFromFormat('Y-m-d', $apartmentData['available_till']);

            if(in_array($apartmentData['id'],$apartmentsReservations) || $availabilityF->gt($reservationAvailabilityF) ||
                $availabilityT->lt($reservationAvailabilityT))
            {
                $status = 'taken';
            }

            else {
                $status = 'available';
            }

            $apartments[] = new Apartment(
                $apartmentData['name'],
                $apartmentData['address'],
                $apartmentData['description'],
                $apartmentData['available_from'],
                $apartmentData['available_till'],
                $apartmentData['user_id'],
                $apartmentData['id'],
                $status,
                $apartmentData['rate_per_night']

            );
        }


        return new View ('Apartments/availability', [
            'availabilityFrom' => $availabilityFrom,
            'availabilityTill' => $availabilityTill,
            'apartments' => $apartments,
            'userName'=> $_SESSION['name'],
            'userId' =>$_SESSION['userid']
        ]);
    }

    public function reservation(array $vars)
    {

            Connection::connection()
                ->insert('apartments_reservations', [
                    'user_id' => $_SESSION['userid'],
                    'apartment_id' => (int)$vars['id'],
                    'reserved_from' => $_SESSION['availability_from'],
                    'reserved_till' => $_SESSION['availability_till'],

                ]);

        return new Redirect("/apartments/{$vars['id']}/confirmation");

    }

    public function confirmation(array $vars)
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
            $apartmentQuery['id'],
            'status',
            $apartmentQuery['rate_per_night']
        );

        $reservationStartingDay = $_SESSION['availability_from'];
        $reservationEndingDay = $_SESSION['availability_till'];

        $startingDay=strtotime($reservationStartingDay);
        $endingDay=strtotime($reservationEndingDay);
        $totalAmount=($endingDay-$startingDay)/ 86400 * $apartmentQuery['rate_per_night'];

        unset($_SESSION['availability_from']);
        unset($_SESSION['availability_till']);

        return new View('Reservations/confirmation',[
            'apartment' => $apartment,
            'startingDay' => $reservationStartingDay,
            'endingDay' => $reservationEndingDay,
            'totalAmount' => $totalAmount,
            'userName'=> $_SESSION['name'],
            'userId' =>$_SESSION['userid']
        ]);
    }
}
