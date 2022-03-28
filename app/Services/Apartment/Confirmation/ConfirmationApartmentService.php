<?php

namespace App\Services\Apartment\Confirmation;

use App\Models\Apartment;
use App\Repositories\Apartment\ApartmentRepository;
use App\Repositories\Apartment\PDOApartmentRepository;

class ConfirmationApartmentService
{
    private ApartmentRepository $apartmentRepository;

    public function __construct()
    {
        $this->apartmentRepository = new PDOApartmentRepository();
    }

    public function execute(ConfirmationApartmentRequest $request): Apartment
    {
        return $this->apartmentRepository->confirmationApartment($request->getApartmentId());
    }
}