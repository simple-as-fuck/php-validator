<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template TIn
 * @template TOut
 * @extends Rule<TIn, TOut>
 */
abstract class ReadableRule extends Rule
{
    /**
     * @return TOut|null
     */
    public function nullable(bool $failAsNull = false)
    {
        return $this->validateChain($failAsNull);
    }

    /**
     * @return TOut
     */
    public function notNull()
    {
        $value = $this->validateChain();
        if ($value === null) {
            if ($this->exceptionFactory === null) {
                throw new ValueMust('be not null');
            }
            throw $this->exceptionFactory->create($this->valueName.' must be not null');
        }

        return $value;
    }
}
