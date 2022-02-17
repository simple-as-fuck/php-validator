<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\Key;
use SimpleAsFuck\Validator\Rule\ArrayRule\StringTypedKey;

/**
 * @extends Component<string>
 */
final class Query extends Component
{
    /** @var array<mixed> */
    private array $parsedParams;

    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $componentName)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $componentName);
        $this->parsedParams = [];
    }

    /**
     * @param non-empty-string $key
     */
    public function key(string $key): StringTypedKey
    {
        $this->validateChain();
        $ruleChain = new RuleChain();
        /** @phpstan-ignore-next-line */
        return new StringTypedKey($ruleChain, new Key($this->exceptionFactory(), $ruleChain, new Validated($this->parsedParams), $this->valueName().' parameter '.$key, $key));
    }

    /**
     * @param array<string> $value
     */
    protected function validate($value): ?string
    {
        $value = parent::validate($value);
        parse_str((string) $value, $this->parsedParams);
        return $value;
    }
}