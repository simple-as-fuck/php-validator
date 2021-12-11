<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

/**
 * @extends ToString<int|float|string|\Stringable>
 */
final class CastString extends ToString
{
    /**
     * @param int|float|string|\Stringable $value
     */
    public function convert($value): string
    {
        return (string) $value;
    }
}
