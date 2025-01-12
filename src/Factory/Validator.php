<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Factory;

use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\Rules;

final class Validator
{
    /**
     * @param non-empty-string $valueName
     */
    public static function make(mixed $value, string $valueName = 'variable', Exception $exceptionFactory = new UnexpectedValueException()): Rules
    {
        return new Rules($exceptionFactory, $valueName, new Validated($value));
    }
}
