<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Custom;

use SimpleAsFuck\Validator\Rule\Object\ObjectRule;

/**
 * @template TClass of object
 */
interface UserClassRule
{
    /**
     * @return TClass
     */
    public function validate(ObjectRule $rule);
}
