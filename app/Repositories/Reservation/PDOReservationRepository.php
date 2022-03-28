<?php

namespace App\Repositories\Reservation;

use App\Connection;
use App\Models\Reservation;

class PDOReservationRepository implements ReservationRepository
{

    public function showReservations(int $apartmentId): array
    {
        $reservationData =[];

        $apartmentsReservationQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments_reservations')
            ->where('apartment_id = ?')
            ->setParameter(0, $apartmentId)
            ->executeQuery()
            ->fetchAllAssociative();


        foreach ($apartmentsReservationQuery as $apartmentData )
        {
            $reservationData[]=[$apartmentData['reserved_from'],$apartmentData['reserved_till']];
        }

        return $reservationData;
    }

    public function showReservationDates(string $reservedFrom, string $reservedTill): array
    {
        $apartmentsReservationQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments_reservations')
            ->where('reserved_from BETWEEN :availableFrom and :availableTill')
            ->orWhere('reserved_till BETWEEN :availableFrom and :availableTill')
            ->setParameter('availableFrom', $reservedFrom)
            ->setParameter('availableTill', $reservedTill)
            ->executeQuery()
            ->fetchAllAssociative();

        $reservations =[];

        foreach($apartmentsReservationQuery as $reservation)
        {
            $reservations [] = $reservation['apartment_id'];
        }

        return $reservations;
    }

    public function addReservation(Reservation $reservation): void
    {
        Connection::connection()
            ->insert('apartments_reservations', [
                'user_id' => $reservation->getUserId(),
                'apartment_id' => $reservation->getApartmentId(),
                'reserved_from' => $reservation->getReservedFrom(),
                'reserved_till' => $reservation->getReservedTill(),
            ]);
    }

    public function getReservation(int $userId): array
    {
        $reservationQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments_reservations')
            ->where('user_id = ?')
            ->setParameter(0, $userId)
            ->executeQuery()
            ->fetchAllAssociative();

        $reservations =[];

        foreach($reservationQuery as $reservation)
        {
            $reservations[] = new Reservation(
                "",
                "",
                $reservation['reserved_from'],
                $reservation['reserved_till'],
                0,
                0,
                $reservation['user_id'],
                $reservation['apartment_id']
            );
        }
        return $reservations;
    }
}