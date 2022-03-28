<?php

namespace App\Repositories\Apartment;

use App\Connection;
use App\Models\Apartment;
use Carbon\Carbon;


class PDOApartmentRepository implements ApartmentRepository
{
    public function deleteApartment(int $apartmentId): void
    {
        Connection::connection()
            ->delete('apartments', [$apartmentId]);
    }

    public function editApartment(int $apartmentId): Apartment
    {
        $apartmentQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments')
            ->where('id = ?')
            ->setParameter(0, $apartmentId)
            ->executeQuery()
            ->fetchAssociative();

        return new Apartment(
            $apartmentQuery['name'],
            $apartmentQuery['address'],
            $apartmentQuery['description'],
            $apartmentQuery['available_from'],
            $apartmentQuery['available_till'],
            $apartmentQuery['user_id'],
            $apartmentQuery['id']
        );
    }

    public function storeApartment(Apartment $apartment): void
    {
        Connection::connection()
            ->insert('apartments', [
                'name' => $apartment->getName(),
                'address' => $apartment->getAddress(),
                'description' => $apartment->getDescription(),
                'available_from' => $apartment->getAvailableFrom(),
                'available_till' => $apartment->getAvailableTill(),
                'user_id' => $apartment->getUserId(),
                'rate_per_night' => $apartment->getRate()

            ]);
    }

    public function indexApartment(): array
    {
        $apartmentsQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments')
            ->executeQuery()
            ->fetchAllAssociative();

        $apartments=[];

        foreach ($apartmentsQuery as $apartmentData )
        {

            $apartments[] = new Apartment(
                $apartmentData['name'],
                $apartmentData['address'],
                $apartmentData['description'],
                $apartmentData['available_from'],
                $apartmentData['available_till'],
                $apartmentData['user_id'],
                $apartmentData['id'],
                'status',
                $apartmentData['rate_per_night']
            ) ;

        }
        return $apartments;
    }

    public function updateApartment(Apartment $apartment): void
    {
        Connection::connection()
            ->update('apartments', [
                'name' => $apartment->getName(),
                'address' => $apartment->getAddress(),
                'description' => $apartment->getDescription()
            ], ['id' => $apartment->getId()]);
    }

    public function availabilityApartment(string $availabilityFrom, string $availabilityTill): array
    {
        $apartmentsReservations=[];

        $apartmentsReservationQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments_reservations')
            ->where('reserved_from BETWEEN :availableFrom and :availableTill')
            ->orWhere('reserved_till BETWEEN :availableFrom and :availableTill')
            ->setParameter('availableFrom', $availabilityFrom)
            ->setParameter('availableTill', $availabilityTill)
            ->executeQuery()
            ->fetchAllAssociative();

        foreach($apartmentsReservationQuery as $apartments)
        {
            $apartmentsReservations[] = $apartments['apartment_id'];
        }

        $apartmentsQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments')
            ->executeQuery()
            ->fetchAllAssociative();

        $apartments=[];

        $reservationAvailabilityF= Carbon::createFromFormat('Y-m-d', $availabilityFrom);
        $reservationAvailabilityT= Carbon::createFromFormat('Y-m-d', $availabilityTill);


        foreach ($apartmentsQuery as $apartmentData )
        {
            $availabilityF = Carbon::createFromFormat('Y-m-d', $apartmentData['available_from']);
            $availabilityT = Carbon::createFromFormat('Y-m-d', $apartmentData['available_till']);

            if(in_array($apartmentData['id'],$apartmentsReservations) || $availabilityF->gt($reservationAvailabilityF) ||
                $availabilityT->lt($reservationAvailabilityT))
            {
                $status = 'taken';
            }

            else {
                $status = 'available';
            }

            $apartments[] = new Apartment(
                $apartmentData['name'],
                $apartmentData['address'],
                $apartmentData['description'],
                $apartmentData['available_from'],
                $apartmentData['available_till'],
                $apartmentData['user_id'],
                $apartmentData['id'],
                $status,
                $apartmentData['rate_per_night']

            );
        }
        return $apartments;
    }

    public function reservationApartment(Apartment $apartment): void
    {
        Connection::connection()
            ->insert('apartments_reservations', [
                'user_id' => $apartment->getUserId(),
                'apartment_id' => $apartment->getId(),
                'reserved_from' => $apartment->getAvailableFrom(),
                'reserved_till' => $apartment->getAvailableTill(),

            ]);
    }

    public function confirmationApartment(int $apartmentId): Apartment
    {
        $apartmentQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments')
            ->where('id = ?')
            ->setParameter(0, $apartmentId)
            ->executeQuery()
            ->fetchAssociative();

        return new Apartment(
            $apartmentQuery['name'],
            $apartmentQuery['address'],
            $apartmentQuery['description'],
            $apartmentQuery['available_from'],
            $apartmentQuery['available_till'],
            $apartmentQuery['user_id'],
            $apartmentQuery['id'],
            'status',
            $apartmentQuery['rate_per_night']
        );
    }

    public function showApartment(int $apartmentId): Apartment
    {
        $apartmentQuery = Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('apartments')
            ->where('id = ?')
            ->setParameter(0, $apartmentId)
            ->executeQuery()
            ->fetchAssociative();


        return new Apartment(
            $apartmentQuery['name'],
            $apartmentQuery['address'],
            $apartmentQuery['description'],
            $apartmentQuery['available_from'],
            $apartmentQuery['available_till'],
            $apartmentQuery['user_id'],
            $apartmentQuery['id'],
            null,
            $apartmentQuery['rate_per_night']
        );

    }
}