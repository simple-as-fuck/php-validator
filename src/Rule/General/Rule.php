<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Custom\CustomRule;
use SimpleAsFuck\Validator\Rule\Custom\UserDefinedRule;

/**
 * @template TIn
 * @template TOut
 */
abstract class Rule
{
    private ?Exception $exceptionFactory;
    /** @var RuleChain<TIn> */
    private RuleChain $ruleChain;
    /** @var Validated<mixed> */
    private Validated $validated;
    private string $valueName;

    /**
     * @param RuleChain<TIn> $ruleChain
     * @param Validated<mixed> $validated
     */
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName)
    {
        $this->exceptionFactory = $exceptionFactory;
        $this->ruleChain = $ruleChain;
        $this->validated = $validated;
        $this->valueName = $valueName;
    }

    /**
     * @template TCustomOut
     * @param UserDefinedRule<TOut, TCustomOut> $rule
     * @return CustomRule<TOut, TCustomOut>
     */
    final public function custom(UserDefinedRule $rule): CustomRule
    {
        return new CustomRule($this, $rule);
    }

    /**
     * @param TIn $value
     * @return TOut|null
     * @throws ValueMust
     */
    abstract protected function validate($value);

    final protected function exceptionFactory(): ?Exception
    {
        return $this->exceptionFactory;
    }

    /**
     * @return RuleChain<TOut>
     */
    final protected function ruleChain(): RuleChain
    {
        return new RuleChain($this->ruleChain->rules(), $this);
    }

    /**
     * @return Validated<mixed>
     */
    final protected function validated(): Validated
    {
        return $this->validated;
    }

    final protected function valueName(): string
    {
        return $this->valueName;
    }

    /**
     * @return TOut|null
     */
    final protected function validateChain(bool $failAsNull = false)
    {
        $value = $this->validated->value();

        foreach ($this->ruleChain->rules() as $rule) {
            $value = $this->validateRule($rule, $value, $failAsNull);
        }
        return $this->validateRule($this, $value, $failAsNull);
    }

    /**
     * @template TRuleOut
     * @param Rule<mixed, TRuleOut> $rule
     * @param mixed $value
     * @return TRuleOut|null
     */
    private function validateRule(Rule $rule, &$value, bool $failAsNull)
    {
        if ($value === null) {
            return null;
        }

        try {
            return $rule->validate($value);
        } catch (ValueMust $exception) {
            if ($failAsNull) {
                return null;
            }

            if (! $this->exceptionFactory) {
                throw $exception;
            }
            throw $this->exceptionFactory->create($rule->valueName.' must '.$exception->getMessage());
        }
    }
}
