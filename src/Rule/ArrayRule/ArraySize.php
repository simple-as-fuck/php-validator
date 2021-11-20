<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Rule\General\Compared;

/**
 * @extends Compared<array, int>
 */
final class ArraySize extends Compared
{
    /**
     * @param array<mixed> $comparedValue
     */
    public function convert($comparedValue): int
    {
        return count($comparedValue);
    }
}
