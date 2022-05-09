<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Model;

final class ValueMust extends \Exception
{
    /**
     * @param non-empty-string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
