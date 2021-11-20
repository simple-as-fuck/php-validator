<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Rule\General\Compared;

/**
 * @extends Compared<string, int>
 */
final class StringLength extends Compared
{
    /**
     * @param string $comparedValue
     */
    public function convert($comparedValue): int
    {
        return strlen($comparedValue);
    }
}
