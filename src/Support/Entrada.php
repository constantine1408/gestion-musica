<?php

declare(strict_types=1);

namespace Musica\Support;

final class Entrada
{
    /**
     * Lee un campo de texto de $_POST/$_GET. Si alguien manda `titulo[]=x`
     * llega un array: se trata como vacío en lugar de romper con un warning.
     * @param array<string, mixed> $datos
     */
    public static function texto(array $datos, string $campo): string
    {
        $valor = $datos[$campo] ?? '';

        return is_string($valor) ? trim($valor) : '';
    }
}
