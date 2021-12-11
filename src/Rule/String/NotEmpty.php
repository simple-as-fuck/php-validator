<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends ReadableRule<string, non-empty-string>
 */
final class NotEmpty extends ReadableRule
{
    /**
     * @param Rule<mixed, string> $rule
     */
    public function __construct(Rule $rule, string $valueName)
    {
        parent::__construct($rule->exceptionFactory(), $rule->ruleChain(), $rule->validated(), $valueName);
    }

    /**
     * @param positive-int $max
     * @return Max<string, int>
     */
    public function max(int $max): Max
    {
        /** @phpstan-ignore-next-line */
        return new Max($this, $this->valueName().' string length', new StringLength(), new CastString(), $max);
    }

    /**
     * @param string $value
     * @return non-empty-string
     */
    protected function validate($value): string
    {
        if ($value === '') {
            throw new ValueMust('not be empty string');
        }

        return $value;
    }
}
