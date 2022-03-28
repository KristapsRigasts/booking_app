<?php

namespace App\Services\Reservation\Add;

use App\Models\Reservation;
use App\Repositories\Reservation\PDOReservationRepository;
use App\Repositories\Reservation\ReservationRepository;

class AddReservationService
{
    private ReservationRepository $reservationRepository;

    public function __construct()
    {
        $this->reservationRepository = new PDOReservationRepository();
    }

    public function execute(AddReservationRequest $request): void
    {
        $this->reservationRepository->addReservation(new Reservation(
            "",
            "",
            $request->getReservedFrom(),
            $request->getReservedTill(),
            0,
            0,
            $request->getUserId(),
            $request->getApartmentId()
        ));
    }
}