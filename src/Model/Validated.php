<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Model;

/**
 * @template TValue
 */
final class Validated
{
    /**
     * @param TValue|null $value
     */
    public function __construct(
        private mixed $value
    ) {
    }

    /**
     * @return TValue|null
     */
    public function value(): mixed
    {
        return $this->value;
    }
}
