<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Rule\General\IntRule;

/**
 * @extends IntRule<array{port?: int<0, 65535>}>
 */
final class Port extends IntRule
{
    /**
     * @param array{port?: int<0, 65535>} $value
     */
    protected function validate($value): ?int
    {
        return $value['port'] ?? null;
    }
}
