<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Numeric;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Same;

/**
 * @extends ReadableRule<string, numeric-string>
 */
final class ParseNumeric extends ReadableRule
{
    private bool $allowLeadingZero;

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
        bool $allowLeadingZero = false
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
        $this->allowLeadingZero = $allowLeadingZero;
    }

    /**
     * @param positive-int $min minimum digits before decimal separator, without minus sign
     */
    public function minDigit(int $min): MinDigit
    {
        return new MinDigit(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            new DigitCount(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $min,
            'digits before decimals'
        );
    }

    /**
     * @param positive-int $max maximum digits before decimal separator, without minus sign
     */
    public function maxDigit(int $max): MaxDigit
    {
        return new MaxDigit(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            new DigitCount(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $max,
            'digits before decimals'
        );
    }

    /**
     * @param int<0,max> $max maximum digits after decimal separator
     * @return Max<numeric-string, int>
     */
    public function maxDecimal(int $max): Max
    {
        /** @var Max<numeric-string, int> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            /** @phpstan-ignore-next-line */
            new DecimalCount(),
            new CastString(),
            $max,
            'decimal digits'
        );
        return $maxRule;
    }

    /**
     * @param positive-int $min digits before decimal separator, without minus sign
     * @param positive-int $max digits before decimal separator, without minus sign
     * @param int<0,max> $maxDecimal maximum digits after decimal separator
     * @return Max<numeric-string, int>
     */
    public function betweenDigit(int $min, int $max, int $maxDecimal = 0): Max
    {
        return $this->minDigit($min)->maxDigit($max)->maxDecimal($maxDecimal);
    }

    /**
     * @param positive-int $number digits before decimal separator, without minus sign
     * @param int<0,max> $maxDecimal maximum digits after decimal separator
     * @return Max<numeric-string, int>
     */
    public function exactDigit(int $number, int $maxDecimal = 0): Max
    {
        $same = new Same(
            $this->exceptionFactory(),
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            /** @phpstan-ignore-next-line */
            new DigitCount(),
            $number,
            'digits before decimals'
        );
        /** @var Max<numeric-string, int> */
        return new Max(
            $same->exceptionFactory(),
            $same->ruleChain(),
            $same->validated(),
            $same->valueName(),
            /** @phpstan-ignore-next-line */
            new DecimalCount(),
            new CastString(),
            $maxDecimal,
            'decimal digits'
        );
    }

    /**
     * @param string $value
     * @return numeric-string
     */
    protected function validate($value): string
    {
        if (preg_match('/^-?(0|['.($this->allowLeadingZero ? 0 : 1).'-9]\d*)(\.\d+)?$/', $value) !== 1) {
            throw new ValueMust('be parsable as number in decimal system'.($this->allowLeadingZero ? ' (leading zero allowed)' : ''));
        }

        /** @var numeric-string $value */
        return $value;
    }
}
