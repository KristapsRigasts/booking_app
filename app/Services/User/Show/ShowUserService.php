<?php

namespace App\Services\User\Show;

use App\Models\User;
use App\Repositories\User\PDOUserRepository;
use App\Repositories\User\UserRepository;

class ShowUserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new PDOUserRepository();
    }

    public function execute(ShowUserRequest $request): User
    {
       return $this->userRepository->showUser($request->getEmail());
    }
}