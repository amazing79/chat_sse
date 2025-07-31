<?php

namespace Ignacio\ChatSsr\Application\User;

use Ignacio\ChatSsr\Domain\User\UserRepository;

class GetUserByIdQueryHandler
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function handle(int $userId): array
    {
        $response = [];
        $response['code'] = 200;
        $response['data'] = [];
        $response['message'] = 'User was successfully logged in';
        try{
            $user = $this->userRepository->findById($userId);
            if ($user === null) {
                throw new \Exception('User not found');
            }
            $response['data'] = $user->toArray();
        } catch (\Exception $e){
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
        } finally {
            return $response;
        }
    }
}