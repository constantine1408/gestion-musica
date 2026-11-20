<?php
/** @var int $codigo */
/** @var string $mensaje */
$tituloPagina = "Error $codigo";
?>
<div class="error-pagina">
    <h1><?= $codigo === 404 ? 'No encontrado' : 'No se pudo completar la acción' ?></h1>
    <p><?= e($mensaje) ?></p>
    <p><a href="/albumes">Volver al catálogo</a></p>
</div>
