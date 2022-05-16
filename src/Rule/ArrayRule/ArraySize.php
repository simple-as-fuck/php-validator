<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Rule\General\Conversion;

/**
 * @extends Conversion<array, int>
 */
final class ArraySize extends Conversion
{
    /**
     * @param array<mixed> $value
     */
    public function convert($value): int
    {
        return count($value);
    }
}
