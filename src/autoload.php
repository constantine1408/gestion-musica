<?php

declare(strict_types=1);

// Autoload mínimo para el namespace Musica\ (equivale a PSR-4 apuntando a src/).
// Si más adelante el proyecto usa Composer, basta con declarar ese mapeo.
spl_autoload_register(static function (string $clase): void {
    $prefijo = 'Musica\\';
    if (!str_starts_with($clase, $prefijo)) {
        return;
    }
    $ruta = __DIR__ . '/' . str_replace('\\', '/', substr($clase, strlen($prefijo))) . '.php';
    if (is_file($ruta)) {
        require $ruta;
    }
});

require_once __DIR__ . '/Support/helpers.php';
