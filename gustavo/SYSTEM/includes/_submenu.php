<div class="_indiv_uno">
    <form method="POST" action="disabler.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" hidden >
        <input type="hidden" name="entidad" value="<?php echo htmlspecialchars($tipo_registro); ?>" hidden >
        <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($nombre_registro); ?>" hidden >
        <input type="hidden" name="tab" value="<?php echo htmlspecialchars($iu); ?>" hidden >
        <button type="submit" class="btn_eliminar" title="Deshabilitar Registro">
            <img src="img/svg/basura_white.svg" alt="icono">
        </button>
    </form>
    
    <button class="btn_detalles" data-id="<?php echo htmlspecialchars($id); ?>"
        data-entidad="<?php echo htmlspecialchars($iu) ?>"
        data-nombre="<?php echo htmlspecialchars($nombre_registro); ?>">
        <img src="img/svg/detalles_white.svg" alt="icono">
    </button>
</div>