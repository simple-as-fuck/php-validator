<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

/**
 * @extends Conversion<int|float|string|\Stringable, string>
 */
final class CastString extends Conversion
{
    /**
     * @param int|float|string|\Stringable $value
     */
    public function convert($value): string
    {
        return (string) $value;
    }
}
