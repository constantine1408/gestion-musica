<?php

declare(strict_types=1);

namespace Musica\Support;

final class Sesion
{
    public static function tokenCsrf(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf'];
    }

    public static function csrfValido(mixed $token): bool
    {
        return is_string($token)
            && isset($_SESSION['csrf'])
            && hash_equals($_SESSION['csrf'], $token);
    }

    public static function flash(string $tipo, string $mensaje): void
    {
        $_SESSION['flash'][] = [$tipo, $mensaje];
    }

    /** @return array<int, array{0: string, 1: string}> */
    public static function tomarFlash(): array
    {
        $mensajes = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $mensajes;
    }
}
