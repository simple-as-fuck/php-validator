<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Rule\Object\ObjectRule;
use SimpleAsFuck\Validator\Rule\Object\Property;

final class ObjectTest extends TestCase
{
    /**
     * @dataProvider dataNullable
     */
    public function testNullable(mixed $expectedValue, mixed $object): void
    {
        $rule = ObjectRule::make($object);

        $value = $rule->nullable('property', static fn (Property $property): string => $property->string()->notNull());

        self::assertSame($expectedValue, $value);
    }

    /**
     * @return non-empty-array<non-empty-array<mixed>>
     */
    public static function dataNullable(): array
    {
        return [
            [null, null],
            [null, (object) []],
            [null, (object) ['otherProperty' => 'aaa']],
            ['bbb', (object) ['property' => 'bbb']],
            [null, (object) ['property' => null]],
        ];
    }

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
