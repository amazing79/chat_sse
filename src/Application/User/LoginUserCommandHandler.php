<?php

namespace Ignacio\ChatSsr\Application\User;

use Ignacio\ChatSsr\Domain\User\UserRepository;

class LoginUserCommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {
    }

    public function handle(array $credentials): array
    {
        $response = [];
        $response['code'] = 200;
        $response['data'] = [];
        $response['message'] = 'User was successfully logged in';
        try {
            $user = $this->userRepository->findUserByCredentials($credentials);
            if (!$user) {
                throw new \Exception('User not found');
            }
            $response['data'] = $user->getUserId();
            return $response;
        } catch (\Exception $e) {
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }
}