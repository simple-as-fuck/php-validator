<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Rule\General\Conversion;

/**
 * @extends Conversion<array<mixed>, int<0, max>>
 */
final class ArraySize extends Conversion
{
    /**
     * @param array<mixed> $value
     * @return int<0, max>
     */
    public function convert($value): int
    {
        return count($value);
    }
}
