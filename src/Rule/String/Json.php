<?php

declare(strict_types=1);

namespace SimpleAsFuck\Validator\Rule\String;

use SimpleAsFuck\Validator\Model\ValueMust;
use SimpleAsFuck\Validator\Rule\General\Rule;

/**
 * @extends Rule<string, non-empty-string>
 */
final class Json extends Rule
{
    /**
     * @param string $value
     * @return non-empty-string
     */
    protected function validate($value): string
    {
        // todo with 8.3 min requirement use json_validate
        \json_decode($value);
        if (\json_last_error() !== JSON_ERROR_NONE) {
            $truncated = strlen($value) > 200;
            $logString = $truncated ? substr($value, 0, 200) : $value;
            throw new ValueMust('be valid json (' . \json_last_error_msg() . '), invalid content: \''.$logString.'\''.($truncated ? ' (truncated)' : ''));
        }

        /** @var non-empty-string */
        return $value;
    }
}
