<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Factory;

abstract class Exception
{
    /**
     * @param non-empty-string $message
     */
    abstract public function create(string $message): \Exception;
}
