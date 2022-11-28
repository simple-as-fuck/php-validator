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
    /**
     * @param RuleChain<TIn> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        private ?Exception $exceptionFactory,
        private RuleChain $ruleChain,
        private Validated $validated,
        private string $valueName
    ) {
    }

    /**
     * @template TCustomOut
     * @param UserDefinedRule<TOut, TCustomOut> $rule
     * @return CustomRule<TOut, TCustomOut>
     */
    final public function custom(UserDefinedRule $rule): CustomRule
    {
        return new CustomRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $rule);
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

    /**
     * @return non-empty-string
     */
    final protected function valueName(): string
    {
        return $this->valueName;
    }

    /**
     * @return TOut|null
     */
    final protected function validateChain(bool $failAsNull = false): mixed
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
     * @return TRuleOut|null
     */
    private function validateRule(Rule $rule, mixed &$value, bool $failAsNull): mixed
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

            if ($this->exceptionFactory === null) {
                throw $exception;
            }
            throw $this->exceptionFactory->create($rule->valueName.' must '.$exception->getMessage());
        }
    }
}
