<?php

namespace Ignacio\ChatSsr\Application\User;

use Ignacio\ChatSsr\Domain\Common\Utils;
use Ignacio\ChatSsr\Domain\User\UserRepository;

class RegisterResetRequestCommandHandler
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function handle(string $email): array
    {
        $response = [];
        $response['code'] = 200;
        $response['data'] = [];
        $response['message'] = 'Successful password change registration';
        try {
            $this->assertValidEmail($email);
            $token = $this->userRepository->requestChangePassword($email);
            $response['data'] = $token;
            return $response;
        } catch (\Exception $e) {
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
            return $response;
        }
    }

    private function assertValidEmail(string $email): void
    {
        $tmp = Utils::cleanEmail($email);
        if (!$tmp) {
            throw new \InvalidArgumentException("El mail ingresado no es correcto!");
        }
        $user = $this->userRepository->findByEmail($tmp);
        if (!$user) {
            throw new \InvalidArgumentException("El mail no pertenece a ningun usuario activo!");
        }
    }
}