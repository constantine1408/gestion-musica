<?php
/** @var string $encabezado */
/** @var string $accion */
/** @var array<string, mixed> $valores */
/** @var array<string, string> $errores */
$tituloPagina = $encabezado;
$valor = static fn (string $campo): string => e(is_string($valores[$campo] ?? null) ? $valores[$campo] : '');
?>
<div class="barra"><h1><?= e($encabezado) ?></h1></div>

<form class="datos" method="post" action="<?= e($accion) ?>" novalidate>
    <?= campo_csrf() ?>

    <label>Título
        <input type="text" name="titulo" maxlength="150" required value="<?= $valor('titulo') ?>">
        <?= mensaje_error($errores, 'titulo') ?>
    </label>

    <label>Género
        <input type="text" name="genero" maxlength="60" required value="<?= $valor('genero') ?>">
        <?= mensaje_error($errores, 'genero') ?>
    </label>

    <label>Año de lanzamiento
        <input type="number" name="anio_lanzamiento" min="1877" max="<?= (int) date('Y') + 1 ?>" required value="<?= $valor('anio_lanzamiento') ?>">
        <?= mensaje_error($errores, 'anio_lanzamiento') ?>
    </label>

    <label>Discográfica (opcional)
        <input type="text" name="discografica" maxlength="100" value="<?= $valor('discografica') ?>">
        <?= mensaje_error($errores, 'discografica') ?>
    </label>

    <div class="acciones">
        <button type="submit">Guardar</button>
        <a class="boton sec" href="/albumes">Cancelar</a>
    </div>
</form>
