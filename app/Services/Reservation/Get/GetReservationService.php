<?php

namespace App\Services\Reservation\Get;

use App\Repositories\Reservation\PDOReservationRepository;
use App\Repositories\Reservation\ReservationRepository;

class GetReservationService
{
    private ReservationRepository $reservationRepository;

    public function __construct()
    {
        $this->reservationRepository = new PDOReservationRepository();
    }

    public function execute(GetReservationRequest $request):array
    {

        return $this->reservationRepository->getReservation($request->getUserId());
    }
}