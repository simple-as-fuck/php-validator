<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\General\Same;

/**
 * @template TOut
 * @extends Rule<array<mixed>, array<TOut>>
 */
final class Collection extends Rule
{
    /**
     * @param RuleChain<array<mixed>> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     * @param callable(TypedKey): TOut $callable
     */
    public function __construct(
        ?Exception $exceptionFactory,
        private readonly RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly mixed $callable
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param positive-int $size
     * @return Same<non-empty-array<TOut>, int>
     */
    public function size(int $size): Same
    {
        /** @var Same<non-empty-array<TOut>, int> $sameRule */
        $sameRule = new Same(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            /** @phpstan-ignore-next-line */
            new ArraySize(),
            $size,
            'array size'
        );
        return $sameRule;
    }

    /**
     * @param positive-int $min
     * @return MinWithMax<non-empty-array<TOut>, int>
     */
    public function min(int $min): MinWithMax
    {
        /** @var MinWithMax<non-empty-array<TOut>, int> $minRule */
        $minRule = new MinWithMax(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            /** @phpstan-ignore-next-line */
            new ArraySize(),
            new CastString(),
            $min,
            'array size'
        );
        return $minRule;
    }

    /**
     * @param positive-int $max
     * @return Max<array<TOut>, int>
     */
    public function max(int $max): Max
    {
        /** @var Max<array<TOut>, int> */
        return new Max(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            /** @phpstan-ignore-next-line */
            new ArraySize(),
            new CastString(),
            $max,
            'array size'
        );
    }

    /**
     * @param array<mixed> $value
     * @return array<TOut>
     */
    final protected function validate($value): array
    {
        $result = [];
        foreach ($value as $key => $item) {
            $result[$key] = ($this->callable)(new TypedKey($this->exceptionFactory, $this->ruleChain, $this->validated, $this->valueName.'['.$key.']', $key));
        }

        return $result;
    }
}
