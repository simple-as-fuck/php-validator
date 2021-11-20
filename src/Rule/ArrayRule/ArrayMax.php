<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Rule\General\Max;

/**
 * @template TValue
 * @extends Max<array<TValue>, int>
 */
final class ArrayMax extends Max
{
    /**
     * @return array<TValue>
     */
    public function notNull(): array
    {
        return $this->validateChain() ?? [];
    }
}
