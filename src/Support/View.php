<?php

declare(strict_types=1);

namespace Musica\Support;

final class View
{
    /**
     * Renderiza templates/<plantilla>.php dentro del layout.
     * El nombre de la plantilla siempre viene del código, nunca del request.
     *
     * @param array<string, mixed> $datos
     */
    public static function render(string $plantilla, array $datos = [], int $estado = 200): void
    {
        $raiz = dirname(__DIR__, 2) . '/templates';
        http_response_code($estado);

        extract($datos, EXTR_SKIP);
        ob_start();
        require $raiz . '/' . $plantilla . '.php';
        $contenido = ob_get_clean();
        require $raiz . '/layout.php';
    }

    public static function redirigir(string $ruta): never
    {
        header('Location: ' . $ruta, true, 303);
        exit;
    }
}
