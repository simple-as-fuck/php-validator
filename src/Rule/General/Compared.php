<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

/**
 * @template TCompared
 * @template TOut
 */
abstract class Compared
{
    /**
     * @param TCompared $comparedValue
     * @return TOut
     */
    abstract public function convert($comparedValue);
}
