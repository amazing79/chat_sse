<?php

namespace Ignacio\ChatSsr\Application\User;

use Ignacio\ChatSsr\Domain\User\User;
use Ignacio\ChatSsr\Domain\User\UserRepository;

class RegisterUserCommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {
    }

    public function handle($values): array
    {
        $response = [];
        $response['code'] = 200;
        $response['data'] = [];
        $response['message'] = '';
        try{
            $user = User::createUserFromArray($values);
            $id = $this->userRepository->save($user);
            $response['data'] = $id;
            $response['message'] = 'User created with id ' . $id;
            return $response;
        } catch (\Exception $e) {
            $response['code'] = 500;
            $response['message'] = "An error occurred: {$e->getMessage()}";
            return $response;
        }
    }
}