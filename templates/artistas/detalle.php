<?php
/** @var \Musica\Domain\Artista $artista */
/** @var \Musica\Domain\Album[] $albumes */
$tituloPagina = $artista->nombreCompleto();
?>
<div class="barra">
    <h1><?= e($artista->nombreCompleto()) ?></h1>
    <div class="acciones">
        <a class="boton sec" href="/artistas/<?= $artista->id ?>/editar">Editar</a>
        <form method="post" action="/artistas/<?= $artista->id ?>/eliminar"
              onsubmit="return confirm('¿Eliminar este artista? Sus álbumes se conservan.');">
            <?= campo_csrf() ?>
            <button type="submit" class="peligro">Eliminar</button>
        </form>
    </div>
</div>

<dl>
    <dt>Nacimiento</dt><dd><?= e($artista->fechaNacimiento) ?: '<span class="tenue">Sin dato</span>' ?></dd>
    <dt>Nacionalidad</dt><dd><?= e($artista->nacionalidad) ?: '<span class="tenue">Sin dato</span>' ?></dd>
</dl>

<h2>Álbumes</h2>
<?php if ($albumes === []): ?>
    <p class="vacio">Todavía no está vinculado a ningún álbum. Se agrega desde la ficha del álbum.</p>
<?php else: ?>
    <table>
        <thead><tr><th>Título</th><th>Género</th><th>Año</th></tr></thead>
        <tbody>
        <?php foreach ($albumes as $album): ?>
            <tr>
                <td><a href="/albumes/<?= $album->id ?>"><?= e($album->titulo) ?></a></td>
                <td><?= e($album->genero) ?></td>
                <td><?= $album->anioLanzamiento ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
