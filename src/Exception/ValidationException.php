<?php

declare(strict_types=1);

namespace Musica\Exception;

use RuntimeException;

final class ValidationException extends RuntimeException
{
    /** @param array<string, string> $errores mensaje por nombre de campo */
    public function __construct(public readonly array $errores)
    {
        parent::__construct('Hay campos con errores: ' . implode(', ', array_keys($errores)));
    }
}
