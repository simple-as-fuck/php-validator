<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;

/**
 * @extends ReadableRule<string, non-empty-string>
 */
final class ParseIp extends ReadableRule
{
    /**
     * @param RuleChain<string> $ruleChain
     * @param Validated<mixed> $validated
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly ?bool $v4,
        private readonly bool $private = false
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName);
    }

    /**
     * @param string $value
     * @return non-empty-string
     */
    protected function validate($value): string
    {
        $flags = 0;
        if ($this->v4 === true) {
            $flags |= FILTER_FLAG_IPV4;
        }
        if ($this->v4 === false) {
            $flags |= FILTER_FLAG_IPV6;
        }
        if ($this->private === false) {
            $flags |= FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
        }

        if (filter_var($value, FILTER_VALIDATE_IP, $flags) === false) {
            throw new ValueMust('be valid'.($this->private ? '' : ' public').' ip'.($this->v4 !== null ? ($this->v4 ? ' v4' : ' v6') : '').' address');
        }

        /** @var non-empty-string $value */
        return $value;
    }
}
