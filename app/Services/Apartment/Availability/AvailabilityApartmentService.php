<?php

namespace App\Services\Apartment\Availability;

use App\Repositories\Apartment\ApartmentRepository;
use App\Repositories\Apartment\PDOApartmentRepository;

class AvailabilityApartmentService
{
    private ApartmentRepository $apartmentRepository;

    public function __construct()
    {
        $this->apartmentRepository = new PDOApartmentRepository();
    }

    public function execute(AvailabilityApartmentRequest $request): array
    {
        return $this->apartmentRepository->availabilityApartment($request->getAvailabilityFrom(),
            $request->getAvailabilityTill());
    }
}