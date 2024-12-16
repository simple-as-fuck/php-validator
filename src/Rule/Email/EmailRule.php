<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\Email;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\EmailValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use SimpleAsFuck\Validator\Factory\Exception;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\CastString;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\Rule;
use SimpleAsFuck\Validator\Rule\String\CharacterCount;

/**
 * @extends Rule<string, non-empty-string>
 */
final class EmailRule extends Rule
{
    /**
     * @param RuleChain<covariant string> $ruleChain
     * @param Validated<covariant mixed> $validated
     * @param non-empty-string $valueName
     * @param non-empty-array<EmailValidation> $validations
     */
    public function __construct(
        ?Exception $exceptionFactory,
        RuleChain $ruleChain,
        Validated $validated,
        string $valueName,
        private readonly array $validations,
    ) {
        parent::__construct(
            $exceptionFactory,
            $ruleChain,
            $validated,
            $valueName,
        );
    }

    /**
     * @param positive-int $max
     * @param non-empty-string $encoding
     * @return Rule<non-empty-string, non-empty-string>
     */
    public function maxChar(int $max, string $encoding = 'UTF-8'): Rule
    {
        /** @var Max<non-empty-string, positive-int> */
        return new Max(
            $this->exceptionFactory,
            $this->ruleChain(),
            $this->validated,
            $this->valueName,
            new CharacterCount($encoding),
            new CastString(),
            $max,
            'number of ' . $encoding . ' encoded chars'
        );
    }

    /**
     * @param string $value
     * @return non-empty-string
     */
    protected function validate($value): string
    {
        $emailValidator = new EmailValidator();

        if ($emailValidator->isValid($value, new MultipleValidationWithAnd($this->validations, MultipleValidationWithAnd::STOP_ON_ERROR))) {
            /** @var non-empty-string */
            return $value;
        }

        throw new ValueMust('be a valid email');
    }
}
