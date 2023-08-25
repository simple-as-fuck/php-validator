<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use SimpleAsFuck\Validator\Factory\Validator as Validator;
use SimpleAsFuck\Validator\Rule\ArrayRule\TypedKey as TypedKey;
use SimpleAsFuck\Validator\Rule\DateTime\ParseDateTime as ParseDateTime;
use SimpleAsFuck\Validator\Rule\String\StringRule as StringRule;

/*class C
{
    public $aaa = 'aa';
}*/
//$variable = 'https://aaaa';
//$variable = new C();
$variable = 'adsad=2005-05-08=';

//$a = Validator::make($i)->string()->parseUrl();
//$a = Validator::make($variable)->string()->parseDecimal(10,2);
//$a = Validator::make($variable)->array()->of(fn(TypedKey $key): string => $key->string()->notNull())->notNull();
//$a = Validator::make($variable)->array()->ofString()->notNull();
$a = Validator::make($variable)->string()->parseRegex('/=(?P<test>.*)=/')->match('test')->parseDateTime('Y-m-d')->notNull();

//$a = ParseDateTime::make($variable, 'Y-m-d', \DateTime::class, 'variable', '+00:00')->notNull();

//$a = StringRule::make($variable)->parseDateTime('aaaa', \DateTime::class)->notNull();
//$a = ParseDateTime::make($variable, 'aaaa', \DateTime::class)->nullable();


//function a(int $a) {

//}

//a($a);
var_dump($a);