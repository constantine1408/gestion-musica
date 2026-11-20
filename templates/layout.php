<?php

use Musica\Support\Sesion;

/** @var string $contenido */
$rutaActual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$actual = static fn (string $seccion): string => str_starts_with($rutaActual, $seccion) ? ' aria-current="page"' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title><?= e(($tituloPagina ?? 'Catálogo') . ' · Gestión de música') ?></title>
    <link rel="preload" href="/fonts/archivo-latin.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
<a class="saltar" href="#contenido">Saltar al contenido</a>
<header class="cabecera">
    <nav aria-label="Principal">
        <a class="marca" href="/albumes">
            <svg class="disco" viewBox="0 0 32 32" aria-hidden="true">
                <circle cx="16" cy="16" r="15.5" fill="#050505" stroke="#3d3d3b"/>
                <circle cx="16" cy="16" r="12.5" fill="none" stroke="#262625" stroke-width=".8"/>
                <circle cx="16" cy="16" r="10" fill="none" stroke="#262625" stroke-width=".8"/>
                <circle cx="16" cy="16" r="6.5" fill="#e2b33c"/>
                <path d="M16 10.5a5.5 5.5 0 0 1 5.5 5.5" fill="none" stroke="#141414" stroke-width="1.2" stroke-linecap="round"/>
                <circle cx="16" cy="16" r="1.4" fill="#141414"/>
            </svg>
            <span>Gestión de música</span>
        </a>
        <a href="/albumes"<?= $actual('/albumes') ?>>Álbumes</a>
        <a href="/artistas"<?= $actual('/artistas') ?>>Artistas</a>
    </nav>
</header>
<main id="contenido">
    <?php foreach (Sesion::tomarFlash() as [$tipo, $mensaje]): ?>
        <div class="aviso <?= e($tipo) ?>" role="status"><?= e($mensaje) ?></div>
    <?php endforeach; ?>
    <?= $contenido ?>
</main>
</body>
</html>
