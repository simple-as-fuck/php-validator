<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

/**
 * @template TIn
 * @template TOut
 */
abstract class Conversion
{
    /**
     * @param TIn $value
     * @return TOut
     */
    abstract public function convert($value);
}
