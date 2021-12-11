<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

/**
 * @template TValue
 */
abstract class ToString
{
    /**
     * @param TValue $value
     */
    abstract public function convert($value): string;
}
