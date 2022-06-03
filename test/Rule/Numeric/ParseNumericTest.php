<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Numeric\ParseNumeric;

/**
 * @covers \SimpleAsFuck\Validator\Rule\Numeric\ParseNumeric
 */
final class ParseNumericTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param mixed $input
     */
    public function test(string $expectedOutput, ?string $expectedErrorMessage, $input): void
    {
        $rule = new ParseNumeric(null, new RuleChain(), new Validated($input), 'input');

        if ($expectedErrorMessage !== null) {
            $this->expectException(ValueMust::class);
            $this->expectExceptionMessage($expectedErrorMessage);
        }
        self::assertSame($expectedOutput, $rule->notNull());
    }

    /**
     * @return array<array<mixed>>
     */
    public function dataProvider(): array
    {
        return [
            ['1', null, '1'],
            ['5', null, '5'],
            ['-5', null, '-5'],
            ['-1', null, '-1'],
            ['10', null, '10'],
            ['-10', null, '-10'],
            ['55.9', null, '55.9'],
            ['-45.3', null, '-45.3'],
            ['0', null, '0'],
            ['', 'be parsable as number in decimal system', ''],
            ['', 'be parsable as number in decimal system', 'adssadd'],
            ['', 'be parsable as number in decimal system', '657a'],
            ['', 'be parsable as number in decimal system', '0657'],
            ['', 'be parsable as number in decimal system', '00657'],
            ['', 'be parsable as number in decimal system', '0x657'],
            ['', 'be parsable as number in decimal system', '0x657.7'],
        ];
    }
}
