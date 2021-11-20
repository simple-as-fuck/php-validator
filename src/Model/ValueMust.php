<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Model;

final class ValueMust extends \Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }
}
