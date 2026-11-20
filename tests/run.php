<?php

declare(strict_types=1);

// Pruebas sin dependencias: php tests/run.php
// Usan SQLite en memoria (hace falta la extensión pdo_sqlite) con el mismo modelo del esquema MySQL.

use Musica\Database;
use Musica\Exception\NoEncontradoException;
use Musica\Exception\ValidationException;
use Musica\Repository\AlbumRepository;
use Musica\Repository\ArtistaRepository;
use Musica\Service\AlbumService;
use Musica\Service\ArtistaService;

require __DIR__ . '/../src/autoload.php';

$total = 0;
$fallos = 0;

function entorno(): array
{
    $pdo = new PDO('sqlite::memory:', null, null, Database::OPCIONES);
    $pdo->exec('PRAGMA foreign_keys = ON'); // SQLite las ignora si no se activan
    $pdo->exec((string) file_get_contents(__DIR__ . '/schema.sqlite.sql'));

    $artistaRepo = new ArtistaRepository($pdo);

    return [
        'pdo'      => $pdo,
        'albumes'  => new AlbumService(new AlbumRepository($pdo), $artistaRepo),
        'artistas' => new ArtistaService($artistaRepo),
    ];
}

function prueba(string $nombre, callable $cuerpo): void
{
    global $total, $fallos;
    $total++;

    try {
        $cuerpo(entorno());
        echo "  ok     $nombre\n";
    } catch (Throwable $e) {
        $fallos++;
        echo "  FALLA  $nombre\n         " . $e->getMessage() . "\n";
    }
}

function afirmar(bool $condicion, string $mensaje): void
{
    if (!$condicion) {
        throw new RuntimeException($mensaje);
    }
}

function afirmarIgual(mixed $esperado, mixed $real, string $mensaje): void
{
    if ($esperado !== $real) {
        throw new RuntimeException(
            $mensaje . ' (esperado ' . var_export($esperado, true) . ', real ' . var_export($real, true) . ')'
        );
    }
}

/** @return array<string, string> errores de validación que lanza $accion */
function erroresDe(callable $accion): array
{
    try {
        $accion();
    } catch (ValidationException $e) {
        return $e->errores;
    }
    throw new RuntimeException('Se esperaba ValidationException y no se lanzó nada');
}

function album(array $cambios = []): array
{
    return array_merge([
        'titulo' => 'Bocanada', 'genero' => 'Rock alternativo',
        'anio_lanzamiento' => '1999', 'discografica' => 'BMG',
    ], $cambios);
}

function artista(array $cambios = []): array
{
    return array_merge([
        'nombre' => 'Gustavo', 'apellido' => 'Cerati',
        'fecha_nacimiento' => '1959-08-11', 'nacionalidad' => 'Argentina',
    ], $cambios);
}

echo "Gestión de música: pruebas\n";

prueba('crea un álbum y lo recupera con sus datos', function (array $e) {
    $id = $e['albumes']->crear(album());
    $a = $e['albumes']->obtener($id);
    afirmarIgual('Bocanada', $a->titulo, 'título');
    afirmarIgual(1999, $a->anioLanzamiento, 'año');
    afirmarIgual('BMG', $a->discografica, 'discográfica');
});

prueba('la discográfica vacía se guarda como NULL', function (array $e) {
    $id = $e['albumes']->crear(album(['discografica' => '  ']));
    afirmarIgual(null, $e['albumes']->obtener($id)->discografica, 'discográfica');
});

prueba('rechaza título vacío y año fuera de rango', function (array $e) {
    $errores = erroresDe(fn () => $e['albumes']->crear(album(['titulo' => ' ', 'anio_lanzamiento' => '1500'])));
    afirmar(isset($errores['titulo']) && isset($errores['anio_lanzamiento']), 'faltan errores: ' . implode(',', array_keys($errores)));
});

prueba('rechaza un año que no es número', function (array $e) {
    $errores = erroresDe(fn () => $e['albumes']->crear(album(['anio_lanzamiento' => '19x9'])));
    afirmar(isset($errores['anio_lanzamiento']), 'debía marcar el año');
});

prueba('trata un campo enviado como arreglo igual que uno vacío', function (array $e) {
    $errores = erroresDe(fn () => $e['albumes']->crear(album(['titulo' => ['x']])));
    afirmar(isset($errores['titulo']), 'debía marcar el título');
});

prueba('crea un artista y valida la fecha de nacimiento', function (array $e) {
    $id = $e['artistas']->crear(artista());
    afirmarIgual('1959-08-11', $e['artistas']->obtener($id)->fechaNacimiento, 'fecha');

    foreach (['2020-02-31', '11/08/1959', '2999-01-01', '1700-01-01'] as $mala) {
        $errores = erroresDe(fn () => $e['artistas']->crear(artista(['fecha_nacimiento' => $mala])));
        afirmar(isset($errores['fecha_nacimiento']), "debía rechazar $mala");
    }
});

prueba('actualiza un álbum y falla con id inexistente', function (array $e) {
    $id = $e['albumes']->crear(album());
    $e['albumes']->actualizar($id, album(['titulo' => 'Bocanada (remaster)']));
    afirmarIgual('Bocanada (remaster)', $e['albumes']->obtener($id)->titulo, 'título nuevo');

    try {
        $e['albumes']->actualizar(999, album());
        afirmar(false, 'debía lanzar NoEncontradoException');
    } catch (NoEncontradoException) {
    }
});

