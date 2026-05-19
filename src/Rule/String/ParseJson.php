<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\ArrayRule\ArrayRule;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\Numeric\BoolRule;
use SimpleAsFuck\Validator\Rule\Numeric\FloatRule;
use SimpleAsFuck\Validator\Rule\Numeric\IntRule;
use SimpleAsFuck\Validator\Rule\Object\ObjectRule;

/**
 * @extends Rule<string, mixed>
 */
final class ParseJson extends Rule
{
    /**
     * @param non-empty-string $valueName
     * @param int $jsonDecodeFlags bitmask https://www.php.net/manual/en/function.json-decode.php
     */
    public static function make(
        string $value,
        string $valueName = 'string',
        Exception $exceptionFactory = new UnexpectedValueException(),
        bool $allowInvalidJson = false,
        bool $emptyStringAsNull = false,
        int $jsonDecodeFlags = 0,
    ): ParseJson {
        return new ParseJson(
            $exceptionFactory,
            /** @phpstan-ignore-next-line  */
            new RuleChain(),
            new Validated($value),
            $valueName,
            allowInvalidJson: $allowInvalidJson,
            jsonDecodeFlags: $jsonDecodeFlags,
            emptyStringAsNull: $emptyStringAsNull,
        );
    }

    /**
     * @param RuleChain<covariant string> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param int $jsonDecodeFlags bitmask https://www.php.net/manual/en/function.json-decode.php
     */
    public function __construct(
        Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        $valueName,
        private readonly bool $allowInvalidJson = false,
        private readonly int $jsonDecodeFlags = 0,
        bool $useCache = false,
        private readonly bool $emptyStringAsNull = false,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $useCache);
    }

    public function int(): IntRule
    {
        return new IntRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.' json');
    }

    public function float(): FloatRule
    {
        return new FloatRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.' json');
    }

    public function string(bool $sensitiveValue = false): StringRule
    {
        return new StringRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.' json', $sensitiveValue);
    }

    public function bool(): BoolRule
    {
        return new BoolRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.' json');
    }

    public function object(): ObjectRule
    {
        return new ObjectRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.' json');
    }

    public function array(): ArrayRule
    {
        return new ArrayRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName.' json');
    }

    /**
     * @param string $value
     */
    protected function validate($value): mixed
    {
        if ($this->emptyStringAsNull && $value === '') {
            return null;
        }

        $content = \json_decode($value, flags: $this->jsonDecodeFlags);
        if (\json_last_error() !== JSON_ERROR_NONE) {
            if ($this->allowInvalidJson) {
                return null;
            } else {
                $truncated = strlen($value) > 200;
                $logString = $truncated ? substr($value, 0, 200) : $value;
                throw new ValueMust('be valid json (' . \json_last_error_msg() . '), invalid content: \''.$logString.'\''.($truncated ? ' (truncated)' : ''));
            }
        }

        return $content;
    }
}
