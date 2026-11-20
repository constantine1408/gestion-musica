<?php

declare(strict_types=1);

namespace Musica\Domain;

final class Artista
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $nombre,
        public readonly string $apellido,
        public readonly ?string $fechaNacimiento, // formato Y-m-d
        public readonly ?string $nacionalidad,
    ) {
    }

    /** @param array<string, mixed> $fila */
    public static function desdeFila(array $fila): self
    {
        return new self(
            (int) $fila['artista_id'],
            (string) $fila['nombre'],
            (string) $fila['apellido'],
            $fila['fecha_nacimiento'] === null ? null : (string) $fila['fecha_nacimiento'],
            $fila['nacionalidad'] === null ? null : (string) $fila['nacionalidad'],
        );
    }

    public function nombreCompleto(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }
}
