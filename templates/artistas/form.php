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

    <label>Nombre
        <input type="text" name="nombre" maxlength="80" required value="<?= $valor('nombre') ?>">
        <?= mensaje_error($errores, 'nombre') ?>
    </label>

    <label>Apellido
        <input type="text" name="apellido" maxlength="80" required value="<?= $valor('apellido') ?>">
        <?= mensaje_error($errores, 'apellido') ?>
    </label>

    <label>Fecha de nacimiento (opcional)
        <input type="date" name="fecha_nacimiento" max="<?= date('Y-m-d') ?>" value="<?= $valor('fecha_nacimiento') ?>">
        <?= mensaje_error($errores, 'fecha_nacimiento') ?>
    </label>

    <label>Nacionalidad (opcional)
        <input type="text" name="nacionalidad" maxlength="60" value="<?= $valor('nacionalidad') ?>">
        <?= mensaje_error($errores, 'nacionalidad') ?>
    </label>

    <div class="acciones">
        <button type="submit">Guardar</button>
        <a class="boton sec" href="/artistas">Cancelar</a>
    </div>
</form>
