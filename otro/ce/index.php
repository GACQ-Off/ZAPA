<?php 
$iu = 1;
require_once 'includes/_br4in.php'; 
$busqueda = $_GET['b'] ?? '';
$busqueda_sql = "%" . $db->real_escape_string($busqueda) . "%"; 
$limite = 5; 
$pagina_actual = (int)($_GET['p'] ?? 1);
$pagina_actual = max(1, $pagina_actual); 
$offset = ($pagina_actual - 1) * $limite;
$condicion_busqueda = "";
if (!empty($busqueda)) {
    $condicion_busqueda = " AND (p.nombres_profesor LIKE '{$busqueda_sql}' 
                                OR p.apellidos_profesor LIKE '{$busqueda_sql}'
                                OR pr.nombre_profesion LIKE '{$busqueda_sql}'
                                OR p.ci_profesor LIKE '{$busqueda_sql}')";
}
$sql_conteo = "SELECT COUNT(p.ci_profesor) AS total 
               FROM profesor p
               JOIN profesion pr ON p.profesion_id = pr.id_profesion
               WHERE p.status_profesor = 1 {$condicion_busqueda}";
$resultado_conteo = $db->query($sql_conteo);
$fila_conteo = $resultado_conteo->fetch_assoc();
$total_registros = $fila_conteo['total'] ?? 0; 
$total_paginas = ceil($total_registros / $limite); 
$pagina_anterior = max(1, $pagina_actual - 1);
$pagina_siguiente = min($total_paginas, $pagina_actual + 1);
$sql_profesores = "SELECT 
                        p.ci_profesor, 
                        p.nombres_profesor, 
                        p.apellidos_profesor, 
                        p.telefono_profesor, 
                        p.email_profesor, 
                        pr.nombre_profesion
                   FROM profesor p
                   JOIN profesion pr ON p.profesion_id = pr.id_profesion
                   WHERE p.status_profesor = 1
                   {$condicion_busqueda}
                   ORDER BY p.apellidos_profesor ASC
                   LIMIT {$limite} OFFSET {$offset}"; 
$resultado = $db->query($sql_profesores); 
$profesores = [];
if ($resultado) {$profesores = $resultado->fetch_all(MYSQLI_ASSOC);
    $resultado->free(); 
}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Profesores | Listados</title>
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
          <?php if (!empty($profesores)): ?>
            <?php foreach ($profesores as $profe): ?>
              <div class="profe_indiv">
                <div id="indiv_uno">
                  <img src="img/svg/profile_black.svg" alt="Logo" class="icono">
                </div>
                <div id="indiv_dos">
                  <h4>
                                                            <text>
                      <?php echo htmlspecialchars($profe['nombres_profesor']); ?>
                    </text>
                    <text>
                      <?php echo htmlspecialchars($profe['apellidos_profesor']); ?>
                    </text>
                  </h4>
                  <h6>
                    Teléfono:
                    <text>
                      <?php echo htmlspecialchars($profe['telefono_profesor'] ?? 'N/A'); ?>
                    </text>
                  </h6>
                  <h6>
                    Correo:
                    <text>
                      <?php echo htmlspecialchars($profe['email_profesor'] ?? 'N/A'); ?>
                    </text>
                  </h6>
                  <h6>
                    Profesión:
                    <text>
                      <?php echo htmlspecialchars($profe['nombre_profesion']); ?>
                    </text>
                  </h6>
                </div>
                <div id="indiv_tres">
                                    <a href="acciones/eliminar_profesor.php?ci=<?php echo htmlspecialchars($profe['ci_profesor']); ?>">
                  <button id="eliminar" value="<?php echo htmlspecialchars($profe['ci_profesor']); ?>">
                    Eliminar
                  </button>
                                    </a>
                                    <a href="detalles_profesor.php?ci=<?php echo htmlspecialchars($profe['ci_profesor']); ?>">
                  <button id="detalles" value="<?php echo htmlspecialchars($profe['ci_profesor']); ?>">
                    Detalles
                  </button>
                                    </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
                                    <p style="text-align: center; padding: 20px;">No se encontraron profesores activos que coincidan con la búsqueda. </p>
          <?php endif; ?>
        </div>
            </section>
        </main>
    </body>
</html>