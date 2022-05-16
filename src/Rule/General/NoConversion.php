<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

/**
 * @template TValue
 * @extends Conversion<TValue, TValue>
 */
final class NoConversion extends Conversion
{
    /**
     * @param TValue $value
     * @return TValue
     */
    public function convert($value)
    {
        return $value;
    }
}
