<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Custom\UserDefinedRule;
use SimpleAsFuck\Validator\Rule\Object\Property;

/**
 * @covers \SimpleAsFuck\Validator\Rule\Object\Property
 */
final class PropertyTest extends TestCase
{
    public function testCustomException(): void
    {
        $object = new \stdClass();
        $object->property = 0;

        /** @var RuleChain<object> $ruleChain */
        $ruleChain = new RuleChain();
        $rule = new Property(
            new UnexpectedValueException(),
            $ruleChain,
            new Validated($object),
            'object',
            'property'
        );

        $this->expectExceptionMessage('object->property must be something not value type: int');

        $rule
            ->custom(new class () implements UserDefinedRule {
                public function validate($value)
                {
                    throw new ValueMust('be something not value type: ' . gettype($value));
                }
            })
            ->notNull()
        ;
    }

    #[DataProvider('data')]
    public function test(string $propertyName, object $testObject): void
    {
        /** @var RuleChain<object> $ruleChain */
        $ruleChain = new RuleChain();
        $property = new Property(
            new UnexpectedValueException(),
            $ruleChain,
            new Validated($testObject),
            'object',
            $propertyName,
        );

        self::assertSame('value', $property->nullable());
    }

    /**
     * @return array<array<mixed>>
     */
    public static function data(): array
    {
        return [
            ['test', (object) ['test' => 'value']],
            ['1', (object) ['1' => 'value']],
            ['1', (object) [1 => 'value']],
        ];
    }
}
