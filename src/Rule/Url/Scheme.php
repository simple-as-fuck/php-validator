<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Rule\General\InRule;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\String\CaseInsensitiveInRule;

/**
 * @extends Rule<array{scheme?: string}, string>
 */
final class Scheme extends Rule
{
    /**
     * @template Tstring of non-empty-string
     * @param non-empty-array<Tstring> $values
     * @return InRule<string, Tstring>
     */
    public function in(array $values): InRule
    {
        return new CaseInsensitiveInRule(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $values
        );
    }

    /**
     * @param array{scheme?: string} $value
     */
    protected function validate($value): ?string
    {
        return $value['scheme'] ?? null;
    }
}
