<?php

declare(strict_types=1);

use Musica\Controller\AlbumController;
use Musica\Controller\ArtistaController;
use Musica\Database;
use Musica\Exception\NoEncontradoException;
use Musica\Http\Router;
use Musica\Repository\AlbumRepository;
use Musica\Repository\ArtistaRepository;
use Musica\Service\AlbumService;
use Musica\Service\ArtistaService;
use Musica\Support\Sesion;
use Musica\Support\View;

// Con `php -S`, deja que el servidor entregue los archivos reales de public/ (CSS, fuentes),
// igual que hace el .htaccess en Apache.
if (PHP_SAPI === 'cli-server') {
    $archivo = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
    if ($archivo !== false && is_file($archivo) && str_starts_with($archivo, __DIR__ . DIRECTORY_SEPARATOR)) {
        return false;
    }
}

require __DIR__ . '/../src/autoload.php';

$config = require __DIR__ . '/../config/config.php';

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

try {
    $pdo = Database::conectar($config['db']);

    $artistaRepo = new ArtistaRepository($pdo);
    $albumRepo = new AlbumRepository($pdo);
    $albumService = new AlbumService($albumRepo, $artistaRepo);
    $artistaService = new ArtistaService($artistaRepo);

    $albumes = new AlbumController($albumService);
    $artistas = new ArtistaController($artistaService);

    $router = new Router();
    $router->agregar('GET', '/', static fn () => View::redirigir('/albumes'));

    $router->agregar('GET', '/albumes', static fn () => $albumes->index($_GET));
    $router->agregar('GET', '/albumes/nuevo', static fn () => $albumes->nuevo());
    $router->agregar('POST', '/albumes', static fn () => $albumes->crear($_POST));
    $router->agregar('GET', '/albumes/(\d+)', static fn (int $id) => $albumes->detalle($id));
    $router->agregar('GET', '/albumes/(\d+)/editar', static fn (int $id) => $albumes->editar($id));
    $router->agregar('POST', '/albumes/(\d+)', static fn (int $id) => $albumes->actualizar($id, $_POST));
    $router->agregar('POST', '/albumes/(\d+)/eliminar', static fn (int $id) => $albumes->eliminar($id));
    $router->agregar('POST', '/albumes/(\d+)/artistas', static fn (int $id) => $albumes->vincularArtista($id, $_POST));
    $router->agregar('POST', '/albumes/(\d+)/artistas/(\d+)/quitar', static fn (int $id, int $artistaId) => $albumes->desvincularArtista($id, $artistaId));

    $router->agregar('GET', '/artistas', static fn () => $artistas->index($_GET));
    $router->agregar('GET', '/artistas/nuevo', static fn () => $artistas->nuevo());
    $router->agregar('POST', '/artistas', static fn () => $artistas->crear($_POST));
    $router->agregar('GET', '/artistas/(\d+)', static fn (int $id) => $artistas->detalle($id));
    $router->agregar('GET', '/artistas/(\d+)/editar', static fn (int $id) => $artistas->editar($id));
    $router->agregar('POST', '/artistas/(\d+)', static fn (int $id) => $artistas->actualizar($id, $_POST));
    $router->agregar('POST', '/artistas/(\d+)/eliminar', static fn (int $id) => $artistas->eliminar($id));

    $metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $ruta = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

    // Todo POST cambia datos, así que exige el token de la sesión.
    if ($metodo === 'POST' && !Sesion::csrfValido($_POST['_token'] ?? null)) {
        View::render('error', [
            'codigo'  => 419,
            'mensaje' => 'La sesión del formulario expiró. Recarga la página e inténtalo otra vez.',
        ], 419);
        return;
    }

    $router->despachar($metodo, $ruta);
} catch (NoEncontradoException $e) {
    View::render('error', ['codigo' => 404, 'mensaje' => $e->getMessage()], 404);
} catch (Throwable $e) {
    error_log((string) $e);
    View::render('error', [
        'codigo'  => 500,
        'mensaje' => $config['debug'] ? $e->getMessage() : 'Algo salió mal de nuestro lado. Inténtalo de nuevo en un momento.',
    ], 500);
}
