<?php

namespace App\Services\Apartment\Store;

use App\Connection;
use App\Models\Apartment;
use App\Repositories\Apartment\ApartmentRepository;
use App\Repositories\Apartment\PDOApartmentRepository;

class StoreApartmentService
{
    private ApartmentRepository $apartmentRepository;

    public function __construct()
    {
        $this->apartmentRepository = new PDOApartmentRepository();
    }

    public function execute(StoreApartmentRequest $request): void
    {
        $this->apartmentRepository->storeApartment(new Apartment(
            $request->getName(),
            $request->getAddress(),
            $request->getDescription(),
            $request->getAvailableFrom(),
            $request->getAvailableTill(),
            $request->getUserId(),
            null,
            null,
            $request->getRatePerNight()
        ));
    }
}