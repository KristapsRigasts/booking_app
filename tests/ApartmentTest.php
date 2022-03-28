<?php

declare(strict_types=1);

use App\Models\Apartment;
use PHPUnit\Framework\TestCase;

class ApartmentTest extends TestCase
{
    public function testName()
    {
        $apartment = new Apartment(
            'Skanste',
            'Skanstes street 12',
            'great place',
            '20-03-2022',
            '25-03-2022',
            1,
            2,
            'taken',
            100
        );

        $this->assertSame('Skanste', $apartment->getName());
    }

    public function testAddress()
    {
        $apartment = new Apartment(
            'Skanste',
            'Skanstes street 12',
            'great place',
            '20-03-2022',
            '25-03-2022',
            1,
            2,
            'taken',
            100
        );

        $this->assertSame('Skanstes street 12',$apartment->getAddress());

    }

    public function testDescription()
    {
        $apartment = new Apartment(
            'Skanste',
            'Skanstes street 12',
            'great place',
            '20-03-2022',
            '25-03-2022',
            1,
            2,
            'taken',
            100
        );

        $this->assertSame('great place',$apartment->getDescription());
    }

    public function testAvailableFrom()
    {
        $apartment = new Apartment(
            'Skanste',
            'Skanstes street 12',
            'great place',
            '20-03-2022',
            '25-03-2022',
            1,
            2,
            'taken',
            100
        );

        $this->assertSame('20-03-2022',$apartment->getAvailableFrom());
    }

    public function testAvailableTill()
    {
        $apartment = new Apartment(
            'Skanste',
            'Skanstes street 12',
            'great place',
            '20-03-2022',
            '25-03-2022',
            1,
            2,
            'taken',
            100,
        );

        $this->assertSame('25-03-2022',$apartment->getAvailableTill());
    }
    public function testUserId()
    {
        $apartment = new Apartment(
            'Skanste',
            'Skanstes street 12',
            'great place',
            '20-03-2022',
            '25-03-2022',
            1,
            2,
            'taken',
            100,
        );

        $this->assertSame(1,$apartment->getUserId());
    }

    public function testApartmentId()
    {
        $apartment = new Apartment(
            'Skanste',
            'Skanstes street 12',
            'great place',
            '20-03-2022',
            '25-03-2022',
            1,
            2,
            'taken',
            100,
        );

        $this->assertSame(2,$apartment->getId());
    }
    public function testApartmentStatus()
    {
        $apartment = new Apartment(
            'Skanste',
            'Skanstes street 12',
            'great place',
            '20-03-2022',
            '25-03-2022',
            1,
            2,
            'taken',
            100,
        );

        $this->assertSame('taken',$apartment->getStatus());
    }
    public function testApartmentRate()
    {
        $apartment = new Apartment(
            'Skanste',
            'Skanstes street 12',
            'great place',
            '20-03-2022',
            '25-03-2022',
            1,
            2,
            'taken',
            100,
        );

        $this->assertSame(100,$apartment->getRate());
    }
}