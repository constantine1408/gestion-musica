<?php

declare(strict_types=1);

namespace Musica\Domain;

final class Album
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $titulo,
        public readonly string $genero,
        public readonly int $anioLanzamiento,
        public readonly ?string $discografica,
    ) {
    }

    /** @param array<string, mixed> $fila */
    public static function desdeFila(array $fila): self
    {
        return new self(
            (int) $fila['album_id'],
            (string) $fila['titulo'],
            (string) $fila['genero'],
            (int) $fila['anio_lanzamiento'],
            $fila['discografica'] === null ? null : (string) $fila['discografica'],
        );
    }
}
