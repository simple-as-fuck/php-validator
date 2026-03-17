<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Common\NotIn;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends \SimpleAsFuck\Validator\Rule\General\IntRule<mixed>
 */
final class IntRule extends \SimpleAsFuck\Validator\Rule\General\IntRule
{
    /**
     * @param non-empty-string $valueName
     * @return \SimpleAsFuck\Validator\Rule\General\IntRule<mixed>
     */
    public static function make(mixed $value, string $valueName = 'variable'): \SimpleAsFuck\Validator\Rule\General\IntRule
    {
        return new IntRule(new UnexpectedValueException(), new RuleChain(), new Validated($value), $valueName);
    }

    /**
     * @todo 0.8 move to abstract IntRule
     * @param array<int> $values
     * @return Rule<int, int>
     */
    public function notIn(array $values): Rule
    {
        return new NotIn(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $values,
        );
    }

    /**
     * @todo 0.8 move to abstract IntRule
     * @return Rule<int, non-zero-int>
     */
    public function notZero(): Rule
    {
        /** @phpstan-ignore-next-line return.type */
        return $this->notIn([0]);
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): int
    {
        if (!is_int($value)) {
            throw new ValueMust('be integer, '.gettype($value).' given');
        }

        return $value;
    }
}
