<?php

declare(strict_types=1);

use Musica\Support\Sesion;

/** Escapa texto para HTML. Toda salida de datos del usuario pasa por aquí. */
function e(?string $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function campo_csrf(): string
{
    return '<input type="hidden" name="_token" value="' . e(Sesion::tokenCsrf()) . '">';
}

/** @param array<string, string> $errores */
function mensaje_error(array $errores, string $campo): string
{
    return isset($errores[$campo])
        ? '<span class="error">' . e($errores[$campo]) . '</span>'
        : '';
}
