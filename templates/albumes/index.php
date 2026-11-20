<?php
/** @var array<int, array{album: \Musica\Domain\Album, artistas: \Musica\Domain\Artista[]}> $items */
/** @var string $q */
$tituloPagina = 'Álbumes';
?>
<div class="barra">
    <h1>Álbumes</h1>
    <a class="boton" href="/albumes/nuevo">Nuevo álbum</a>
</div>

<form class="busqueda" method="get" action="/albumes">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar por título, género o discográfica">
    <button type="submit" class="sec">Buscar</button>
</form>

<?php if ($items === []): ?>
    <p class="vacio"><?= $q !== '' ? 'Ningún álbum coincide con la búsqueda.' : 'Todavía no hay álbumes registrados.' ?></p>
<?php else: ?>
    <table>
        <thead>
            <tr><th>Título</th><th>Artistas</th><th>Género</th><th>Año</th><th>Discográfica</th></tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): $album = $item['album']; ?>
            <tr>
                <td><a href="/albumes/<?= $album->id ?>"><?= e($album->titulo) ?></a></td>
                <td>
                    <?php if ($item['artistas'] === []): ?>
                        <span class="tenue">Sin artistas</span>
                    <?php else: ?>
                        <?= e(implode(', ', array_map(static fn ($a) => $a->nombreCompleto(), $item['artistas']))) ?>
                    <?php endif; ?>
                </td>
                <td><?= e($album->genero) ?></td>
                <td><?= $album->anioLanzamiento ?></td>
                <td><?= e($album->discografica) ?: '<span class="tenue">-</span>' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
