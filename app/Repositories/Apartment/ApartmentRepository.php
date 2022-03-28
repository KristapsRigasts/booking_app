<?php

namespace App\Repositories\Apartment;


use App\Models\Apartment;

interface ApartmentRepository
{
    public function deleteApartment(int $apartmentId);
    public function editApartment(int $apartmentId);
    public function storeApartment(Apartment $apartment);
    public function indexApartment();
    public function updateApartment(Apartment $apartment);
    public function availabilityApartment(string $availabilityFrom,string $availabilityTill);
    public function reservationApartment(Apartment $apartment);
    public function confirmationApartment(int $apartmentId);
    public function showApartment(int $apartmentId);

}