<?php

namespace App\Repositories\Reservation;

use App\Models\Reservation;

interface ReservationRepository
{
    public function showReservations(int $apartmentId);
    public function showReservationDates(string $reservedFrom, string $reservedTill);
    public function addReservation(Reservation $reservation);
    public function getReservation(int $userId);
}