<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Model;

/**
 * @template TValue
 */
final readonly class Validated
{
    /**
     * @param TValue|null $value
     */
    public function __construct(
        public mixed $value
    ) {
    }
}
