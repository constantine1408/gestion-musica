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

<div class="ficha">
    <section aria-label="Datos del álbum">
        <dl class="creditos">
            <div><dt>Género</dt><dd><?= e($album->genero) ?></dd></div>
            <div><dt>Año</dt><dd><?= $album->anioLanzamiento ?></dd></div>
            <div><dt>Discográfica</dt><dd><?= e($album->discografica) ?: '<span class="tenue">Sin dato</span>' ?></dd></div>
            <div><dt>Catálogo</dt><dd class="catalogo"><?= numero_catalogo((int) $album->id) ?></dd></div>
        </dl>
    </section>

    <section aria-labelledby="t-artistas">
        <h2 id="t-artistas">Artistas</h2>
        <?php if ($artistas === []): ?>
            <p class="vacio">Este álbum todavía no tiene artistas.</p>
        <?php else: ?>
            <?php foreach ($artistas as $artista): ?>
                <div class="vinculo">
                    <a href="/artistas/<?= $artista->id ?>"><?= e($artista->nombreCompleto()) ?></a>
                    <span class="meta"><?= e($artista->nacionalidad) ?: '-' ?></span>
                    <form method="post" action="/albumes/<?= $album->id ?>/artistas/<?= $artista->id ?>/quitar">
                        <?= campo_csrf() ?>
                        <button type="submit" class="sec chico" aria-label="Quitar a <?= e($artista->nombreCompleto()) ?> de este álbum">Quitar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <h2 id="t-agregar">Agregar artista</h2>
        <?php if ($disponibles === []): ?>
            <p class="vacio">No quedan artistas por agregar. <a href="/artistas/nuevo">Crear un artista nuevo</a>.</p>
        <?php else: ?>
            <form class="agregar" method="post" action="/albumes/<?= $album->id ?>/artistas">
                <?= campo_csrf() ?>
                <select name="artista_id" required aria-labelledby="t-agregar">
                    <option value="">Elige un artista</option>
                    <?php foreach ($disponibles as $artista): ?>
                        <option value="<?= $artista->id ?>"><?= e($artista->nombreCompleto()) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Agregar</button>
            </form>
        <?php endif; ?>
    </section>
</div>
