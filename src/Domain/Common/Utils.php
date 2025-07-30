<?php

namespace Ignacio\ChatSsr\Domain\Common;

class Utils
{
    public static function cleanEmail($email)
    {
        return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
    }
}