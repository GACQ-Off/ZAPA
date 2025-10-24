<div id="uno_cuerpo">
    <div id="dos_cuerpo">
        <h5>
            Total de Registros:
            <text>
                <?php echo htmlspecialchars($total_registros) ?> Resultados
            </text>
        </h5>
        <button>
            <a href="<?php echo htmlspecialchars($form) ?>">
                <?php echo htmlspecialchars($boton) ?>
            </a>
        </button>
    </div>
    <ul>
        <?php $query_params = 'b=' . htmlspecialchars($busqueda); ?>
        <li><a href="?page=1&<?php echo $query_params ?>">|<< /a>
        </li>
        <li><a href="?page=<?php echo $pagina_anterior ?>&<?php echo $query_params ?>">
                <<< /a>
        </li>
        <li><a href="?page=<?php echo $pagina_siguiente ?>&<?php echo $query_params ?>">>></a></li>
        <li><a href="?page=<?php echo $total_paginas ?>&<?php echo $query_params ?>">>|</a></li>
    </ul>
</div>