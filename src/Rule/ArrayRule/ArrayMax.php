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
     * @deprecated use nullable() ?? []
     * @return array<TValue>
     */
    public function notNull(bool $failAsEmpty = false): array
    {
        return $this->validateChain($failAsEmpty) ?? [];
    }
}
