<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\Key;
use SimpleAsFuck\Validator\Rule\ArrayRule\StringTypedKey;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends Rule<array<non-empty-string, string>, array<mixed>>
 */
final class ParseQuery extends Rule
{
    /** @var Key<string> */
    private Key $key;

    /**
     * @param RuleChain<array<non-empty-string, string>> $ruleChain
     * @param Validated<mixed> $validated
     * @param non-empty-string $valueName
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);

        /** @var RuleChain<array<string>> $ruleChain */
        $this->key = new Key($exceptionFactory, $ruleChain, $validated, $valueName, 'query');
    }

    /**
     * @param non-empty-string $key
     */
    public function key(string $key): StringTypedKey
    {
        return new StringTypedKey(
            $this->exceptionFactory(),
            $this->ruleChain(),
            $this->validated(),
            $this->valueName().' parameter '.$key,
            new Key(
                $this->exceptionFactory(),
                $this->ruleChain(),
                $this->validated(),
                $this->valueName().' parameter '.$key,
                $key
            )
        );
    }

    /**
     * @param array<non-empty-string, string> $value
     * @return array<mixed>
     */
    protected function validate($value): array
    {
        $value = $this->key->validate($value);
        parse_str((string) $value, $queryParams);
        return $queryParams;
    }
}
