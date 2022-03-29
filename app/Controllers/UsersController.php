<?php

namespace App\Controllers;

use App\Exceptions\FormValidationException;
use App\Models\Reservation;
use App\Redirect;
use App\Services\Apartment\Show\ShowApartmentRequest;
use App\Services\Apartment\Show\ShowApartmentService;
use App\Services\Reservation\Get\GetReservationRequest;
use App\Services\Reservation\Get\GetReservationService;
use App\Services\User\Add\AddUserRequest;
use App\Services\User\Add\AddUserService;
use App\Services\User\Get\GetUserRequest;
use App\Services\User\Get\GetUserService;
use App\Services\User\Show\ShowUserRequest;
use App\Services\User\Show\ShowUserService;
use App\Validation\Errors;
use App\Validation\FormValidator;
use App\View;

class UsersController

{
    public function home(): View
    {
        return new View('Home/home', ['userName' => $_SESSION['name'] ?? [],
            'userId' => $_SESSION['userid'] ?? []]);
    }

    public function index(): View
    {
        return new View('Users/index');
    }

    public function show(array $vars): View
    {
        return new View('Users/show', [
            'id' => $vars['id']
        ]);
    }

    public function register(): View
    {
        return new View('Users/register', [
            'inputs' => $_SESSION['inputs'] ?? [],
            'errors' => Errors::getAll()]);
    }

    public function store(): Redirect
    {
        try {
            $validator = (new FormValidator($_POST, [
                'name' => ['required'],
                'surname' => ['required'],
                'email' => ['required'],
                'password' => ['required'],
            ]));
            $validator->passes();

            $userService = new ShowUserService();
            $userQueryCheck = $userService->execute(new ShowUserRequest($_POST['email']));

            if ($userQueryCheck != false) {
                return new Redirect('/users/register?error=emailalreadytaken');
            }

            $passwordHashed = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $service = new AddUserService();
            $service->execute(new AddUserRequest(
                $_POST['name'],
                $_POST['surname'],
                $_POST['email'],
                $passwordHashed
            ));

            return new Redirect('/');

        } catch (FormValidationException $exception) {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['inputs'] = $_POST;

            return new Redirect('/users/register');
        }
    }

    public function logIn(): View
    {
        return new View('Users/login', [
            'errors' => Errors::getAll(),
            'inputs' => $_SESSION['inputs'] ?? []
        ]);
    }

    public function validateLogIn(): Redirect
    {
        try {
            $validator = (new FormValidator($_POST, [
                'email' => ['required'],
                'password' => ['required']
            ]));
            $validator->passes();

            $service = new GetUserService();
            $userData = $service->execute(new GetUserRequest($_POST['email']));

            if ($userData === 0) {
                return new Redirect('/users/login?error=usernotfound');
            } else {

                $userService = new ShowUserService();
                $userQuery = $userService->execute(new ShowUserRequest($_POST['email']));

                $checkPwd = password_verify($_POST['password'], $userQuery->getPassword());

                if ($checkPwd == false) {
                    return new Redirect('/users/login?error=wrongpassword');
                } else {
                    $_SESSION["userid"] = $userQuery->getId();
                    $_SESSION["name"] = $userQuery->getName();
                    $_SESSION["surname"] = $userQuery->getSurname();

                    return new Redirect('/');
                }
            }
        } catch (FormValidationException $exception) {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['inputs'] = $_POST;

            return new Redirect('/users/login');
        }
    }

    public function logOut(): Redirect
    {
        unset ($_SESSION["userid"]);
        unset ($_SESSION["name"]);
        unset ($_SESSION["surname"]);
        return new Redirect('/');
    }

    public function showReservations(): View
    {
        $reservationService = new GetReservationService();
        $apartmentsReservationQuery = $reservationService->execute(new GetReservationRequest($_SESSION["userid"]));

        $userReservations = [];

        foreach ($apartmentsReservationQuery as $reservations) {
            $showService = new ShowApartmentService();
            $apartmentQuery = $showService->execute(new ShowApartmentRequest((int)$reservations->getApartmentId()));

            $startingDay = strtotime($reservations->getReservedFrom());
            $endingDay = strtotime($reservations->getReservedTill());
            $totalAmount = ($endingDay - $startingDay) / 86400 * $apartmentQuery->getRate();

            $userReservations[] = new Reservation(
                $apartmentQuery->getName(),
                $apartmentQuery->getAddress(),
                $reservations->getReservedFrom(),
                $reservations->getReservedTill(),
                $apartmentQuery->getRate(),
                $totalAmount
            );
        }
        return new View('Users/reservations', [
            'userName' => $_SESSION['name'],
            'userId' => $_SESSION['userid'],
            'reservations' => $userReservations ?? []
        ]);
    }
}