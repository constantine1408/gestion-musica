<?php

declare(strict_types=1);

namespace Musica\Controller;

use Musica\Exception\ValidationException;
use Musica\Service\AlbumService;
use Musica\Support\Entrada;
use Musica\Support\Sesion;
use Musica\Support\View;

final class AlbumController
{
    public function __construct(private readonly AlbumService $albumes)
    {
    }

    /** @param array<string, mixed> $query */
    public function index(array $query): void
    {
        $busqueda = Entrada::texto($query, 'q');

        View::render('albumes/index', [
            'items' => $this->albumes->listarConArtistas($busqueda),
            'q'     => $busqueda,
        ]);
    }

    public function nuevo(): void
    {
        View::render('albumes/form', [
            'encabezado' => 'Nuevo álbum',
            'accion'     => '/albumes',
            'valores'    => [],
            'errores'    => [],
        ]);
    }

    /** @param array<string, mixed> $post */
    public function crear(array $post): void
    {
        try {
            $id = $this->albumes->crear($post);
        } catch (ValidationException $e) {
            View::render('albumes/form', [
                'encabezado' => 'Nuevo álbum',
                'accion'     => '/albumes',
                'valores'    => $post,
                'errores'    => $e->errores,
            ], 422);

            return;
        }

        Sesion::flash('ok', 'Álbum creado. Ya puedes agregarle artistas.');
        View::redirigir("/albumes/$id");
    }

    public function detalle(int $id): void
    {
        View::render('albumes/detalle', [
            'album'       => $this->albumes->obtener($id),
            'artistas'    => $this->albumes->artistasDe($id),
            'disponibles' => $this->albumes->artistasDisponibles($id),
        ]);
    }

    public function editar(int $id): void
    {
        $album = $this->albumes->obtener($id);

        View::render('albumes/form', [
            'encabezado' => 'Editar álbum',
            'accion'     => "/albumes/$id",
            'valores'    => [
                'titulo'           => $album->titulo,
                'genero'           => $album->genero,
                'anio_lanzamiento' => (string) $album->anioLanzamiento,
                'discografica'     => $album->discografica ?? '',
            ],
            'errores'    => [],
        ]);
    }

    /** @param array<string, mixed> $post */
    public function actualizar(int $id, array $post): void
    {
        try {
            $this->albumes->actualizar($id, $post);
        } catch (ValidationException $e) {
            View::render('albumes/form', [
                'encabezado' => 'Editar álbum',
                'accion'     => "/albumes/$id",
                'valores'    => $post,
                'errores'    => $e->errores,
            ], 422);

            return;
        }

        Sesion::flash('ok', 'Cambios guardados.');
        View::redirigir("/albumes/$id");
    }

    public function eliminar(int $id): void
    {
        $this->albumes->eliminar($id);
        Sesion::flash('ok', 'Álbum eliminado.');
        View::redirigir('/albumes');
    }

    /** @param array<string, mixed> $post */
    public function vincularArtista(int $albumId, array $post): void
    {
        $artistaId = filter_var(Entrada::texto($post, 'artista_id'), FILTER_VALIDATE_INT);

        if ($artistaId === false) {
            Sesion::flash('error', 'Elige un artista de la lista.');
        } elseif ($this->albumes->vincularArtista($albumId, $artistaId)) {
            Sesion::flash('ok', 'Artista agregado al álbum.');
        } else {
            Sesion::flash('error', 'Ese artista ya estaba en el álbum.');
        }

        View::redirigir("/albumes/$albumId");
    }

    public function desvincularArtista(int $albumId, int $artistaId): void
    {
        $this->albumes->desvincularArtista($albumId, $artistaId);
        Sesion::flash('ok', 'Artista quitado del álbum.');
        View::redirigir("/albumes/$albumId");
    }
}
