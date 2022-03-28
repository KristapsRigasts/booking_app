<?php

namespace App\Services\Reservation\Show;

use App\Repositories\Reservation\PDOReservationRepository;
use App\Repositories\Reservation\ReservationRepository;

class ShowReservationService
{
    private ReservationRepository $reservationRepository;

    public function __construct()
    {
        $this->reservationRepository = new PDOReservationRepository();
    }

    public function execute(ShowReservationRequest $request): array
    {
        return $this->reservationRepository->showReservationDates(
            $request->getReservedFrom(),
            $request->getReservedTill());
    }
}