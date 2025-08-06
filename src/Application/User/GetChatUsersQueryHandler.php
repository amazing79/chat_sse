<?php

namespace Ignacio\ChatSsr\Application\User;

use Ignacio\ChatSsr\Domain\User\User;
use Ignacio\ChatSsr\Domain\User\UserRepository;

class GetChatUsersQueryHandler
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {

    }
    public function handle(): array
    {
        $response = [];
        $response['code'] = 200;
        $response['data'] = [];
        $response['message'] = 'Users was successfully retrieved';
        try {
            $data = [];
            $users = $this->userRepository->getAll();
            /**
             * @var User $user
             */
            foreach ($users as $user) {
                $data[$user->getEmail()] = $user->toArray();
            }
            $response['data'] = $data;
            return $response;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = 500;
            return $response;
        }
    }
}