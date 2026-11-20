<?php

declare(strict_types=1);

namespace Musica\Support;

final class Sql
{
    /**
     * Arma el patrón de un LIKE "contiene" tratando %, _ y ! como texto literal.
     * Se usa con `LIKE :p ESCAPE '!'`; ese carácter funciona igual en MySQL y SQLite
     * (con la barra invertida cada motor interpreta el literal distinto).
     */
    public static function patronLike(string $texto): string
    {
        return '%' . strtr($texto, ['!' => '!!', '%' => '!%', '_' => '!_']) . '%';
    }
}
