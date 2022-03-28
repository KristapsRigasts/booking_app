<?php

declare(strict_types=1);


use App\Models\Review;
use PHPUnit\Framework\TestCase;

class ReviewTest extends TestCase
{
    public function testName()
    {
        $review = new Review(
            'Kristaps',
            'Rigasts',
            1,
            'great place',
            2,
            '22-03-2022',
            2
        );

        $this->assertSame('Kristaps', $review->getName());
    }

    public function testSurname()
    {
        $review = new Review(
            'Kristaps',
            'Rigasts',
            1,
            'great place',
            2,
            '22-03-2022',
            2
        );

        $this->assertSame('Rigasts',$review->getSurname());
    }

    public function testUserId()
    {
        $review = new Review(
            'Kristaps',
            'Rigasts',
            1,
            'great place',
            2,
            '22-03-2022',
            2
        );

        $this->assertSame(1,$review->getUserId());
    }

    public function testReview()
    {
        $review = new Review(
            'Kristaps',
            'Rigasts',
            1,
            'great place',
            2,
            '22-03-2022',
            2
        );

        $this->assertSame('great place',$review->getReview());
    }

    public function testApartmentId()
    {
        $review = new Review(
            'Kristaps',
            'Rigasts',
            1,
            'great place',
            2,
            '22-03-2022',
            2
        );

        $this->assertSame(2,$review->getApartmentId());
    }

    public function testCreatedAt()
    {
        $review = new Review(
            'Kristaps',
            'Rigasts',
            1,
            'great place',
            2,
            '22-03-2022',
            2
        );

        $this->assertSame('22-03-2022',$review->getCreatedAt());
    }

    public function testReviewId()
    {
        $review = new Review(
            'Kristaps',
            'Rigasts',
            1,
            'great place',
            2,
            '22-03-2022',
            2
        );

        $this->assertSame(2,$review->getId());
    }




}