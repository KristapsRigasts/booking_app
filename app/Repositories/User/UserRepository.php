<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepository
{
    public function showUser(string $email);
    public function addUser(User $user);
}