prueba('vincula artistas y el listado los trae agrupados', function (array $e) {
    $album = $e['albumes']->crear(album());
    $cerati = $e['artistas']->crear(artista());
    $garcia = $e['artistas']->crear(artista(['nombre' => 'Charly', 'apellido' => 'García']));
    $e['albumes']->vincularArtista($album, $cerati);
    $e['albumes']->vincularArtista($album, $garcia);

    $lista = $e['albumes']->listarConArtistas();
    afirmarIgual(1, count($lista), 'cantidad de álbumes');
    afirmarIgual(
        ['Gustavo Cerati', 'Charly García'],
        array_map(fn ($a) => $a->nombreCompleto(), $lista[0]['artistas']),
        'artistas ordenados por apellido'
    );
});

prueba('vincular dos veces no duplica ni lanza error', function (array $e) {
    $album = $e['albumes']->crear(album());
    $art = $e['artistas']->crear(artista());
    afirmarIgual(true, $e['albumes']->vincularArtista($album, $art), 'primer vínculo');
    afirmarIgual(false, $e['albumes']->vincularArtista($album, $art), 'segundo vínculo');
    afirmarIgual(1, count($e['albumes']->artistasDe($album)), 'artistas del álbum');
});

prueba('no vincula con álbum o artista inexistentes', function (array $e) {
    $album = $e['albumes']->crear(album());
    $art = $e['artistas']->crear(artista());

    foreach ([[999, $art], [$album, 999]] as [$a, $b]) {
        try {
            $e['albumes']->vincularArtista($a, $b);
            afirmar(false, "debía fallar con ($a, $b)");
        } catch (NoEncontradoException) {
        }
    }
});

prueba('desvincular quita solo la relación', function (array $e) {
    $album = $e['albumes']->crear(album());
    $art = $e['artistas']->crear(artista());
    $e['albumes']->vincularArtista($album, $art);

    afirmarIgual(true, $e['albumes']->desvincularArtista($album, $art), 'desvincular');
    afirmarIgual(0, count($e['albumes']->artistasDe($album)), 'sin artistas');
    afirmarIgual('Cerati', $e['artistas']->obtener($art)->apellido, 'el artista sigue existiendo');
});

prueba('eliminar un álbum borra sus vínculos y conserva al artista', function (array $e) {
    $album = $e['albumes']->crear(album());
    $art = $e['artistas']->crear(artista());
    $e['albumes']->vincularArtista($album, $art);
    $e['albumes']->eliminar($album);

    afirmarIgual(0, (int) $e['pdo']->query('SELECT COUNT(*) FROM Album_Artista')->fetchColumn(), 'vínculos restantes');
    afirmarIgual(0, count($e['artistas']->albumesDe($art)), 'álbumes del artista');
    afirmarIgual('Cerati', $e['artistas']->obtener($art)->apellido, 'el artista sigue existiendo');
});

prueba('eliminar un artista borra sus vínculos y conserva el álbum', function (array $e) {
    $album = $e['albumes']->crear(album());
    $art = $e['artistas']->crear(artista());
    $e['albumes']->vincularArtista($album, $art);
    $e['artistas']->eliminar($art);

    afirmarIgual(0, count($e['albumes']->artistasDe($album)), 'artistas del álbum');
    afirmarIgual('Bocanada', $e['albumes']->obtener($album)->titulo, 'el álbum sigue existiendo');
});

prueba('un artista ve todos sus álbumes, del más reciente al más antiguo', function (array $e) {
    $art = $e['artistas']->crear(artista());
    $viejo = $e['albumes']->crear(album(['titulo' => 'Amor amarillo', 'anio_lanzamiento' => '1993']));
    $nuevo = $e['albumes']->crear(album(['titulo' => 'Siempre es hoy', 'anio_lanzamiento' => '2002']));
    $e['albumes']->vincularArtista($viejo, $art);
    $e['albumes']->vincularArtista($nuevo, $art);

    afirmarIgual(
        ['Siempre es hoy', 'Amor amarillo'],
        array_map(fn ($a) => $a->titulo, $e['artistas']->albumesDe($art)),
        'orden'
    );
});

prueba('artistasDisponibles excluye a los que ya están en el álbum', function (array $e) {
    $album = $e['albumes']->crear(album());
    $dentro = $e['artistas']->crear(artista());
    $fuera = $e['artistas']->crear(artista(['nombre' => 'Fito', 'apellido' => 'Páez']));
    $e['albumes']->vincularArtista($album, $dentro);

    $ids = array_map(fn ($a) => $a->id, $e['albumes']->artistasDisponibles($album));
    afirmarIgual([$fuera], $ids, 'disponibles');
});

prueba('la búsqueda trata % y _ como texto literal', function (array $e) {
    $e['albumes']->crear(album(['titulo' => 'Cien por ciento']));
    $e['albumes']->crear(album(['titulo' => 'Oferta 100% rock']));

    afirmarIgual(1, count($e['albumes']->listarConArtistas('100%')), 'búsqueda "100%"');
    afirmarIgual(0, count($e['albumes']->listarConArtistas('%%%')), 'búsqueda "%%%" no debe traer todo');
    afirmarIgual(2, count($e['albumes']->listarConArtistas('')), 'sin búsqueda trae todo');
});

echo sprintf("\n%d pruebas, %d con fallas\n", $total, $fallos);
exit($fallos === 0 ? 0 : 1);
