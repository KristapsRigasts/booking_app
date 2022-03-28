<?php

namespace App\Repositories\User;

use App\Connection;
use App\Models\User;
use App\Redirect;

class PDOUserRepository implements UserRepository
{
    public function showUser(string $email): array
    {
         return Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->executeQuery()
            ->fetchAssociative();

    }

    public function addUser(User $user): void
    {
        Connection::connection()
            ->insert('users', [
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword()
            ]);
    }
}