<?php

declare(strict_types=1);

namespace Musica\Service;

use DateTimeImmutable;
use Musica\Domain\Album;
use Musica\Domain\Artista;
use Musica\Exception\NoEncontradoException;
use Musica\Exception\ValidationException;
use Musica\Repository\ArtistaRepository;
use Musica\Support\Entrada;

final class ArtistaService
{
    public function __construct(private readonly ArtistaRepository $artistas)
    {
    }

    /** @return Artista[] */
    public function listar(string $busqueda = ''): array
    {
        return $this->artistas->listar($busqueda);
    }

    public function obtener(int $id): Artista
    {
        return $this->artistas->buscar($id) ?? throw NoEncontradoException::artista($id);
    }

    /** @return Album[] */
    public function albumesDe(int $artistaId): array
    {
        $this->obtener($artistaId);

        return $this->artistas->albumesDe($artistaId);
    }

    /** @param array<string, mixed> $datos */
    public function crear(array $datos): int
    {
        return $this->artistas->crear($this->validar($datos));
    }

    /** @param array<string, mixed> $datos */
    public function actualizar(int $id, array $datos): void
    {
        $this->obtener($id);
        $this->artistas->actualizar($this->validar($datos, $id));
    }

    public function eliminar(int $id): void
    {
        if (!$this->artistas->eliminar($id)) {
            throw NoEncontradoException::artista($id);
        }
    }

    /** @param array<string, mixed> $datos */
    private function validar(array $datos, ?int $id = null): Artista
    {
        $errores = [];

        $nombre = Entrada::texto($datos, 'nombre');
        $apellido = Entrada::texto($datos, 'apellido');
        $fechaTexto = Entrada::texto($datos, 'fecha_nacimiento');
        $nacionalidad = Entrada::texto($datos, 'nacionalidad');

        if ($nombre === '') {
            $errores['nombre'] = 'El nombre es obligatorio.';
        } elseif (mb_strlen($nombre) > 80) {
            $errores['nombre'] = 'El nombre no puede pasar de 80 caracteres.';
        }

        if ($apellido === '') {
            $errores['apellido'] = 'El apellido es obligatorio.';
        } elseif (mb_strlen($apellido) > 80) {
            $errores['apellido'] = 'El apellido no puede pasar de 80 caracteres.';
        }

        if ($fechaTexto !== '') {
            // El "!" pone en cero las partes no indicadas y el formato de ida y
            // vuelta descarta fechas imposibles como 2020-02-31.
            $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $fechaTexto);

            if ($fecha === false || $fecha->format('Y-m-d') !== $fechaTexto) {
                $errores['fecha_nacimiento'] = 'Usa el formato AAAA-MM-DD.';
            } elseif ($fecha > new DateTimeImmutable('today')) {
                $errores['fecha_nacimiento'] = 'La fecha de nacimiento no puede ser futura.';
            } elseif ($fecha < new DateTimeImmutable('1800-01-01')) {
                $errores['fecha_nacimiento'] = 'La fecha de nacimiento es demasiado antigua.';
            }
        }

        if (mb_strlen($nacionalidad) > 60) {
            $errores['nacionalidad'] = 'La nacionalidad no puede pasar de 60 caracteres.';
        }

        if ($errores !== []) {
            throw new ValidationException($errores);
        }

        return new Artista(
            $id,
            $nombre,
            $apellido,
            $fechaTexto === '' ? null : $fechaTexto,
            $nacionalidad === '' ? null : $nacionalidad,
        );
    }
}
