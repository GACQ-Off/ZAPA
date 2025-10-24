<?php
session_start();
include "../conexion/conexion.php";

$busqueda = '';
if (!empty($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
}

$where_conditions = array();
$where_conditions[] = "estado_categoria_gasto = 1";

if (!empty($busqueda)) {
    $busqueda_esc = mysqli_real_escape_string($conn, $busqueda);
    $where_conditions[] = "nombre_categoria_gasto LIKE '%$busqueda_esc%'";
}
$where_sql = "WHERE " . implode(" AND ", $where_conditions);
?>
<html>

<head>
<?php include "../assets/head_gerente.php"?>
<link rel="stylesheet" href="../assets/css/lista_cargos.css">
<link rel="stylesheet" href="../assets/css/lista_empleados.css">
<title>Lista de Categoria (Gastos)</title>
</head>

<body>
<?php include "../assets/lista_gerente.php"?>
            <div class="main-content-lista">
                <h3>Lista de Categorias (Gastos)</h3>
                <form action="" method="GET" class="attendance__form">
                    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo htmlspecialchars($busqueda); ?>">
                    <input type="submit" value="Buscar" class="attendance__button">
                </form>
                <a href="../registrar/registrar_categoria_gasto.php" class="btn btn--secondary">Crear Categoria</a>
            <table class="attendance__table">
                <tr class="attendance__table-header">
                    <th class="attendance__table-header-cell">Código Categoria</th>
                    <th class="attendance__table-header-cell">Nombre de la Categoria</th>
                    <th>Acciones</th>
                </tr>
                <?php
                $sql_registro = mysqli_query($conn, "SELECT COUNT(*) as total_registro FROM categoria_gasto $where_sql");
                $result_registro = mysqli_fetch_array($sql_registro);
                $total_registro = $result_registro['total_registro'];
                $por_pagina = 5;
                $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                $desde = ($pagina - 1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);
                $query = mysqli_query($conn, "SELECT * FROM categoria_gasto $where_sql ORDER BY id_categoria_gasto ASC LIMIT $desde, $por_pagina");
                if (mysqli_num_rows($query) > 0) {
                    while ($data = mysqli_fetch_array($query)) {
                ?>
                        <tr>
                            <td class="attendance__table-cell "><?php echo htmlspecialchars($data["id_categoria_gasto"]); ?></td>
                            <td class="attendance__table-cell "><a href="lista_categoria_gasto_especifico.php?Categoria=<?php echo htmlspecialchars($data["id_categoria_gasto"]); ?>" title="Ver gastos de esta categoría">
                                <?php echo htmlspecialchars($data["nombre_categoria_gasto"]); ?>
                                </a>
                            </td>
                            <td class="attendance__table-cell ">
                                <a href="../editar/editar_categoria_gasto.php?Categoria=<?php echo $data["id_categoria_gasto"]; ?>">
                                    <span class="material-symbols-rounded ico-edit md-24 fill-1 wght-18 leading-none alert">
                                    </span>
                                </a>
                                <button type="button" title="Eliminar" class="btn-icon" onclick="document.getElementById('confirmDeleteModal_<?php echo htmlspecialchars($data['id_categoria_gasto']); ?>').showModal();">

                                    <span class="material-symbols-rounded ico-delete md-24 fill-1 wght-18 leading-none error">
                                    </span>
                                </button>

            <dialog id="confirmDeleteModal_<?php echo htmlspecialchars($data['id_categoria_gasto']); ?>" class="confirm-dialog">
            <h3 class="dialog-title">Confirmar Eliminación</h3>
            <p class="dialog-message">
                ¿Estás seguro de que deseas eliminar la categoria:
                <strong><?php echo htmlspecialchars($data["nombre_categoria_gasto"]); ?></strong>

            </p>
            <p class="dialog-warning">Al eliminarlo, solo se marcará como inactivo.</p>
            <div class="dialog-actions">
                <a href="../eliminar/eliminar_categoria_gasto.php?Categoria=<?php echo htmlspecialchars($data["id_categoria_gasto"]); ?>" class="btn btn--danger">Eliminar</a>
                <button type="button" class="btn btn--secondary" onclick="document.getElementById('confirmDeleteModal_<?php echo htmlspecialchars($data['id_categoria_gasto']); ?>').close();">Cancelar</button>
            </div>
        </dialog>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="3" class="attendance__table-cell ">No hay registros</td></tr>';
                }
                ?>
            </table>
            <?php if ($total_paginas > 1) : ?>
                <ul class="pagination">
                    <?php if ($pagina != 1) : ?>
                        <li class="pagination__item">
                            <a href="?pagina=1&busqueda=<?php echo $busqueda; ?>" class="pagination__link">|<<</a>
                        </li>
                        <li class="pagination__item">
                            <a href="?pagina=<?php echo ($pagina - 1); ?>&busqueda=<?php echo $busqueda; ?>" class="pagination__link">
                                <<</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
                        <?php if ($i == $pagina) : ?>
                            <li class="pagination__item pagination__item--active"><?php echo $i; ?></li>
                        <?php else : ?>
                            <li class="pagination__item">
                                <a href="?pagina=<?php echo $i; ?>&busqueda=<?php echo $busqueda; ?>" class="pagination__link"><?php echo $i; ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($pagina != $total_paginas) : ?>
                        <li class="pagination__item">
                            <a href="?pagina=<?php echo ($pagina + 1); ?>&busqueda=<?php echo $busqueda; ?>" class="pagination__link">>></a>
                        </li>
                        <li class="pagination__item">
                            <a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>" class="pagination__link">>>|</a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
                <a href="../menu.php" class="btn btn--cancel">Regresar</a>
            </section>
</body>
</html>