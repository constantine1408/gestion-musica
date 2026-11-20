<?php

declare(strict_types=1);

// Todo sale de variables de entorno para no dejar credenciales en el repositorio.
return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'gestion_musica',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
    ],
    // Con APP_DEBUG=1 las páginas de error muestran el mensaje real.
    'debug' => filter_var(getenv('APP_DEBUG') ?: '0', FILTER_VALIDATE_BOOLEAN),
];
