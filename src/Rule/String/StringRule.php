<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\DateTime\ParseDateTime;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\InRule;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Same;
use SimpleAsFuck\Validator\Rule\Numeric\ParseNumeric;
use SimpleAsFuck\Validator\Rule\Url\ParseProtocolUrl;
use SimpleAsFuck\Validator\Rule\Url\ParseUrl;

/**
 * @extends ReadableRule<mixed, string>
 */
final class StringRule extends ReadableRule
{
    private bool $emptyAsNull;

    /**
     * @param mixed $value
     * @param non-empty-string $valueName
     * @return StringRule
     */
    public static function make($value, string $valueName = 'variable', bool $emptyAsNull = false): StringRule
    {
        return new StringRule(new UnexpectedValueException(), new RuleChain(), new Validated($value), $valueName, false, $emptyAsNull);
    }

    /**
     * @param RuleChain<mixed> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        bool $useSecondaryOutput = false,
        bool $emptyAsNull = false
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $useSecondaryOutput);
        $this->emptyAsNull = $emptyAsNull;
    }

    /**
     * @param positive-int $size
     * @return Same<non-empty-string, int>
     */
    public function size(int $size): Same
    {
        /** @var Same<non-empty-string, int> $sameRule */
        $sameRule = new Same(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            /** @phpstan-ignore-next-line */
            new StringLength(),
            $size,
            'string length'
        );
        return $sameRule;
    }

    /**
     * @param positive-int $min
     * @return MinWithMax<non-empty-string, int>
     */
    public function min(int $min): MinWithMax
    {
        /** @var MinWithMax<non-empty-string, int> $minRule */
        $minRule = new MinWithMax(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            /** @phpstan-ignore-next-line */
            new StringLength(),
            new CastString(),
            $min,
            'string length'
        );
        return $minRule;
    }

    /**
     * @param positive-int $max
     * @return Max<string, int>
     */
    public function max(int $max): Max
    {
        /** @var Max<string, int> $maxRule */
        $maxRule = new Max(
            $this->exceptionFactory(),
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

    public function parseInt(): ParseInt
    {
        return new ParseInt($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().': \''.$this->validateChain(true).'\'');
    }

    public function parseFloat(): ParseFloat
    {
        return new ParseFloat($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().': \''.$this->validateChain(true).'\'');
    }

    public function parseNumeric(bool $allowLeadingZero = false, bool $allowNegative = true): ParseNumeric
    {
        return new ParseNumeric($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().': \''.$this->validateChain(true).'\'', $allowLeadingZero, $allowNegative);
    }

    /**
     * @param positive-int $digits maximum digits before decimal separator, without minus sign
     * @param int<0, max> $decimals maximum digits after decimal separator
     * @return Max<numeric-string, int>
     */
    public function parseDecimal(int $digits, int $decimals): Max
    {
        $numericRule = new ParseNumeric($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().': \''.$this->validateChain(true).'\'');
        return $numericRule->maxDigit($digits)->maxDecimal($decimals);
    }

    /**
     * @template TDateTime of \DateTimeInterface
     * @param non-empty-string $format
     * @param class-string<TDateTime> $dateTimeClass
     * @param non-empty-string|null $timeZone
     * @return ParseDateTime<TDateTime>
     */
    public function parseDateTime(string $format, string $dateTimeClass = \DateTimeImmutable::class, ?string $timeZone = null): ParseDateTime
    {
        return new ParseDateTime($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().': \''.$this->validateChain(true).'\'', $format, $dateTimeClass, $timeZone);
    }

    /**
     * @template TDateTime of \DateTimeInterface
     * @param class-string<TDateTime> $dateTimeClass
     * @return ParseDateTime<TDateTime>
     */
    public function parseIsoDateTime(string $dateTimeClass = \DateTimeImmutable::class): ParseDateTime
    {
        return $this->parseDateTime(\DateTimeInterface::ATOM, $dateTimeClass);
    }

    /**
     * @param array<int<0,7>> $requiredComponents array of PHP_URL_ constants
     * @param array<int<0,7>> $forbiddenComponents array of PHP_URL_ constants
     * @return ParseUrl<string>
     */
    public function parseUrl(array $requiredComponents = [], array $forbiddenComponents = []): ParseUrl
    {
        return new ParseUrl($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().': \''.$this->validateChain(true).'\'', $requiredComponents, $forbiddenComponents);
    }

    /**
     * @param array<int<0,7>> $requiredComponents array of PHP_URL_ constants
     * @param array<int<2,7>> $forbiddenComponents array of PHP_URL_ constants
     * @return ParseUrl<non-empty-string>
     */
    public function parseHttpUrl(array $requiredComponents = [], array $forbiddenComponents = []): ParseUrl
    {
        return new ParseProtocolUrl(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().': \''.$this->validateChain(true).'\'',
            $requiredComponents,
            $forbiddenComponents,
            ['http', 'https']
        );
    }

    /**
     * @param array<int<0,7>> $requiredComponents array of PHP_URL_ constants
     * @param array<int<2,7>> $forbiddenComponents array of PHP_URL_ constants
     * @return ParseUrl<non-empty-string>
     */
    public function parseHttpsUrl(array $requiredComponents = [], array $forbiddenComponents = []): ParseUrl
    {
        return new ParseProtocolUrl(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().': \''.$this->validateChain(true).'\'',
            $requiredComponents,
            $forbiddenComponents,
            ['https']
        );
    }

    /**
     * @param bool $private if false private and reserved ip address will fail
     */
    public function parseIpv4(bool $private = false): ParseIp
    {
        return new ParseIp($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName(), true, $private);
    }

    /**
     * @param bool $private if false private and reserved ip address will fail
     */
    public function parseIpv6(bool $private = false): ParseIp
    {
        return new ParseIp($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName(), false, $private);
    }

    public function notEmpty(): NotEmpty
    {
        return new NotEmpty($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName());
    }

    /**
     * @param non-empty-string $pattern cool example: '/.+/'
     * @param PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL|768|0 $flags
     * @return Regex<string>
     */
    public function regex(string $pattern, int $flags = 0): Regex
    {
        return new Regex($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().': \''.$this->validateChain(true).'\'', $pattern, $flags);
    }

    /**
     * @param non-empty-string $pattern cool example: '/(?P<matchKey>.*)/'
     * @param PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL|768|0 $flags
     */
    public function parseRegex(string $pattern, int $flags = 0): ParseRegex
    {
        return new ParseRegex($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().': \''.$this->validateChain(true).'\'', $pattern, $flags);
    }

    /**
     * @template Tstring of string
     * @param non-empty-array<Tstring> $values
     * @return InRule<Tstring>
     */
    public function in(array $values): InRule
    {
        /** @var InRule<Tstring> $inRule */
        $inRule = new InRule(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName(),
            $values
        );
        return $inRule;
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): ?string
    {
        if (!is_string($value)) {
            throw new ValueMust('be string, '.gettype($value).' given');
        }

        if ($this->emptyAsNull && $value === '') {
            return null;
        }

        return $value;
    }
}
