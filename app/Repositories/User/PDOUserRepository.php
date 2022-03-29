<?php

namespace App\Repositories\User;

use App\Connection;
use App\Models\User;
use App\Redirect;

class PDOUserRepository implements UserRepository
{
    public function getUser(string $email): int
    {
         $userQuery= Connection::connection()
            ->createQueryBuilder()
            ->select('id')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->executeQuery()
            ->fetchAssociative();

         if($userQuery == false)
         {
             return 0;
         }
         else
         {
             return $userQuery['id'];
         }
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

    public function showUser(string $email): User
    {
        $userQuery= Connection::connection()
            ->createQueryBuilder()
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->executeQuery()
            ->fetchAssociative();

        return new User(
            $userQuery['name'],
            $userQuery['surname'],
            $userQuery['id'],
            $userQuery['email'],
            $userQuery['password']
        );

    }
}