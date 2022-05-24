<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\DateTime;

use SimpleAsFuck\Validator\Rule\General\Conversion;

/**
 * @extends Conversion<\DateTimeInterface, non-empty-string>
 */
final class ToIsoString extends Conversion
{
    /**
     * @param \DateTimeInterface $value
     * @return non-empty-string
     */
    public function convert($value): string
    {
        return $value->format(\DateTimeInterface::ATOM);
    }
}
