<?php if ($nro_registros > 0   && $total_paginas > 1) { ?> <div class="paginador">
        <ul>
            <?php $url_base = '?';
            if (!empty($terminoBusqueda)) {
                $url_base .= 'b=' . urlencode($terminoBusqueda) . '&';
            };
            if ($paginaActual > 1) { ?>
                <li><a href="<?php echo $url_base; ?>p=1">|<</a>
                </li>
                <li><a href="<?php echo $url_base; ?>p=<?php echo $paginaActual - 1; ?>"><<</a>
                </li>
            <?php };
            for ($i = 1; $i <= $total_paginas; $i++) {if ($i == $paginaActual) {
                    echo '<li class="pagina_seleccionada">' . $i . '</li>';
                } else {
                    echo '<li><a href="' . $url_base . 'p=' . $i . '">' . $i . '</a></li>';
                }};
            if ($paginaActual < $total_paginas && $total_paginas > 0) {?>
                <li><a href="<?php echo $url_base; ?>p=<?php echo $paginaActual + 1; ?>">>></a></li>
                <li><a href="<?php echo $url_base; ?>p=<?php echo $total_paginas; ?>">>|</a></li>
            <?php }; ?>
        </ul>
    </div>
<?php }; ?>