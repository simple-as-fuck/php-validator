<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleAsFuck\Validator\Factory\UnexpectedValueException;
use SimpleAsFuck\Validator\Model\RuleChain;
use SimpleAsFuck\Validator\Model\Validated;
use SimpleAsFuck\Validator\Rule\ArrayRule\ArrayOfString;
use SimpleAsFuck\Validator\Rule\ArrayRule\StringTypedKey;

/**
 * @covers \SimpleAsFuck\Validator\Rule\ArrayRule\ArrayOfString
 */
final class ArrayOfStringTest extends TestCase
{
    public function testKey(): void
    {
        /** @var Validated<mixed> $validated */
        $validated = new Validated(['test' => '1']);
        $rule = new ArrayOfString(new UnexpectedValueException(), new RuleChain(), $validated, 'value');
        $value = $rule->key('test')->string()->notNull();

        self::assertSame('1', $value);

        /** @var Validated<mixed> $validated */
        $validated = new Validated(null);
        $rule = new ArrayOfString(new UnexpectedValueException(), new RuleChain(), $validated, 'value');

        $this->expectException(\UnexpectedValueException::class);
        $this->expectErrorMessage('value[test] must be not null');

        $rule->key('test')->string()->notNull();
    }

    public function testOf(): void
    {
        /** @var Validated<mixed> $validated */
        $validated = new Validated(['test']);
        $rule = new ArrayOfString(new UnexpectedValueException(), new RuleChain(), $validated, 'value');
        $array = $rule->of(fn (StringTypedKey $key): string => $key->string()->notNull())->notNull();

        self::assertSame(['test'], $array);

        /** @var Validated<mixed> $validated */
        $validated = new Validated([[]]);
        $rule = new ArrayOfString(new UnexpectedValueException(), new RuleChain(), $validated, 'value');

        $this->expectException(\UnexpectedValueException::class);
        $this->expectErrorMessage('value[0] must be string');

        $rule->of(fn (StringTypedKey $key): string => $key->string()->notNull())->notNull();
    }
}
