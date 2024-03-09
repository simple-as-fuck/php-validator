<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Model;

use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @template TLastRuleOut
 */
final class RuleChain
{
    /** @var array<Rule<mixed, mixed>> */
    public readonly array $rules;

    /**
     * @param array<Rule<mixed, mixed>> $rules
     * @param Rule<mixed,TLastRuleOut>|null $lastRule
     */
    public function __construct(array $rules = [], Rule $lastRule = null)
    {
        if ($lastRule !== null) {
            $rules[] = $lastRule;
        }

        $this->rules = $rules;
    }

    /**
     * @deprecated use rules property
     * @return array<Rule<mixed, mixed>>
     */
    public function rules(): array
    {
        return $this->rules;
    }
}
