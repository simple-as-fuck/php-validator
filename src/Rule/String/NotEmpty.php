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
     * @return Rule<non-empty-string, non-empty-string>
     */
    public function maxByte(int $max): Rule
    {
        /** @var Rule<non-empty-string, non-empty-string> */
        return new Max(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            /** @phpstan-ignore-next-line */
            new StringLength(),
            new CastString(),
            $max,
            'string length in bytes'
        );
    }

    /**
     * @param positive-int $max
     * @param non-empty-string $encoding
     * @return Rule<non-empty-string, non-empty-string>
     */
    public function maxChar(int $max, string $encoding = 'UTF-8'): Rule
    {
        /** @var Rule<non-empty-string, non-empty-string> */
        return new Max(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            /** @phpstan-ignore-next-line */
            new CharacterCount($encoding),
            new CastString(),
            $max,
            'number of ' . $encoding . ' encoded chars'
        );
    }

    /**
     * @deprecated use static::maxChar or static::maxByte
     * @param positive-int $max
     * @return Max<non-empty-string, int>
     */
    public function max(int $max): Max
    {
        /** @var Max<non-empty-string, int> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory,
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
