<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\EmailValidation;
use Egulias\EmailValidator\Validation\RFCValidation;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\DateTime\DateTime;
use SimpleAsFuck\Validator\Rule\DateTime\ParseDateTime;
use SimpleAsFuck\Validator\Rule\Email\EmailRule;
use SimpleAsFuck\Validator\Rule\Enum\Enum;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\InRule;
use SimpleAsFuck\Validator\Rule\General\Max;
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
     * @param positive-int $number of bytes that string length can have
     * @return Rule<string, non-empty-string>
     */
    public function exactByte(int $number): Rule
    {
        /** @phpstan-ignore-next-line */
        return new Same(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new StringLength(),
            $number,
            'string length in bytes'
        );
    }

    /**
     * @param positive-int $number of encoded chars that string length can have
     * @param non-empty-string $encoding
     * @return Rule<string, non-empty-string>
     */
    public function exactChar(int $number, string $encoding = 'UTF-8'): Rule
    {
        /** @phpstan-ignore-next-line */
        return new Same(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new CharacterCount($encoding),
            $number,
            'number of ' . $encoding . ' encoded chars'
        );
    }

    /**
     * @param positive-int $min
     * @return MinLength<non-empty-string>
     */
    public function minByte(int $min): MinLength
    {
        /** @var MinLength<non-empty-string> */
        return new MinLength(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new StringLength(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $min,
            'string length in bytes'
        );
    }

    /**
     * @param positive-int $min
     * @param non-empty-string $encoding
     * @return MinLength<non-empty-string>
     */
    public function minChar(int $min, string $encoding = 'UTF-8'): MinLength
    {
        /** @var MinLength<non-empty-string> */
        return new MinLength(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new CharacterCount($encoding),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $min,
            'number of ' . $encoding . ' encoded chars'
        );
    }

    /**
     * @param positive-int $max
     * @return Rule<string, string>
     */
    public function maxByte(int $max): Rule
    {
        return new Max(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new StringLength(),
            new CastString(),
            $max,
            'string length in bytes'
        );
    }

    /**
     * @param positive-int $max
     * @param non-empty-string $encoding
     * @return Rule<string, string>
     */
    public function maxChar(int $max, string $encoding = 'UTF-8'): Rule
    {
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
     * @deprecated use static::decimal
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
     * @param positive-int $digits maximum digits before decimal separator, without minus sign
     * @param int<0, max> $decimals maximum digits after decimal separator
     * @return Rule<numeric-string, numeric-string>
     */
    public function decimal(int $digits, int $decimals): Rule
    {
        return (new ParseNumeric(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName.': \''.$this->nullable(true).'\''
        ))
            ->maxDigit($digits)->maxDecimal($decimals)
        ;
    }

    /**
     * @param non-empty-string $format
     * @param non-empty-string|null $timeZone
     * @return Rule<string, non-empty-string>
     */
    public function dateTime(string $format, ?string $timeZone = null): Rule
    {
        return new DateTime(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName.': \''.$this->nullable(true).'\'',
            $format,
            $timeZone,
        );
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
        /** @phpstan-ignore-next-line */
        return $this->url($requiredComponents, $forbiddenComponents, ['http', 'https']);
    }

    /**
     * @param array<PHP_URL_SCHEME|PHP_URL_HOST|PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $requiredComponents
     * @param array<PHP_URL_PORT|PHP_URL_USER|PHP_URL_PASS|PHP_URL_PATH|PHP_URL_QUERY|PHP_URL_FRAGMENT> $forbiddenComponents
     * @return UrlRule<non-empty-string>
     */
    public function httpsUrl(array $requiredComponents = [], array $forbiddenComponents = []): UrlRule
    {
        /** @phpstan-ignore-next-line */
        return $this->url($requiredComponents, $forbiddenComponents, ['https']);
    }

    /**
     * @deprecated use static::ipv4
     * @param bool $private if false private and reserved ip address will fail
     */
    public function parseIpv4(bool $private = false): ParseIp
    {
        return new ParseIp($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', true, $private);
    }

    /**
     * @deprecated use static::ipv6
     * @param bool $private if false private and reserved ip address will fail
     */
    public function parseIpv6(bool $private = false): ParseIp
    {
        return new ParseIp($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', false, $private);
    }

    /**
     * @param bool $private if false private and reserved ip address will fail
     * @return Rule<string, non-empty-string>
     */
    public function ipv4(bool $private = false): Rule
    {
        return new ParseIp($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', true, $private);
    }

    /**
     * @param bool $private if false private and reserved ip address will fail
     * @return Rule<string, non-empty-string>
     */
    public function ipv6(bool $private = false): Rule
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
     * @deprecated use static::inCaseInsensitive
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
     * @template Tstring of string
     * @param non-empty-array<Tstring> $values
     * @return Rule<string, Tstring>
     */
    public function inCaseInsensitive(array $values): Rule
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
     * @return Rule<string, value-of<TEnum>>
     */
    public function inEnum(string $enumClass): Rule
    {
        if (((string) (new \ReflectionEnum($enumClass))->getBackingType()) !== 'string') {
            throw new \LogicException('BackedEnum: '.$enumClass.' must be of type string');
        }

        /** @phpstan-ignore-next-line */
        return $this->in(array_map(static fn (\BackedEnum $enum): string => (string) $enum->value, $enumClass::cases()));
    }

    /**
     * @deprecated use static::inEnum or static::parseEnum
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
     * @template TEnum of \BackedEnum of string
     * @param class-string<TEnum> $enumClass
     * @return Rule<string, TEnum>
     */
    public function parseEnum(string $enumClass): Rule
    {
        if (((string) (new \ReflectionEnum($enumClass))->getBackingType()) !== 'string') {
            throw new \LogicException('BackedEnum: '.$enumClass.' must be of type string');
        }

        /** @phpstan-ignore-next-line */
        return new Enum(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            $enumClass
        );
    }

    /**
     * @param non-empty-array<EmailValidation> $validations with and
     */
    public function email(array $validations = [new RFCValidation(), new DNSCheckValidation()]): EmailRule
    {
        return new EmailRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.': \''.$this->nullable(true).'\'', $validations);
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
