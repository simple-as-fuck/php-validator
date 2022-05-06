<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\General\Compared;

/**
 * @template Tstring of string
 * @extends \SimpleAsFuck\Validator\Rule\General\InRule<Tstring>
 */
final class InRule extends \SimpleAsFuck\Validator\Rule\General\InRule
{
    private bool $ignoreCharacterSize;

    /**
     * @param RuleChain<Tstring> $ruleChain
     * @param Validated<mixed> $validated
     * @param Compared<Tstring, array<Tstring>> $compared
     * @param non-empty-array<Tstring> $comparedTo
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        Compared $compared,
        $comparedTo,
        bool $ignoreCharacterSize
    ) {
        parent::__construct($exceptionFactory, $ruleChain, $validated, $valueName, $compared, $comparedTo);
        $this->ignoreCharacterSize = $ignoreCharacterSize;
    }

    /**
     * @param Tstring $compared
     * @param array<Tstring> $comparedTo
     */
    protected function compare($compared, $comparedTo): void
    {
        if ($this->ignoreCharacterSize) {
            /** @var Tstring $compared */
            $compared = strtolower($compared);
            /** @var array<Tstring> $comparedTo */
            $comparedTo = array_map(fn (string $value): string => strtolower($value), $comparedTo);
        }

        parent::compare($compared, $comparedTo);
    }
}
