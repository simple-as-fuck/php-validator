<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\DateTime\ParseDateTime;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\ComparedValue;
use SimpleAsFuck\Validator\Rule\General\InRule;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Same;
use SimpleAsFuck\Validator\Rule\Url\ParseUrl;

/**
 * @extends ReadableRule<mixed, string>
 */
final class StringRule extends ReadableRule
{
    /**
     * @param mixed $value
     * @return StringRule
     */
    public static function make($value, string $valueName = 'variable'): StringRule
    {
        return new StringRule(new UnexpectedValueException(), new RuleChain(), new Validated($value), $valueName);
    }

    /**
     * @param positive-int $size
     * @return Same<string, int>
     */
    public function size(int $size): Same
    {
        /** @phpstan-ignore-next-line */
        return new Same($this, $this->valueName(), new StringLength(), $size, 'string length');
    }

    /**
     * @param positive-int $min
     * @return MinWithMax<string, int>
     */
    public function min(int $min): MinWithMax
    {
        /** @phpstan-ignore-next-line */
        return new MinWithMax($this, $this->valueName(), new StringLength(), new CastString(), $min, 'string length');
    }

    /**
     * @param positive-int $max
     * @return Max<string, int>
     */
    public function max(int $max): Max
    {
        /** @phpstan-ignore-next-line */
        return new Max($this, $this->valueName(), new StringLength(), new CastString(), $max, 'string length');
    }

    public function parseInt(): ParseInt
    {
        return new ParseInt($this, $this->valueName().': "'.$this->validateChain().'"');
    }

    public function parseFloat(): ParseFloat
    {
        return new ParseFloat($this, $this->valueName().': "'.$this->validateChain().'"');
    }

    /**
     * @template TDateTime of \DateTimeInterface
     * @param non-empty-string $format
     * @param class-string<TDateTime> $dateTimeClass
     * @return ParseDateTime<TDateTime>
     */
    public function parseDateTime(string $format, string $dateTimeClass = \DateTimeImmutable::class): ParseDateTime
    {
        return new ParseDateTime($this, $this->valueName().': "'.$this->validateChain().'"', $format, $dateTimeClass);
    }

    /**
     * @template TDateTime of \DateTimeInterface
     * @param class-string<TDateTime> $dateTimeClass
     * @return ParseDateTime<TDateTime>
     */
    public function parseIsoDateTime(string $dateTimeClass = \DateTimeImmutable::class): ParseDateTime
    {
        return $this->parseDateTime(\DateTimeInterface::ISO8601, $dateTimeClass);
    }

    /**
     * @param array<int<0,7>> $requiredComponents array of PHP_URL_ constants
     * @param array<int<0,7>> $forbiddenComponents array of PHP_URL_ constants
     * @return ParseUrl<string>
     */
    public function parseUrl(array $requiredComponents = [], array $forbiddenComponents = []): ParseUrl
    {
        return new ParseUrl($this->exceptionFactory(), $this->ruleChain(), $this->validated(), $this->valueName().': "'.$this->validateChain().'"', $requiredComponents, $forbiddenComponents);
    }

    /**
     * @param array<int<0,7>> $requiredComponents array of PHP_URL_ constants
     * @param array<int<2,7>> $forbiddenComponents array of PHP_URL_ constants
     * @return ParseUrl<non-empty-string>
     */
    public function parseHttpUrl(array $requiredComponents = [], array $forbiddenComponents = []): ParseUrl
    {
        $requiredComponents[] = PHP_URL_SCHEME;
        $requiredComponents[] = PHP_URL_HOST;
        $urlRule = $this->parseUrl($requiredComponents, $forbiddenComponents);
        $urlRule->scheme()->in(['http', 'https'])->notNull();
        /** @phpstan-ignore-next-line */
        return $urlRule;
    }

    /**
     * @param array<int<0,7>> $requiredComponents array of PHP_URL_ constants
     * @param array<int<2,7>> $forbiddenComponents array of PHP_URL_ constants
     * @return ParseUrl<non-empty-string>
     */
    public function parseHttpsUrl(array $requiredComponents = [], array $forbiddenComponents = []): ParseUrl
    {
        $requiredComponents[] = PHP_URL_SCHEME;
        $requiredComponents[] = PHP_URL_HOST;
        $urlRule = $this->parseUrl($requiredComponents, $forbiddenComponents);
        $urlRule->scheme()->in(['https'])->notNull();
        /** @phpstan-ignore-next-line */
        return $urlRule;
    }

    public function notEmpty(): NotEmpty
    {
        return new NotEmpty($this, $this->valueName());
    }

    /**
     * @param string $pattern cool example: '/(?P<matchKey>.*)/'
     */
    public function regex(string $pattern, int $flags = 0): Regex
    {
        return new Regex($this, $this->valueName().': "'.$this->validateChain().'"', $pattern, $flags);
    }

    /**
     * @param non-empty-array<string> $values
     * @return InRule<string>
     */
    public function in(array $values): InRule
    {
        return new InRule($this, $this->valueName(), new ComparedValue(), $values);
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): string
    {
        if (! is_string($value)) {
            throw new ValueMust('be string');
        }

        return $value;
    }
}
