<?php

declare(strict_types=1);

namespace Musica\Service;

use Musica\Domain\Album;
use Musica\Domain\Artista;
use Musica\Exception\NoEncontradoException;
use Musica\Exception\ValidationException;
use Musica\Repository\AlbumRepository;
use Musica\Repository\ArtistaRepository;
use Musica\Support\Entrada;

final class AlbumService
{
    // El primer álbum grabado que se suele tomar como referencia es de 1877
    // (el fonógrafo de Edison); antes de eso no tiene sentido un año de lanzamiento.
    private const ANIO_MINIMO = 1877;

    public function __construct(
        private readonly AlbumRepository $albumes,
        private readonly ArtistaRepository $artistas,
    ) {
    }

    /** @return array<int, array{album: Album, artistas: Artista[]}> */
    public function listarConArtistas(string $busqueda = ''): array
    {
        $albumes = $this->albumes->listar($busqueda);
        $artistasPorAlbum = $this->albumes->artistasPorAlbum(
            array_map(static fn (Album $a): int => (int) $a->id, $albumes)
        );

        return array_map(
            static fn (Album $a): array => [
                'album'    => $a,
                'artistas' => $artistasPorAlbum[$a->id] ?? [],
            ],
            $albumes
        );
    }

    public function obtener(int $id): Album
    {
        return $this->albumes->buscar($id) ?? throw NoEncontradoException::album($id);
    }

    /** @return Artista[] */
    public function artistasDe(int $albumId): array
    {
        $this->obtener($albumId);

        return $this->albumes->artistasPorAlbum([$albumId])[$albumId] ?? [];
    }

    /** @return Artista[] */
    public function artistasDisponibles(int $albumId): array
    {
        return $this->artistas->noVinculadosAlAlbum($albumId);
    }

    /** @param array<string, mixed> $datos */
    public function crear(array $datos): int
    {
        return $this->albumes->crear($this->validar($datos));
    }

    /** @param array<string, mixed> $datos */
    public function actualizar(int $id, array $datos): void
    {
        $this->obtener($id);
        $this->albumes->actualizar($this->validar($datos, $id));
    }

    public function eliminar(int $id): void
    {
        if (!$this->albumes->eliminar($id)) {
            throw NoEncontradoException::album($id);
        }
    }

    /** @return bool false si el artista ya estaba en el álbum */
    public function vincularArtista(int $albumId, int $artistaId): bool
    {
        $this->obtener($albumId);
        if ($this->artistas->buscar($artistaId) === null) {
            throw NoEncontradoException::artista($artistaId);
        }

        return $this->albumes->vincular($albumId, $artistaId);
    }

    public function desvincularArtista(int $albumId, int $artistaId): bool
    {
        return $this->albumes->desvincular($albumId, $artistaId);
    }

    /** @param array<string, mixed> $datos */
    private function validar(array $datos, ?int $id = null): Album
    {
        $errores = [];

        $titulo = Entrada::texto($datos, 'titulo');
        $genero = Entrada::texto($datos, 'genero');
        $discografica = Entrada::texto($datos, 'discografica');
        $anio = filter_var(Entrada::texto($datos, 'anio_lanzamiento'), FILTER_VALIDATE_INT);

        if ($titulo === '') {
            $errores['titulo'] = 'El título es obligatorio.';
        } elseif (mb_strlen($titulo) > 150) {
            $errores['titulo'] = 'El título no puede pasar de 150 caracteres.';
        }

        if ($genero === '') {
            $errores['genero'] = 'El género es obligatorio.';
        } elseif (mb_strlen($genero) > 60) {
            $errores['genero'] = 'El género no puede pasar de 60 caracteres.';
        }

        $anioMaximo = (int) date('Y') + 1; // se permite anunciar el álbum del año que viene
        if ($anio === false || $anio < self::ANIO_MINIMO || $anio > $anioMaximo) {
            $errores['anio_lanzamiento'] = sprintf(
                'Ingresa un año entre %d y %d.',
                self::ANIO_MINIMO,
                $anioMaximo
            );
        }

        if (mb_strlen($discografica) > 100) {
            $errores['discografica'] = 'La discográfica no puede pasar de 100 caracteres.';
        }

        if ($errores !== []) {
            throw new ValidationException($errores);
        }

        return new Album($id, $titulo, $genero, $anio, $discografica === '' ? null : $discografica);
    }
}
