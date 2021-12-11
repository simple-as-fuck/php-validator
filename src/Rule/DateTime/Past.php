<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\DateTime;

use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\Min;

/**
 * @template TDateTime of \DateTimeInterface
 * @extends Max<TDateTime, TDateTime>
 */
final class Past extends Max
{
    /**
     * @return Min<TDateTime, TDateTime>
     */
    public function min(): Min
    {
        return new Min($this, $this->valueName(), $this->compared(), $this->toString(), $this->comparedTo());
    }
}
