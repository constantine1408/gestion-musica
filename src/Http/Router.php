<?php

declare(strict_types=1);

namespace Musica\Http;

use Musica\Exception\NoEncontradoException;

final class Router
{
    /** @var array<int, array{0: string, 1: string, 2: callable}> */
    private array $rutas = [];

    /**
     * El patrón es una expresión regular sin delimitadores. Cada grupo de
     * captura llega al manejador como entero, por eso las rutas usan (\d+).
     */
    public function agregar(string $metodo, string $patron, callable $manejador): void
    {
        $this->rutas[] = [$metodo, '#^' . $patron . '$#', $manejador];
    }

    public function despachar(string $metodo, string $ruta): void
    {
        $ruta = $ruta === '/' ? '/' : rtrim($ruta, '/');

        foreach ($this->rutas as [$metodoRuta, $regex, $manejador]) {
            if ($metodoRuta === $metodo && preg_match($regex, $ruta, $coincidencias) === 1) {
                array_shift($coincidencias);
                $manejador(...array_map('intval', $coincidencias));

                return;
            }
        }

        throw NoEncontradoException::ruta($ruta);
    }
}
