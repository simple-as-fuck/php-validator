<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Custom;

/**
 * @template TArrayRule of object
 * @template TClass of object
 */
interface UserArrayRule
{
    /**
     * @param TArrayRule $rule
     * @return TClass|null
     */
    public function validate(object $rule): ?object;
}
