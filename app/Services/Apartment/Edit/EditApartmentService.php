<?php

namespace App\Services\Apartment\Edit;


use App\Models\Apartment;
use App\Repositories\Apartment\ApartmentRepository;
use App\Repositories\Apartment\PDOApartmentRepository;

class EditApartmentService
{
    private ApartmentRepository $apartmentRepository;

    public function __construct()
    {
        $this->apartmentRepository = new PDOApartmentRepository();
    }

    public function execute(EditApartmentRequest $request): Apartment
    {
        return $this->apartmentRepository->editApartment($request->getApartmentId());

    }
}
