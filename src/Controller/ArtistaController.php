<?php

declare(strict_types=1);

namespace Musica\Controller;

use Musica\Exception\ValidationException;
use Musica\Service\ArtistaService;
use Musica\Support\Entrada;
use Musica\Support\Sesion;
use Musica\Support\View;

final class ArtistaController
{
    public function __construct(private readonly ArtistaService $artistas)
    {
    }

    /** @param array<string, mixed> $query */
    public function index(array $query): void
    {
        $busqueda = Entrada::texto($query, 'q');

        View::render('artistas/index', [
            'artistas' => $this->artistas->listar($busqueda),
            'q'        => $busqueda,
        ]);
    }

    public function nuevo(): void
    {
        View::render('artistas/form', [
            'encabezado' => 'Nuevo artista',
            'accion'     => '/artistas',
            'valores'    => [],
            'errores'    => [],
        ]);
    }

    /** @param array<string, mixed> $post */
    public function crear(array $post): void
    {
        try {
            $id = $this->artistas->crear($post);
        } catch (ValidationException $e) {
            View::render('artistas/form', [
                'encabezado' => 'Nuevo artista',
                'accion'     => '/artistas',
                'valores'    => $post,
                'errores'    => $e->errores,
            ], 422);

            return;
        }

        Sesion::flash('ok', 'Artista creado.');
        View::redirigir("/artistas/$id");
    }

    public function detalle(int $id): void
    {
        View::render('artistas/detalle', [
            'artista' => $this->artistas->obtener($id),
            'albumes' => $this->artistas->albumesDe($id),
        ]);
    }

    public function editar(int $id): void
    {
        $artista = $this->artistas->obtener($id);

        View::render('artistas/form', [
            'encabezado' => 'Editar artista',
            'accion'     => "/artistas/$id",
            'valores'    => [
                'nombre'           => $artista->nombre,
                'apellido'         => $artista->apellido,
                'fecha_nacimiento' => $artista->fechaNacimiento ?? '',
                'nacionalidad'     => $artista->nacionalidad ?? '',
            ],
            'errores'    => [],
        ]);
    }

    /** @param array<string, mixed> $post */
    public function actualizar(int $id, array $post): void
    {
        try {
            $this->artistas->actualizar($id, $post);
        } catch (ValidationException $e) {
            View::render('artistas/form', [
                'encabezado' => 'Editar artista',
                'accion'     => "/artistas/$id",
                'valores'    => $post,
                'errores'    => $e->errores,
            ], 422);

            return;
        }

        Sesion::flash('ok', 'Cambios guardados.');
        View::redirigir("/artistas/$id");
    }

    public function eliminar(int $id): void
    {
        $this->artistas->eliminar($id);
        Sesion::flash('ok', 'Artista eliminado. Sus álbumes siguen en el catálogo.');
        View::redirigir('/artistas');
    }
}
