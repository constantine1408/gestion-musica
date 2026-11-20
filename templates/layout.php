<?php

use Musica\Support\Sesion;

/** @var string $contenido */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(($tituloPagina ?? 'Catálogo') . ' · Gestión de música') ?></title>
    <style>
        :root { --borde: #d9d9d9; --texto: #1f2328; --tenue: #6b7280; --acento: #1f5fbf; --error: #b42318; --ok: #1a7f37; }
        * { box-sizing: border-box; }
        body { font: 16px/1.5 system-ui, sans-serif; color: var(--texto); margin: 0; background: #fafafa; }
        header { background: #fff; border-bottom: 1px solid var(--borde); }
        nav, main { max-width: 960px; margin: 0 auto; padding: 0 16px; }
        nav { display: flex; gap: 20px; align-items: center; height: 52px; }
        nav .marca { font-weight: 600; margin-right: auto; }
        nav a { text-decoration: none; color: var(--texto); }
        nav a:hover { color: var(--acento); }
        main { padding-top: 24px; padding-bottom: 48px; }
        a { color: var(--acento); }
        h1 { font-size: 1.5rem; margin: 0; }
        .barra { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; flex-wrap: wrap; }
        .boton, button { display: inline-block; font: inherit; padding: 6px 14px; border: 1px solid var(--acento); background: var(--acento); color: #fff; border-radius: 6px; text-decoration: none; cursor: pointer; }
        .boton.sec, button.sec { background: #fff; color: var(--acento); }
        button.peligro { background: #fff; color: var(--error); border-color: var(--error); }
        table { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid var(--borde); }
        th, td { text-align: left; padding: 8px 12px; border-bottom: 1px solid var(--borde); vertical-align: top; }
        th { font-size: .85rem; color: var(--tenue); font-weight: 600; }
        .tenue, .vacio { color: var(--tenue); }
        .busqueda { display: flex; gap: 8px; margin-bottom: 16px; }
        input, select { font: inherit; padding: 6px 10px; border: 1px solid var(--borde); border-radius: 6px; background: #fff; }
        .busqueda input { flex: 1; }
        form.datos { background: #fff; border: 1px solid var(--borde); border-radius: 8px; padding: 20px; max-width: 520px; }
        form.datos label { display: block; margin-bottom: 14px; font-weight: 500; }
        form.datos input { display: block; width: 100%; margin-top: 4px; font-weight: 400; }
        .error { display: block; color: var(--error); font-size: .875rem; font-weight: 400; margin-top: 2px; }
        .aviso { padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; border: 1px solid; }
        .aviso.ok { color: var(--ok); border-color: var(--ok); background: #f0fdf4; }
        .aviso.error { color: var(--error); border-color: var(--error); background: #fef3f2; }
        .acciones { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .acciones form { margin: 0; }
        dl { display: grid; grid-template-columns: 140px 1fr; gap: 4px 12px; margin: 0 0 24px; }
        dt { color: var(--tenue); }
        dd { margin: 0; }
        h2 { font-size: 1.15rem; margin: 28px 0 10px; }
    </style>
</head>
<body>
<header>
    <nav>
        <span class="marca">Gestión de música</span>
        <a href="/albumes">Álbumes</a>
        <a href="/artistas">Artistas</a>
    </nav>
</header>
<main>
    <?php foreach (Sesion::tomarFlash() as [$tipo, $mensaje]): ?>
        <div class="aviso <?= e($tipo) ?>" role="status"><?= e($mensaje) ?></div>
    <?php endforeach; ?>
    <?= $contenido ?>
</main>
</body>
</html>
