<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Url;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\Key;
use SimpleAsFuck\Validator\Rule\General\ForwardRule;

/**
 * @template TComponent
 * @extends ForwardRule<array<TComponent>, TComponent>
 */
class Component extends ForwardRule
{
    public function __construct(?Exception $exceptionFactory, RuleChain $ruleChain, Validated $validated, string $valueName, string $componentName)
    {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, new Key($exceptionFactory, $ruleChain, $validated, $valueName, $componentName));
    }
}
