<?php

declare(strict_types=1);

use App\Models\Reservation;
use PHPUnit\Framework\TestCase;

class ReservationTest extends TestCase
{
    public function testName()
    {
        $reservation = new Reservation(
            'Skanste',
            'Skanstes street 20',
            '25-03-2022',
            '27-03-2022',
            100,
            200,
            1,
            2
        );

        $this->assertSame('Skanste', $reservation->getName());
    }

    public function testAddress()
    {
        $reservation = new Reservation(
            'Skanste',
            'Skanstes street 20',
            '25-03-2022',
            '27-03-2022',
            100,
            200,
            1,
            2
        );

        $this->assertSame('Skanstes street 20', $reservation->getAddress());
    }

    public function testReservedFrom()
    {
        $reservation = new Reservation(
            'Skanste',
            'Skanstes street 20',
            '25-03-2022',
            '27-03-2022',
            100,
            200,
            1,
            2
        );

        $this->assertSame('25-03-2022', $reservation->getReservedFrom());
    }

    public function testReservedTill()
    {
        $reservation = new Reservation(
            'Skanste',
            'Skanstes street 20',
            '25-03-2022',
            '27-03-2022',
            100,
            200,
            1,
            2
        );

        $this->assertSame('27-03-2022', $reservation->getReservedTill());
    }

    public function testRatePerNight()
    {
        $reservation = new Reservation(
            'Skanste',
            'Skanstes street 20',
            '25-03-2022',
            '27-03-2022',
            100,
            200,
            1,
            2
        );

        $this->assertSame(100, $reservation->getRatePerNight());
    }

    public function testTotalAmountPerStay()
    {
        $reservation = new Reservation(
            'Skanste',
            'Skanstes street 20',
            '25-03-2022',
            '27-03-2022',
            100,
            200,
            1,
            2
        );

        $this->assertSame(200, $reservation->getTotalAmount());
    }

    public function testUserId()
    {
        $reservation = new Reservation(
            'Skanste',
            'Skanstes street 20',
            '25-03-2022',
            '27-03-2022',
            100,
            200,
            1,
            2
        );

        $this->assertSame(1, $reservation->getUserId());
    }

    public function testApartmentId()
    {
        $reservation = new Reservation(
            'Skanste',
            'Skanstes street 20',
            '25-03-2022',
            '27-03-2022',
            100,
            200,
            1,
            2
        );

        $this->assertSame(2, $reservation->getApartmentId());
    }
}