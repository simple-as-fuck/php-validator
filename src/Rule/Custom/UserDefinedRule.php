<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Custom;

use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @template TIn
 * @template TOut
 */
interface UserDefinedRule
{
    /**
     * @param TIn $value
     * @return TOut|null
     * @throws ValueMust
     */
    public function validate($value);
}
