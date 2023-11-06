<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\Custom\UserDefinedRule;

/**
 * @covers \SimpleAsFuck\Validator\Rule\Object\Property
 */
final class PropertyTest extends TestCase
{
    public function testCustomException(): void
    {
        $object = new \stdClass();
        $object->property = 0;

        /** @var mixed $object */

        $rule = new \SimpleAsFuck\Validator\Rule\Object\Property(
            new UnexpectedValueException(),
            new RuleChain(),
            new Validated($object),
            'object',
            'property'
        );

        $this->expectExceptionMessage('object->property must be something not value: 0');

        $rule
            ->custom(new class () implements UserDefinedRule {
                public function validate($value)
                {
                    throw new ValueMust('be something not value: ' . $value);
                }
            })
            ->notNull()
        ;
    }
}
