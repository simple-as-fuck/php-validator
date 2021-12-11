<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\DateTime;

use SimpleAsFuck\Validator\Rule\General\ToString;

/**
 * @extends ToString<\DateTimeInterface>
 */
final class ToIsoString extends ToString
{
    /**
     * @param \DateTimeInterface $value
     */
    public function convert($value): string
    {
        return $value->format(\DateTimeInterface::ISO8601);
    }
}
