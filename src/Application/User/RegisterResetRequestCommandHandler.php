<?php

namespace Ignacio\ChatSsr\Application\User;

use Ignacio\ChatSsr\Domain\User\UserRepository;

class RegisterResetRequestCommandHandler
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function handle($request): array
    {
        $response = [];
        $response['code'] = 200;
        $response['data'] = [];
        $response['message'] = 'Successful password change registration';
        try {
            $this->userRepository->changePasswordRequest($request);
            return $response;
        } catch (\Exception $e) {
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }
}