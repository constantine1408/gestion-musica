<?php
/** @var \Musica\Domain\Album $album */
/** @var \Musica\Domain\Artista[] $artistas */
/** @var \Musica\Domain\Artista[] $disponibles */
$tituloPagina = $album->titulo;
?>
<div class="barra">
    <h1><?= e($album->titulo) ?></h1>
    <div class="acciones">
        <a class="boton sec" href="/albumes/<?= $album->id ?>/editar">Editar</a>
        <form method="post" action="/albumes/<?= $album->id ?>/eliminar"
              onsubmit="return confirm('¿Eliminar este álbum? Los artistas no se borran.');">
            <?= campo_csrf() ?>
            <button type="submit" class="peligro">Eliminar</button>
        </form>
    </div>
</div>

<dl>
    <dt>Género</dt><dd><?= e($album->genero) ?></dd>
    <dt>Año</dt><dd><?= $album->anioLanzamiento ?></dd>
    <dt>Discográfica</dt><dd><?= e($album->discografica) ?: '<span class="tenue">Sin dato</span>' ?></dd>
</dl>

<h2>Artistas</h2>
<?php if ($artistas === []): ?>
    <p class="vacio">Este álbum todavía no tiene artistas.</p>
<?php else: ?>
    <table>
        <thead><tr><th>Nombre</th><th>Nacionalidad</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($artistas as $artista): ?>
            <tr>
                <td><a href="/artistas/<?= $artista->id ?>"><?= e($artista->nombreCompleto()) ?></a></td>
                <td><?= e($artista->nacionalidad) ?: '<span class="tenue">-</span>' ?></td>
                <td>
                    <form method="post" action="/albumes/<?= $album->id ?>/artistas/<?= $artista->id ?>/quitar">
                        <?= campo_csrf() ?>
                        <button type="submit" class="sec">Quitar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<h2>Agregar artista</h2>
<?php if ($disponibles === []): ?>
    <p class="vacio">No quedan artistas por agregar. <a href="/artistas/nuevo">Crear un artista nuevo</a>.</p>
<?php else: ?>
    <form class="acciones" method="post" action="/albumes/<?= $album->id ?>/artistas">
        <?= campo_csrf() ?>
        <select name="artista_id" required>
            <option value="">Elige un artista</option>
            <?php foreach ($disponibles as $artista): ?>
                <option value="<?= $artista->id ?>"><?= e($artista->nombreCompleto()) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Agregar</button>
    </form>
<?php endif; ?>
