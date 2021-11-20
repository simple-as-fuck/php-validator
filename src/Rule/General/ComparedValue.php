<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

/**
 * @template TValue
 * @extends Compared<TValue, TValue>
 */
final class ComparedValue extends Compared
{
    /**
     * @param TValue $comparedValue
     * @return TValue
     */
    public function convert($comparedValue)
    {
        return $comparedValue;
    }
}
