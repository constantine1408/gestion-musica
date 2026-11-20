<?php

declare(strict_types=1);

namespace Musica\Exception;

use RuntimeException;

final class NoEncontradoException extends RuntimeException
{
    public static function album(int $id): self
    {
        return new self("No existe el álbum #$id.");
    }

    public static function artista(int $id): self
    {
        return new self("No existe el artista #$id.");
    }

    public static function ruta(string $ruta): self
    {
        return new self("La página $ruta no existe.");
    }
}
