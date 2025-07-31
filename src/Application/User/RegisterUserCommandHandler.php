<?php

namespace Ignacio\ChatSsr\Application\User;

use Ignacio\ChatSsr\Domain\Common\Utils;
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
            $this->assertValidEmailAddress($values['email']);
            $this->assertValidPassword($values['password'], $values['password_confirm']);
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

    private function assertValidPassword($pass, $passConfirm): void
    {
        if ($pass !== $passConfirm) {
            throw new \InvalidArgumentException("las contraseñas no coinciden");
        }
    }

    private function assertValidEmailAddress(string $email): void
    {
        $tmp = Utils::cleanEmail($email);
        if (!$tmp) {
            throw new \InvalidArgumentException("El mail ingresado no es correcto!");
        }
        $user = $this->userRepository->findByEmail($tmp);
        if ($user) {
            throw new \InvalidArgumentException("El mail ingresado ya existe!");
        }
    }
}