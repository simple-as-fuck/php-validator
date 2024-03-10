<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\General;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Custom\CallableRule;
use SimpleAsFuck\Validator\Rule\Custom\CustomRule;
use SimpleAsFuck\Validator\Rule\Custom\UserDefinedRule;

/**
 * @template TIn
 * @template TOut
 */
abstract class Rule
{
    /**
     * @param RuleChain<covariant TIn> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        protected readonly ?Exception $exceptionFactory,
        private readonly RuleChain $ruleChain,
        protected readonly Validated $validated,
        protected readonly string $valueName
    ) {
    }

    /**
     * @template TCustomOut
     * @param UserDefinedRule<TOut, TCustomOut> $rule
     * @return CustomRule<TOut, TCustomOut>
     */
    public function custom(UserDefinedRule $rule): CustomRule
    {
        return new CustomRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $rule);
    }

    /**
     * @template TCallableOut
     * @param callable(TOut): TCallableOut $callable
     * @return CallableRule<TOut, TCallableOut>
     */
    public function callable(callable $callable): CallableRule
    {
        return new CallableRule($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $callable);
    }

    /**
     * @return TOut
     */
    public function notNull()
    {
        $value = $this->nullable();
        if ($value === null) {
            if ($this->exceptionFactory === null) {
                throw new ValueMust('be not null');
            }
            throw $this->exceptionFactory->create($this->valueName.' must be not null');
        }

        return $value;
    }

    /**
     * @return TOut|null
     */
    public function nullable(bool $failAsNull = false)
    {
        $value = $this->validated->value;

        foreach ($this->ruleChain->rules as $rule) {
            $value = $this->validateRule($rule, $value, $failAsNull);
        }
        return $this->validateRule($this, $value, $failAsNull);
    }

    /**
     * @param TIn $value
     * @return TOut|null
     * @throws ValueMust
     */
    abstract protected function validate($value);

    /**
     * @deprecated use property exceptionFactory
     */
    final protected function exceptionFactory(): ?Exception
    {
        return $this->exceptionFactory;
    }

    /**
     * @return RuleChain<TOut>
     */
    final protected function ruleChain(): RuleChain
    {
        return new RuleChain($this->ruleChain->rules, $this);
    }

    /**
     * @deprecated use property validated
     * @return Validated<mixed>
     */
    final protected function validated(): Validated
    {
        return $this->validated;
    }

    /**
     * @deprecated use property valueName
     * @return non-empty-string
     */
    final protected function valueName(): string
    {
        return $this->valueName;
    }

    /**
     * @deprecated use nullable()
     * @return TOut|null
     */
    final protected function validateChain(bool $failAsNull = false): mixed
    {
        return $this->nullable($failAsNull);
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
        } catch (\Throwable $exception) {
            if ($failAsNull) {
                return null;
            }

            throw $exception;
        }
    }
}
