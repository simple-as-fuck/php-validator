<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\ComparedValue;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<string, int>
 */
final class ParseInt extends ReadableRule
{
    /**
     * @return MinWithMax<int, int>
     */
    public function min(int $min): MinWithMax
    {
        /** @var MinWithMax<int, int> $minRule */
        $minRule = new MinWithMax(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            new ComparedValue(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $min
        );
        return $minRule;
    }

    /**
     * @return Max<int, int>
     */
    public function max(int $max): Max
    {
        /** @var Max<int, int> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            new ComparedValue(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $max
        );
        return $maxRule;
    }

    /**
     * @param string $value
     */
    protected function validate($value): int
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new ValueMust('be parsable as integer');
        }

        return (int) $value;
    }
}
