<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Factory;

final class UnexpectedValueException extends Exception
{
    /**
     * @param non-empty-string $message
     */
    public function create(string $message): \Exception
    {
        return new \UnexpectedValueException($message);
    }
}
