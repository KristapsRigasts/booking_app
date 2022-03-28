<?php

namespace App\Services\Reservation\Index;

use App\Repositories\Reservation\PDOReservationRepository;
use App\Repositories\Reservation\ReservationRepository;

class IndexReservationService
{
    private ReservationRepository $reservationRepository;

    public function __construct()
    {
        $this->reservationRepository = new PDOReservationRepository();
    }

    public function execute(IndexReservationRequest $request): array
    {
        return $this->reservationRepository->showReservations($request->getApartmentId());
    }
}