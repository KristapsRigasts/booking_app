<?php

namespace App\Services\Apartment\Delete;


use App\Repositories\Apartment\ApartmentRepository;
use App\Repositories\Apartment\PDOApartmentRepository;


class DeleteApartmentService
{
    private ApartmentRepository $apartmentRepository;

    public function __construct()
    {
        $this->apartmentRepository = new PDOApartmentRepository();
    }

    public function execute(DeleteApartmentRequest $request): void
    {
        $this->apartmentRepository->deleteApartment($request->getApartmentId());
    }
}