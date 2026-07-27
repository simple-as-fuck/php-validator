<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;

/**
 * @extends \SimpleAsFuck\Validator\Rule\Common\StringRule<mixed>
 */
final class StringRule extends \SimpleAsFuck\Validator\Rule\Common\StringRule
{
    /**
     * @param non-empty-string $valueName
     */
    public static function make(mixed $value, string $valueName = 'variable', bool $sensitiveValue = false, bool $emptyAsNull = false): StringRule
    {
        return new StringRule(
            new UnexpectedValueException(),
            new RuleChain(),
            new Validated($value),
            $valueName,
            $sensitiveValue,
            $emptyAsNull,
        );
    }

    /**
     * @param RuleChain<covariant mixed> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param bool $sensitiveValue if true string value is not dump into error message like: https://www.php.net/manual/en/class.sensitiveparameter.php
     */
    public function __construct(
        Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly bool $sensitiveValue = false,
        private readonly bool $emptyAsNull = false,
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): ?string
    {
        if ($this->emptyAsNull && $value === '') {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        throw new ValueMust('be string, '.gettype($value).' given');
    }

    /**
     * @return non-empty-string
     */
    protected function valueName(): string
    {
        if ($this->sensitiveValue) {
            return $this->valueName;
        }

        return $this->valueName.': \''.$this->nullable(true).'\'';
    }
}
