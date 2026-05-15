<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Custom\CallableRule;
use SimpleAsFuck\Validator\Rule\Custom\CustomRule;
use SimpleAsFuck\Validator\Rule\Custom\UserDefinedRule;

/**
 * @extends \SimpleAsFuck\Validator\Rule\Common\StringRule<mixed>
 */
final class StringRule extends \SimpleAsFuck\Validator\Rule\Common\StringRule
{
    /**
     * @param non-empty-string $valueName
     */
    public static function make(mixed $value, string $valueName = 'variable', bool $sensitiveValue = false): StringRule
    {
        return new StringRule(
            new UnexpectedValueException(),
            new RuleChain(),
            new Validated($value),
            $valueName,
            $sensitiveValue,
        );
    }

    /**
     * @todo 0.8 move to abstract StringRule
     * @template TCustomOut
     * @param UserDefinedRule<string, TCustomOut> $rule
     * @return CustomRule<string, TCustomOut>
     */
    public function custom(UserDefinedRule $rule): CustomRule
    {
        return new CustomRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName(), $rule);
    }

    /**
     * @todo 0.8 move to abstract StringRule
     * @template TCallableOut
     * @param callable(string): TCallableOut $callable
     * @return CallableRule<string, TCallableOut>
     */
    public function callable(callable $callable): CallableRule
    {
        return new CallableRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName(), $callable);
    }

    /**
     * @todo 0.8 move to abstract StringRule
     */
    public function json(): Json
    {
        return new Json($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName);
    }

    /**
     * @todo 0.8 move to abstract StringRule
     * @param int $jsonDecodeFlags bitmask https://www.php.net/manual/en/function.json-decode.php
     */
    public function parseJson(bool $allowInvalidJson = false, int $jsonDecodeFlags = 0, bool $useCache = false): ParseJson
    {
        return new ParseJson($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $allowInvalidJson, $jsonDecodeFlags, $useCache);
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
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
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
