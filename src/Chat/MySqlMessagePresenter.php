<?php

namespace Ignacio\ChatSsr\Chat;

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