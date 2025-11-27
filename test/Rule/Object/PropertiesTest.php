<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain as RuleChain;
use SimpleAsFuck\Validator\Model\Validated as Validated;
use SimpleAsFuck\Validator\Rule\Object\Properties;

#[CoversClass(Properties::class)]
final class PropertiesTest extends TestCase
{
    public function test(): void
    {
        $object = (object) [
            'property' => 'value',
            '468984' => 'value in numeric string key',
            87946 => 'value in int key',
            '' => 'value in empty string key',
            0 => 'value in zero key',
            -887 => 'value in negative key',
            'property ' => 'value in space key',
        ];

        /** @var RuleChain<object> $ruleChain */
        $ruleChain = new RuleChain();
        $properties = new Properties(
            new UnexpectedValueException(),
            $ruleChain,
            new Validated($object),
            'Object',
            static fn (\SimpleAsFuck\Validator\Rule\Object\Property $property) => $property->nullable(),
        );

        $expectedArray = [
            'property' => 'value',
            '468984' => 'value in numeric string key',
            '87946' => 'value in int key',
            '' => 'value in empty string key',
            '0' => 'value in zero key',
            '-887' => 'value in negative key',
            'property ' => 'value in space key',
        ];
        self::assertSame($expectedArray, $properties->nullable());
    }
}
