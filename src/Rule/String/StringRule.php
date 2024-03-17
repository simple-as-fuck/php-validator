<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\DateTime\ParseDateTime;
use SimpleAsFuck\Validator\Rule\Enum\Enum;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\InRule;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\General\Same;
use SimpleAsFuck\Validator\Rule\Numeric\ParseNumeric;
use SimpleAsFuck\Validator\Rule\Url\UrlRule;
use SimpleAsFuck\Validator\Rule\Url\ParseUrl;

/**
 * @extends Rule<mixed, string>
 */
final class StringRule extends Rule
{
    /**
     * @param non-empty-string $valueName
     */
    public static function make(mixed $value, string $valueName = 'variable'): StringRule
    {
        return new StringRule(new UnexpectedValueException(), new RuleChain(), new Validated($value), $valueName);
    }

    /**
     * @param positive-int $size
     * @return Same<non-empty-string, int>
     */
    public function size(int $size): Same
    {
        /** @var Same<non-empty-string, int> $sameRule */
        $sameRule = new Same(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
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
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
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

    public function parseInt(): ParseInt
    {
        return new ParseInt($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'');
    }

    /**
     * @param non-empty-string $trueDefinition
     * @param non-empty-string $falseDefinition
     */
    public function parseBool(string $trueDefinition = 'true', string $falseDefinition = 'false'): ParseBool
    {
        return new ParseBool(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName . ': \'' . $this->nullable(true) . '\'',
            $trueDefinition,
            $falseDefinition
        );
    }

    public function parseFloat(): ParseFloat
    {
        return new ParseFloat($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'');
    }

    public function numeric(bool $allowLeadingZero = false): ParseNumeric
    {
        return new ParseNumeric($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', $allowLeadingZero);
    }

    /**
     * @param positive-int $digits maximum digits before decimal separator, without minus sign
     * @param int<0, max> $decimals maximum digits after decimal separator
     * @return Max<numeric-string, int>
     */
    public function parseDecimal(int $digits, int $decimals): Max
    {
        $numericRule = new ParseNumeric($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'');
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
        return new ParseDateTime($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', $format, $dateTimeClass, $timeZone);
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
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $requiredComponents
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $forbiddenComponents
     * @return ParseUrl<string>
     */
    public function parseUrl(array $requiredComponents = [], array $forbiddenComponents = []): ParseUrl
    {
        return new ParseUrl($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', $requiredComponents, $forbiddenComponents);
    }

    /**
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $requiredComponents
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $forbiddenComponents
     * @param array<non-empty-string> $requiredSchemes
     * @return UrlRule<string>
     */
    public function url(array $requiredComponents = [], array $forbiddenComponents = [], array $requiredSchemes = []): UrlRule
    {
        return new UrlRule(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName.': \''.$this->nullable(true).'\'',
            $requiredComponents,
            $forbiddenComponents,
            $requiredSchemes
        );
    }

    /**
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $requiredComponents
     * @param array<PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $forbiddenComponents
     * @return UrlRule<non-empty-string>
     */
    public function httpUrl(array $requiredComponents = [], array $forbiddenComponents = []): UrlRule
    {
        /** @var UrlRule<non-empty-string> */
        return $this->url($requiredComponents, $forbiddenComponents, ['http', 'https']);
    }

    /**
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $requiredComponents
     * @param array<PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $forbiddenComponents
     * @return UrlRule<non-empty-string>
     */
    public function httpsUrl(array $requiredComponents = [], array $forbiddenComponents = []): UrlRule
    {
        /** @var UrlRule<non-empty-string> */
        return $this->url($requiredComponents, $forbiddenComponents, ['https']);
    }

    /**
     * @param bool $private if false private and reserved ip address will fail
     */
    public function parseIpv4(bool $private = false): ParseIp
    {
        return new ParseIp($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', true, $private);
    }

    /**
     * @param bool $private if false private and reserved ip address will fail
     */
    public function parseIpv6(bool $private = false): ParseIp
    {
        return new ParseIp($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', false, $private);
    }

    public function notEmpty(bool $emptyAsNull = false): NotEmpty
    {
        return new NotEmpty($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $emptyAsNull);
    }

    /**
     * @param non-empty-string $pattern cool example: '/.+/'
     * @param PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL|768|0 $flags
     * @return Regex<string>
     */
    public function regex(string $pattern, int $flags = 0): Regex
    {
        return new Regex($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', $pattern, $flags);
    }

    /**
     * @param non-empty-string $pattern cool example: '/(?P<matchKey>.*)/'
     * @param PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL|768|0 $flags
     */
    public function parseRegex(string $pattern, int $flags = 0): ParseRegex
    {
        return new ParseRegex($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', $pattern, $flags);
    }

    /**
     * @template Tstring of string
     * @param non-empty-array<Tstring> $values
     * @return InRule<string, Tstring>
     */
    public function in(array $values): InRule
    {
        return new InRule(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $values
        );
    }

    /**
     * @template Tstring of string
     * @param non-empty-array<Tstring> $values
     * @return InRule<string, Tstring>
     */
    public function caseInsensitiveIn(array $values): InRule
    {
        return new CaseInsensitiveInRule(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $values
        );
    }

    /**
     * @template TEnum of \BackedEnum of string
     * @param class-string<TEnum> $enumClass
     * @return Enum<TEnum>
     */
    public function enum(string $enumClass): Enum
    {
        if (((string) (new \ReflectionEnum($enumClass))->getBackingType()) !== 'string') {
            throw new \LogicException('BackedEnum: '.$enumClass.' must be of type string');
        }

        return new Enum($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $enumClass);
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): string
    {
        if (!is_string($value)) {
            throw new ValueMust('be string, '.gettype($value).' given');
        }

        return $value;
    }
}
