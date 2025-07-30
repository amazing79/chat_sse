<?php

namespace Ignacio\ChatSsr\Application\User;

use Ignacio\ChatSsr\Domain\User\UserRepository;

class ResetPasswordCommandHandler
{
    public function __construct(private UserRepository $userRepository)
    {

    }

    public function handle($credentials): array
    {
        $response = [];
        $response['status'] = 200;
        $response['data'] = [];
        $response['message'] = 'Passowrd reset successfully';
        try {
            $this->userRepository->resetPassword($credentials);
            return $response;
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }
}