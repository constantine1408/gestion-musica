<?php
/** @var array<int, array{album: \Musica\Domain\Album, artistas: \Musica\Domain\Artista[]}> $items */
/** @var string $q */
$tituloPagina = 'Álbumes';
?>
<div class="barra">
    <h1>Álbumes</h1>
    <a class="boton" href="/albumes/nuevo">Nuevo álbum</a>
</div>

<form class="busqueda" method="get" action="/albumes" role="search">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar por título, género o discográfica" aria-label="Buscar álbumes">
    <button type="submit" class="sec">Buscar</button>
</form>

<?php if ($items === []): ?>
    <p class="vacio"><?= $q !== '' ? 'Ningún álbum coincide con la búsqueda.' : 'Todavía no hay álbumes registrados.' ?></p>
<?php else: ?>
    <div class="lados">
        <?php foreach (lados($items) as $lado => $pistas): if ($pistas === []) continue; ?>
            <section class="lado" aria-label="Lado <?= $lado ?>">
                <h2>Lado <?= $lado ?></h2>
                <ol class="pistas">
                    <?php foreach ($pistas as $i => $item): $album = $item['album']; ?>
                        <li class="pista">
                            <span class="n"><?= $lado . ($i + 1) ?></span>
                            <a class="t" href="/albumes/<?= $album->id ?>"><?= e($album->titulo) ?></a>
                            <span class="meta">
                                <?php if ($item['artistas'] === []): ?>
                                    <span class="tenue">Sin artistas</span>
                                <?php else: ?>
                                    <?= e(implode(', ', array_map(static fn ($a) => $a->nombreCompleto(), $item['artistas']))) ?>
                                <?php endif; ?>
                                — <?= e($album->genero) ?><?= $album->discografica ? ' · ' . e($album->discografica) : '' ?>
                            </span>
                            <span class="der">
                                <b><?= $album->anioLanzamiento ?></b>
                                <span class="catalogo"><?= numero_catalogo((int) $album->id) ?></span>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
