<?php
/** @var \Musica\Domain\Artista[] $artistas */
/** @var string $q */
$tituloPagina = 'Artistas';
?>
<div class="barra">
    <h1>Artistas</h1>
    <a class="boton" href="/artistas/nuevo">Nuevo artista</a>
</div>

<form class="busqueda" method="get" action="/artistas" role="search">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar por nombre, apellido o nacionalidad" aria-label="Buscar artistas">
    <button type="submit" class="sec">Buscar</button>
</form>

<?php if ($artistas === []): ?>
    <p class="vacio"><?= $q !== '' ? 'Ningún artista coincide con la búsqueda.' : 'Todavía no hay artistas registrados.' ?></p>
<?php else: ?>
    <div class="lados">
        <?php foreach (lados($artistas) as $lado => $pistas): if ($pistas === []) continue; ?>
            <section class="lado" aria-label="Lado <?= $lado ?>">
                <h2>Lado <?= $lado ?></h2>
                <ol class="pistas">
                    <?php foreach ($pistas as $i => $artista): ?>
                        <li class="pista">
                            <span class="n"><?= $lado . ($i + 1) ?></span>
                            <a class="t" href="/artistas/<?= $artista->id ?>"><?= e($artista->apellido . ', ' . $artista->nombre) ?></a>
                            <span class="meta"><?= e($artista->nacionalidad) ?: '-' ?></span>
                            <span class="der">
                                <b><?= e($artista->fechaNacimiento) ?: '-' ?></b>
                                Nacimiento
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
