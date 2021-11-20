<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Model;

/**
 * @template TValue
 */
final class Validated
{
    /** @var TValue|null */
    private $value;

    /**
     * @param TValue|null $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return TValue|null
     */
    public function value()
    {
        return $this->value;
    }
}
