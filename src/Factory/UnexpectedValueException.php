<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Factory;

final class UnexpectedValueException extends Exception
{
    public function create(string $message): \Exception
    {
        return new \UnexpectedValueException($message);
    }
}
