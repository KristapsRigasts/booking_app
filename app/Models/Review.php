<?php

namespace App\Models;

class Review
{
    private string $name;
    private string $surname;
    private int $userId;
    private string $review;
    private int $apartmentId;
    private string $createdAt;
    private ?int $reviewId;



    public function __construct(string $name, string $surname, int $userId, string $review, int $apartmentId,
                                string $createdAt, ?int $reviewId=null)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->userId = $userId;
        $this->review = $review;
        $this->apartmentId = $apartmentId;
        $this->createdAt = $createdAt;
        $this->reviewId = $reviewId;

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getReview(): string
    {
        return $this->review;
    }

    public function getApartmentId(): int
    {
        return $this->apartmentId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getId(): ?int
    {
        return $this->reviewId;
    }

}