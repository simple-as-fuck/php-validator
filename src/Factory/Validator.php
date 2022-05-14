<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Factory;

use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\Rules;

final class Validator
{
    /**
     * @param mixed|null $value
     * @param non-empty-string $valueName
     */
    final public static function make($value, string $valueName = 'variable'): Rules
    {
        $value = new Validated($value);
        return new Rules(new UnexpectedValueException(), $valueName, $value);
    }
}
