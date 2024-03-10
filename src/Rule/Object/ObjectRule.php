<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Object;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Custom\UserClassRule;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends Rule<mixed, object>
 */
final class ObjectRule extends Rule
{
    /**
     * @param non-empty-string $valueName
     */
    public static function make(mixed $value, string $valueName = 'variable', bool $emptyAsNull = false): ObjectRule
    {
        return new ObjectRule(new UnexpectedValueException(), new RuleChain(), new Validated($value), $valueName, $emptyAsNull);
    }

    /**
     * @param RuleChain<mixed> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly bool $emptyAsNull = false
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @template TClass of object
     * @param UserClassRule<TClass> $rule
     * @return ClassRule<TClass>
     */
    public function class(UserClassRule $rule): ClassRule
    {
        return new ClassRule($this, $rule);
    }

    /**
     * @param bool $present true value turns off conversion from not existing property into null
     */
    public function property(string $name, bool $present = false): Property
    {
        return new Property($this->exceptionFactory, $this->ruleChain(), $this->validated, $this->valueName, $name, $present);
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): ?object
    {
        if (!is_object($value)) {
            throw new ValueMust('be object, '.gettype($value).' given');
        }

        if ($this->emptyAsNull && count((array) $value) === 0) {
            return null;
        }

        return $value;
    }
}
