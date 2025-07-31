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
        $response['code'] = 200;
        $response['data'] = [];
        $response['message'] = 'Password reset successfully';
        try {
            $this->assertValidPassword($credentials['password'], $credentials['password_confirm']);
            $this->userRepository->resetPassword($credentials);
            return $response;
        } catch (\Exception $e) {
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    private function assertValidPassword($pass, $passConfirm): void
    {
        if ($pass !== $passConfirm) {
            throw new \InvalidArgumentException("las contraseñas no coinciden");
        }
    }
}