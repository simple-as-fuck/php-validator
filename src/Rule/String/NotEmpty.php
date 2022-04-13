<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<string, non-empty-string>
 */
final class NotEmpty extends ReadableRule
{
    /**
     * @param positive-int $max
     * @return Max<non-empty-string, int>
     */
    public function max(int $max): Max
    {
        /** @var Max<non-empty-string, int> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            /** @phpstan-ignore-next-line */
            new StringLength(),
            new CastString(),
            $max,
            'string length'
        );
        return $maxRule;
    }

    /**
     * @param string $value
     * @return non-empty-string
     */
    protected function validate($value): string
    {
        if ($value === '') {
            throw new ValueMust('be non empty string');
        }

        return $value;
    }
}
