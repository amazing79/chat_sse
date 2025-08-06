<?php

namespace Ignacio\ChatSsr\Infraestructure\Chat;

use Ignacio\ChatSsr\Domain\Chat\ChatRepository;
use Ignacio\ChatSsr\Domain\Chat\Message;
use Ignacio\ChatSsr\Domain\User\User;
use Ignacio\ChatSsr\Infraestructure\User\UserMysqlRepository;
use Predis\Client;
class RedisRepository implements ChatRepository
{
    private Client $client;
    private mixed $chat_key;
    private string $active_users;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client([
            'scheme' => $_ENV['REDIS_CHAT_SCHEME'] ?? 'tcp',
            'host' => $_ENV['REDIS_CHAT_HOST'] ?? '127.0.0.1',
            'port' => $_ENV['REDIS_CHAT_PORT'] ?? 6379,
            'database' => $_ENV['REDIS_CHAT_DB'] ?? 0,
        ]);
        $this->chat_key = $_ENV['REDIS_CHAT_KEY'] ?? 'chat_token';
        $this->active_users = $_ENV['REDIS_CHAT_USERS'] ?? 'active_users';
    }

    public function getAllMessages(): array
    {
        return $this->client->lrange($this->chat_key, 0, -1);
    }

    public function getNewMessages($lastCount): array
    {
        return $this->client->lRange($this->chat_key, $lastCount, $this->getTotalMessages());
    }

    public function getUserMessages($user)
    {
        $method = __METHOD__;
        throw new \Exception("Method {$method} not implemented");
    }

    public function getTotalMessages(): int
    {
        return $this->client->llen($this->chat_key);
    }

    public function saveMessage(Message $message): void
    {
        $msg[] = "{$message->getUser()} says: {$message->getMessage()}";
        $this->client->rpush($this->chat_key, $msg);
    }

    public function getActiveUsers(): array
    {
        $active = [];
        $tmp = explode(',', $this->client->get($this->active_users));
        if(count($tmp) > 0) {
            $userRepository = new UserMysqlRepository();
            foreach ($tmp as $mail) {
                $user = $userRepository->findByEmail($mail);
                if($user) {
                    $active[$user->getEmail()] = $user;
                }
            }
        }

       return $active;
    }

    public function addActiveUser(User $user): void
    {
        $users = $this->getActiveUsersAsArray($this->client->get($this->active_users));
        $users[$user->getEmail()] = $user->getEmail();
        $activeUsers = implode(',', $users);
        $this->client->set($this->active_users, $activeUsers);
    }

    public function removeActiveUser(User $user): void
    {
        $users = $this->getActiveUsersAsArray($this->client->get($this->active_users));
        unset($users[$user->getEmail()]);
        $activeUsers = implode(',', $users);
        $this->client->set($this->active_users, $activeUsers);
    }

    private function getActiveUsersAsArray(?string $activeUsers): array
    {
        if(!empty($activeUsers)) {
            $temp = explode(',', $activeUsers);
            $users = [];
            foreach ($temp as $user) {
                $users[$user] = $user;
            }
            return $users;
        }
        return [];
    }

    public function getTotalActiveUsers(): mixed
    {
        return md5($this->client->get($this->active_users));
    }
}