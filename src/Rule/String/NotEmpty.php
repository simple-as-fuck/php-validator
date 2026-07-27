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
     * @todo $emptyAsNull mark as deprecated by PHP attribute in version with minimum PHP 8.4
     * @param RuleChain<covariant string> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly bool $emptyAsNull = false,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param positive-int $max
     * @param non-empty-string|null $measuredEncoding
     * @param non-empty-string|null $sourceEncoding
     * @return Rule<non-empty-string, non-empty-string>
     */
    public function maxByte(int $max, ?string $measuredEncoding = null, ?string $sourceEncoding = null): Rule
    {
        $stringLength = new StringLength($measuredEncoding, $sourceEncoding);
        /** @var Max<non-empty-string, positive-int> */
        return new Max(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $stringLength,
            new CastString(),
            $max,
            $stringLength->convertedName()
        );
    }

    /**
     * @param positive-int $max
     * @param non-empty-string $encoding
     * @return Rule<non-empty-string, non-empty-string>
     */
    public function maxChar(int $max, string $encoding = 'UTF-8'): Rule
    {
        /** @var Max<non-empty-string, positive-int> */
        return new Max(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new CharacterCount($encoding),
            new CastString(),
            $max,
            'number of ' . $encoding . ' encoded chars'
        );
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
