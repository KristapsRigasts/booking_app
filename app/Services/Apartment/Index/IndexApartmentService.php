<?php

namespace App\Services\Apartment\Index;

use App\Repositories\Apartment\ApartmentRepository;
use App\Repositories\Apartment\PDOApartmentRepository;

class IndexApartmentService
{
    private ApartmentRepository $apartmentRepository;

    public function __construct()
    {
        $this->apartmentRepository = new PDOApartmentRepository();
    }

    public function execute(): array
    {
        return $this->apartmentRepository->indexApartment();
    }
}