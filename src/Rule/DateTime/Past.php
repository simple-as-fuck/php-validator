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
     * @param TDateTime $min
     * @return Min<TDateTime, TDateTime>
     */
    public function min(\DateTimeInterface $min): Min
    {
        if ($this->comparedTo <= $min) {
            throw new \LogicException('Min value rule parameter must be in past');
        }

        return new Min($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $this->conversion, $this->toString, $min);
    }
}
