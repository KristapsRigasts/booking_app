<?php

namespace App\Services\Apartment\Show;

use App\Models\Apartment;
use App\Repositories\Apartment\ApartmentRepository;
use App\Repositories\Apartment\PDOApartmentRepository;

class ShowApartmentService
{
    private ApartmentRepository $apartmentRepository;

    public function __construct()
    {
        $this->apartmentRepository = new PDOApartmentRepository();
    }

    public function execute(ShowApartmentRequest $request): Apartment
    {
        return $this->apartmentRepository->showApartment($request->getApartmentId());
    }
}