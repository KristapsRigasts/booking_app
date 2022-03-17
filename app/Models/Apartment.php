<?php

namespace App\Models;

class Apartment
{
    private string $name;
    private string $address;
    private string $description;
    private string $available_from;
    private string $available_till;
    private ?int $user_id;
    private ?int $id;
    private ?string $status;
    private ?int $rate;


    public function __construct(string $name, string $address, string $description, string $available_from,
                                string $available_till, ?int $user_id = null, ?int $id = null, ?string $status = null,
                                ?int $rate = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->description = $description;
        $this->available_from = $available_from;
        $this->available_till = $available_till;
        $this->user_id = $user_id;
        $this->id = $id;

        $this->status = $status;
        $this->rate = $rate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAvailableFrom(): string
    {
        return $this->available_from;
    }

    public function getAvailableTill(): string
    {
        return $this->available_till;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }
}