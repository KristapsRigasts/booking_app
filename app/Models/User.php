<?php

namespace App\Models;

class User
{
    private string $name;
    private string $surname;
    private ?int $id;
    private ?string $email;
    private ?string $password;

    public function __construct(string $name, string $surname, ?int $id = null, ?string $email= null, ?string $password= null)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}