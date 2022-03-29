<?php

namespace App\Services\User\Get;

use App\Repositories\User\PDOUserRepository;
use App\Repositories\User\UserRepository;

class GetUserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new PDOUserRepository();
    }

    public function execute(GetUserRequest $request): int
    {
        return $this->userRepository->getUser($request->getEmail());
    }
}