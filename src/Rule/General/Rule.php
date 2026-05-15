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
    /** @var Validated<mixed>|null */
    private ?Validated $cache = null;

    /**
     * @todo 0.8 remove $useCache and add cache() method
     * @param RuleChain<covariant TIn> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        protected readonly Exception $exceptionFactory,
        private RuleChain $ruleChain,
        protected Validated $validated,
        protected readonly string $valueName,
        private readonly bool $useCache = false,
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
     * @return ($if is true ? TOut : TOut|null)
     */
    final public function notNull(bool $if = true): mixed
    {
        $value = $this->nullable();
        if ($value === null && $if) {
            throw $this->exceptionFactory->create($this->valueName.' must be not null');
        }

        return $value;
    }

    /**
     * @return TOut|null
     */
    final public function nullable(bool $failAsNull = false): mixed
    {
        $value = $this->validated->value;

        try {
            foreach ($this->ruleChain->rules as $rule) {
                $value = self::validateRule($rule, $value, $this->exceptionFactory);
            }
            return self::validateRule($this, $value, $this->exceptionFactory);
        } catch (\Throwable $exception) {
            if ($failAsNull) {
                return null;
            }
            throw $exception;
        }
    }

    /**
     * @param TIn $value
     * @return TOut|null
     * @throws ValueMust
     */
    abstract protected function validate($value);

    /**
     * @return RuleChain<TOut>
     */
    final protected function ruleChain(): RuleChain
    {
        return new RuleChain($this->ruleChain->rules, $this);
    }

    /**
     * @template TRuleOut
     * @param Rule<mixed, TRuleOut> $rule
     * @return TRuleOut|null
     */
    private static function validateRule(Rule $rule, mixed &$value, Exception $exceptionFactory): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($rule->cache !== null) {
            return $rule->cache->value;
        }

        try {
            if ($rule->useCache) {
                $rule->cache = new Validated($rule->validate($value));
                $rule->validated = $rule->cache;
                $rule->ruleChain = new RuleChain();
                return $rule->cache->value;
            }

            return $rule->validate($value);
        } catch (ValueMust $exception) {
            throw $exceptionFactory->create($rule->valueName.' must '.$exception->getMessage());
        }
    }
}
