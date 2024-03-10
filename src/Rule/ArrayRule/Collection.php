<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\ArrayRule;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\MinWithMax;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Same;

/**
 * @template TOut
 * @extends ReadableRule<array<mixed>, array<TOut>>
 */
final class Collection extends ReadableRule
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
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
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
            /** @phpstan-ignore-next-line */
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
     * @return ArrayMax<TOut>
     */
    public function max(int $max): ArrayMax
    {
        /** @var ArrayMax<TOut> $maxRule */
        $maxRule = new ArrayMax(
            $this->exceptionFactory,
            /** @phpstan-ignore-next-line */
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new ArraySize(),
            /** @phpstan-ignore-next-line */
            new CastString(),
            $max,
            'array size'
        );
        return $maxRule;
    }

    /**
     * @deprecated use nullable() ?? []
     * @return array<TOut>
     */
    public function notNull(bool $failAsEmpty = false): array
    {
        return $this->validateChain($failAsEmpty) ?? [];
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
