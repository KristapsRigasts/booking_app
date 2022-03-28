<?php

namespace App\Services\Apartment\Reservation;

use App\Models\Apartment;
use App\Repositories\Apartment\ApartmentRepository;
use App\Repositories\Apartment\PDOApartmentRepository;

class ReservationApartmentService
{
    private ApartmentRepository $apartmentRepository;

    public function __construct()
    {
        $this->apartmentRepository = new PDOApartmentRepository();
    }

    public function execute(ReservationApartmentRequest $request): void
    {
        $this->apartmentRepository->reservationApartment(new Apartment(
            "",
            "",
            "",
            $request->getReservedFrom(),
            $request->getReservedTill(),
            $request->getUserId(),
            $request->getApartmentId()
        ));
    }
}
