<?php

namespace App\Services\User\Add;

class AddUserRequest
{
    private string $name;
    private string $surname;
    private string $email;
    private string $password;

    public function __construct(string $name, string $surname, string $email, string $password)
    {
        $this->name = $name;
        $this->surname = $surname;
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

