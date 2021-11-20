<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

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
    public function nullable()
    {
        return $this->validateChain();
    }

    /**
     * @return TOut
     */
    public function notNull()
    {
        $value = $this->validateChain();
        if ($value === null) {
            throw $this->exceptionFactory()->create($this->valueName().' must be not null');
        }

        return $value;
    }
}
