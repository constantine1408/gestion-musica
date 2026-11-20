<?php
/** @var \Musica\Domain\Artista[] $artistas */
/** @var string $q */
$tituloPagina = 'Artistas';
?>
<div class="barra">
    <h1>Artistas</h1>
    <a class="boton" href="/artistas/nuevo">Nuevo artista</a>
</div>

<form class="busqueda" method="get" action="/artistas">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar por nombre, apellido o nacionalidad">
    <button type="submit" class="sec">Buscar</button>
</form>

<?php if ($artistas === []): ?>
    <p class="vacio"><?= $q !== '' ? 'Ningún artista coincide con la búsqueda.' : 'Todavía no hay artistas registrados.' ?></p>
<?php else: ?>
    <table>
        <thead>
            <tr><th>Nombre</th><th>Nacimiento</th><th>Nacionalidad</th></tr>
        </thead>
        <tbody>
        <?php foreach ($artistas as $artista): ?>
            <tr>
                <td><a href="/artistas/<?= $artista->id ?>"><?= e($artista->apellido . ', ' . $artista->nombre) ?></a></td>
                <td><?= e($artista->fechaNacimiento) ?: '<span class="tenue">-</span>' ?></td>
                <td><?= e($artista->nacionalidad) ?: '<span class="tenue">-</span>' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
