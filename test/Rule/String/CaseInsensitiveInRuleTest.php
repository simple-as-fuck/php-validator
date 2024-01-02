<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\String\CaseInsensitiveInRule;

final class CaseInsensitiveInRuleTest extends TestCase
{
    /**
     * @dataProvider data
     *
     * @param non-empty-array<string> $values
     */
    public function test(?string $expectedValue, ?string $expectedExceptionMessage, mixed $value, array $values): void
    {
        if ($expectedExceptionMessage !== null) {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $rule = new CaseInsensitiveInRule(
            new UnexpectedValueException(),
            new RuleChain(),
            new Validated($value),
            'variable',
            $values
        );
        self::assertSame($expectedValue, $rule->nullable());
    }

    /**
     * @return non-empty-array<non-empty-array<mixed>>
     */
    public static function data(): array
    {
        return [
            ['test', null, 'test', ['test']],
            ['lower case', null, 'LoWeR CaSe', ['lower case']],
            ['UPPER CASE', null, 'uPPeR CaSe', ['UPPER CASE']],
            ['Random Case', null, 'RANDOM CASE', ['Random Case']],
            [null, 'variable must be in values list: lower char, UPPER CHAR', 'wrong input', ['lower char', 'UPPER CHAR']]
        ];
    }
}
