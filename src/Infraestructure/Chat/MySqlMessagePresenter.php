<?php

namespace Ignacio\ChatSsr\Infraestructure\Chat;

use Ignacio\ChatSsr\Domain\Chat\Message;
use Ignacio\ChatSsr\Domain\Chat\Presenter;

class MySqlMessagePresenter implements Presenter
{
    public function render($object): string
    {
        /**
         * @var Message $object
         */
        return "{$object->getDate()} - {$object->getUser()} says: {$object->getMessage()}";
    }
}