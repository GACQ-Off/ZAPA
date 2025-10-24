<?php
$iu = 2;
require_once 'includes/_br4in.php';
$busqueda = $_GET['b'] ?? '';
$busqueda_sql = "%" . $db->real_escape_string($busqueda) . "%";
$limite = 5;
$pagina_actual = (int) ($_GET['p'] ?? 1);
$pagina_actual = max(1, $pagina_actual);
$offset = ($pagina_actual - 1) * $limite;
$sql_conteo = "SELECT COUNT(id_profesion) AS total 
               FROM profesion 
               WHERE status_profesion = 1";
$condicion_busqueda = "";
if (!empty($busqueda)) {
    $condicion_busqueda = " AND (nombre_profesion LIKE '{$busqueda_sql}' OR descripcion_profesion LIKE '{$busqueda_sql}')";
    $sql_conteo .= $condicion_busqueda;
}
$resultado_conteo = $db->query($sql_conteo);
$fila_conteo = $resultado_conteo->fetch_assoc();
$total_registros = $fila_conteo['total'] ?? 0;
$total_paginas = ceil($total_registros / $limite);
$pagina_anterior = max(1, $pagina_actual - 1);
$pagina_siguiente = min($total_paginas, $pagina_actual + 1);
$sql_profesiones = "SELECT id_profesion, nombre_profesion, descripcion_profesion 
                    FROM profesion 
                    WHERE status_profesion = 1
                    {$condicion_busqueda}
                    ORDER BY nombre_profesion ASC
                    LIMIT {$limite} OFFSET {$offset}";
$resultado = $db->query($sql_profesiones);
$profesiones = [];
if ($resultado) {
    $profesiones = $resultado->fetch_all(MYSQLI_ASSOC);
    $resultado->free();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profesiones | Listados</title>
    <link rel="stylesheet" href="CSS/_interface.css">
    <link rel="stylesheet" href="CSS/_bar.css">
    <link rel="stylesheet" href="CSS/list_t.css">
    <script src="" async defer></script>
</head>

<body>
    <?php include 'includes/_bar.php' ?>
    <main id="contenedor_principal">
        <?php include 'includes/_header.php' ?>
        <section id="cuerpo_principal">
            <?php include 'includes/_b0dy.php' ?>
            <div id="cuatro_cuerpo">
                <?php if (!empty($profesiones)): ?>

                    <?php foreach ($profesiones as $profesion): ?>

                        <div class="profe_indiv">
                            <div id="indiv_uno">
                                <img src="img/svg/role_black.svg" alt="Logo" class="icono">
                            </div>
                            <div id="indiv_dos">
                                <h4>
                                    <?php echo htmlspecialchars($profesion['nombre_profesion']); ?>
                                </h4>
                                <h6>
                                    Descripción:
                                    <text>
                                        <?php echo htmlspecialchars($profesion['descripcion_profesion']); ?>
                                    </text>
                                </h6>
                            </div>
                            <div id="indiv_tres">
                                <a href="acciones/eliminar_profesion.php?id=<?php echo $profesion['id_profesion']; ?>">
                                    <button id="eliminar" value="<?php echo $profesion['id_profesion']; ?>">
                                        Eliminar
                                    </button>
                                </a>
                                <a href="detalles_profesion.php?id=<?php echo $profesion['id_profesion']; ?>">
                                    <button id="detalles" value="<?php echo $profesion['id_profesion']; ?>">
                                        Detalles
                                    </button>
                                </a>
                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else: ?>
                    <p style="text-align: center; padding: 20px;">No hay profesiones activas para mostrar.</p>
                <?php endif; ?>

            </div>
        </section>
    </main>
</body>

</html>