<?php

declare(strict_types=1);

namespace Musica;

use PDO;

final class Database
{
    public const OPCIONES = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Prepared statements reales: el valor nunca se mezcla con el SQL.
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    /** @param array{host:string, port:int, name:string, user:string, pass:string} $db */
    public static function conectar(array $db): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $db['host'],
            $db['port'],
            $db['name']
        );

        return new PDO($dsn, $db['user'], $db['pass'], self::OPCIONES);
    }
}
