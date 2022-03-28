<?php

namespace App\Services\User\Add;

use App\Models\User;
use App\Repositories\User\PDOUserRepository;
use App\Repositories\User\UserRepository;

class AddUserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new PDOUserRepository();
    }

    public function execute(AddUserRequest $request): void
    {
        $this->userRepository->addUser(new User(
            $request->getName(),
            $request->getSurname(),
            1,
            $request->getEmail(),
            $request->getPassword()
        ));
    }
}