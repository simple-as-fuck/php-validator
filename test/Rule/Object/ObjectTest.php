<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Rule\Object\ObjectRule;

final class ObjectTest extends TestCase
{
    /**
     * @dataProvider dataEmptyAsNull
     */
    public function testEmptyAsNull(mixed $expectedValue, mixed $object): void
    {
        $rule = ObjectRule::make($object, emptyAsNull: true);

        $value = $rule->property('property', present: true)->string()->nullable();

        self::assertSame($expectedValue, $value);
    }

    /**
     * @return non-empty-array<non-empty-array<mixed>>
     */
    public static function dataEmptyAsNull(): array
    {
        return [
            [null, null],
            [null, (object) []],
            ['test', (object) ['property' => 'test']],
            [null, (object) ['property' => null]],
        ];
    }
}
