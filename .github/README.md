# Simple as fuck / Php Validator

Validator for php variables with intuitive rule chain, put inside mixed and you in the end get requested type.

## Installation

```console
composer require simple-as-fuck/php-validator
```

## Support

If any PHP platform requirements in [composer.json](../composer.json) ends with security support,
consider package version as unsupported except last version.

[PHP supported versions](https://www.php.net/supported-versions.php).

## Usage

```php

/** @var mixed $value */
$value = $config->get('some_value_name');

$rules = \SimpleAsFuck\Validator\Factory\Validator::make($value, 'Config "some_value_name" value');
$validValue = $rules->string()->notEmpty()->notNull();
/*
 * now you have in $value really not empty string and even phpstan know the type without any annoying annotation
 * if validation failed \UnexpectedValueException('Config "some_value_name" value must ...') is thrown from rule chain
 */

/*
 * shorter notation, value name in validator factory is optional and here is unnecessary,
 * validation exception is thrown in same line as config key name
 * so you should this find in your stacktrace and know than something is wrong in your config file
 */
\SimpleAsFuck\Validator\Factory\Validator::make($config->get('some_value_name'))->string()->notEmpty()->notNull();

```

This validation can be applied into any php variable and is appropriately usable for json decoded data.
All rules have declared types for next rule in chain so not look for any rules list,
your IDE should hint you available rules and rule chain is designed for preventing redundant rules or
rule combination witch do not make sense.

## Validation exception type changing

You can change exception type throw from rule chain while validation failed by inject yours exception factory.

If you want throw some HTTP exception for validation http request beware of this validator is not suitable for
validation user inputs.

First: validator fail in first unsuccessful rule in nested structure and throw only one exception even if
there can be more validation fails.

Second: validator throw highly generated messages which can be for user unreadable and translation support
is not in plan, for us developers messages should be fine.

Third: realise than we write in PHP server side application so the app should return data in some API format
and view data should some weird javascript, native or mobile client app. Yes I know than even pc games are rendered in cloud but client pc has also some computing power and
if client logic and html is rendered on server is only wasting of its power.

```php

final class ExceptionFactory extends \SimpleAsFuck\Validator\Factory\Exception
{
    public function create(string $message): \Exception
    {
        return new \RuntimeException($message);
    }
}

$exceptionFactory = new \ExceptionFactory();

/** @var mixed $value */
$value = 1;

$rules = new \SimpleAsFuck\Validator\Rule\General\Rules($exceptionFactory, 'variable', $value);

$validValue = $rules->int()->notNull();

```

## Customization

### User defined rule

You can create your rule and put it in the end of rule chain and this allow you to run some yours validation.

If you are validating something what is widely standardized, consider contribute into rule chain,
for more nicely writing it or share some rule with others.

```php

/**
 * @implements \SimpleAsFuck\Validator\Rule\Custom\UserDefinedRule<string, string>
 */
final class GandalfRule implements \SimpleAsFuck\Validator\Rule\Custom\UserDefinedRule
{
    /**
     * @param string $value
     */
    public function validate($value): ?string
    {
        throw new \SimpleAsFuck\Validator\Model\ValueMust('not pass');
    }
}

$rules = \SimpleAsFuck\Validator\Factory\Validator::make('');

$rules->string()->custom(new \GandalfRule())->notNull();

```

### User class rule

Validator rule chain always return some specific type so generic object is not in option.
You are able to convert object into some class with validated structure.

```php

/**
 * @implements \SimpleAsFuck\Validator\Rule\Custom\UserClassRule<YourClass>
 */
final class YourClassRule implements \SimpleAsFuck\Validator\Rule\Custom\UserClassRule
{
    public function validate(\SimpleAsFuck\Validator\Rule\Object\ObjectRule $object): YourClass
    {
        return new YourCass(
            $object->property('propertyName')->string()->max(30)->notNull()
            // some next property ...
        );
    }
}

$object = new \stdClass();

$rules = \SimpleAsFuck\Validator\Factory\Validator::make($object);

$yourObject = $rules->object()->class(new YourClassRule())->notNull();

```
