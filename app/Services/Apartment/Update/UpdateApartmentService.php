<?php

namespace App\Services\Apartment\Update;

use App\Models\Apartment;
use App\Repositories\Apartment\ApartmentRepository;
use App\Repositories\Apartment\PDOApartmentRepository;

class UpdateApartmentService
{
    private ApartmentRepository $apartmentRepository;

    public function __construct()
    {
        $this->apartmentRepository = new PDOApartmentRepository();
    }

    public function execute(UpdateApartmentRequest $request)
    {
        $this->apartmentRepository->updateApartment(new Apartment(
            $request->getName(),
            $request->getAddress(),
            $request->getDescription(),
            '28-03-2022',
            '28-03-2022',
            1,
            $request->getApartmentId()
        ));

    }
}