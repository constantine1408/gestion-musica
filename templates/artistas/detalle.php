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

<div class="ficha">
    <section aria-label="Datos del artista">
        <dl class="creditos">
            <div><dt>Nacimiento</dt><dd><?= e($artista->fechaNacimiento) ?: '<span class="tenue">Sin dato</span>' ?></dd></div>
            <div><dt>Nacionalidad</dt><dd><?= e($artista->nacionalidad) ?: '<span class="tenue">Sin dato</span>' ?></dd></div>
        </dl>
    </section>

    <section aria-labelledby="t-albumes">
        <h2 id="t-albumes">Álbumes</h2>
        <?php if ($albumes === []): ?>
            <p class="vacio">Todavía no está vinculado a ningún álbum. Se agrega desde la ficha del álbum.</p>
        <?php else: ?>
            <ol class="pistas">
                <?php foreach ($albumes as $i => $album): ?>
                    <li class="pista">
                        <span class="n"><?= $i + 1 ?></span>
                        <a class="t" href="/albumes/<?= $album->id ?>"><?= e($album->titulo) ?></a>
                        <span class="meta"><?= e($album->genero) ?></span>
                        <span class="der">
                            <b><?= $album->anioLanzamiento ?></b>
                            <span class="catalogo"><?= numero_catalogo((int) $album->id) ?></span>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </section>
</div>
