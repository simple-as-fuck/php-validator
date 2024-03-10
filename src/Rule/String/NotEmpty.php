<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends Rule<string, non-empty-string>
 */
final class NotEmpty extends Rule
{
    /**
     * @param RuleChain<string> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly bool $emptyAsNull = false,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param positive-int $max
     * @return Max<non-empty-string, int>
     */
    public function max(int $max): Max
    {
        /** @var Max<non-empty-string, int> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory,
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
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
     * @return non-empty-string|null
     */
    protected function validate($value): ?string
    {
        if ($value === '') {
            if ($this->emptyAsNull) {
                return null;
            }

            throw new ValueMust('be non empty string');
        }

        return $value;
    }
}
