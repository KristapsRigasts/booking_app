<?php

namespace App\Models;

class Review
{
    private string $name;
    private string $surname;
    private int $userId;
    private string $review;
    private int $apartment_id;
    private string $created_at;
    private ?int $id;



    public function __construct(string $name, string $surname, int $userId, string $review, int $apartment_id, string $created_at, ?int $id=null)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->userId = $userId;
        $this->review = $review;
        $this->apartment_id = $apartment_id;
        $this->created_at = $created_at;
        $this->id = $id;

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
        return $this->apartment_id;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

}