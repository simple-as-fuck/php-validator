<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\ComparedValue;
use SimpleAsFuck\Validator\Rule\General\InRule;
use SimpleAsFuck\Validator\Rule\General\Max;
use SimpleAsFuck\Validator\Rule\General\Min;
use SimpleAsFuck\Validator\Rule\General\ReadableRule;
use SimpleAsFuck\Validator\Rule\General\Same;

/**
 * @extends ReadableRule<mixed, string>
 */
final class StringRule extends ReadableRule
{
    /**
     * @param positive-int $size
     * @return Same<string, int>
     */
    public function size(int $size): Same
    {
        /** @phpstan-ignore-next-line */
        return new Same($this, $this->valueName().' string length', new StringLength(), $size);
    }

    /**
     * @param positive-int $min
     * @return Min<string, int>
     */
    public function min(int $min): Min
    {
        /** @phpstan-ignore-next-line */
        return new Min($this, $this->valueName().' string length', new StringLength(), $min);
    }

    /**
     * @param positive-int $max
     * @return Max<string, int>
     */
    public function max(int $max): Max
    {
        /** @phpstan-ignore-next-line */
        return new Max($this, $this->valueName().' string length', new StringLength(), $max);
    }

    public function parseInt(): ParseInt
    {
        return new ParseInt($this, $this->valueName().': "'.$this->validateChain().'"');
    }

    public function parseFloat(): ParseFloat
    {
        return new ParseFloat($this, $this->valueName().': "'.$this->validateChain().'"');
    }

    public function notEmpty(): NotEmpty
    {
        return new NotEmpty($this, $this->valueName());
    }

    /**
     * @param string $pattern cool example: '/(?P<matchKey>.*)/'
     */
    public function regex(string $pattern, int $flags = 0): Regex
    {
        return new Regex($this, $this->valueName().': "'.$this->validateChain().'"', $pattern, $flags);
    }

    /**
     * @param non-empty-array<string> $values
     * @return InRule<string>
     */
    public function in(array $values): InRule
    {
        return new InRule($this, $this->valueName(), new ComparedValue(), $values);
    }

    /**
     * @param mixed $value
     */
    protected function validate($value): string
    {
        if (! is_string($value)) {
            throw new ValueMust('be string');
        }

        return $value;
    }
}
