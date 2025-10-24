<?php
session_start();
include "../conexion/conexion.php";

$busqueda = '';

if (!empty($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
}

$where_conditions = array();

if (!empty($busqueda)) {
    $where_conditions[] = "usuario.nombre_usuario LIKE '%$busqueda%'";
}

$where_conditions[] = "usuario.id_tipo_usuario = 2";

$where_conditions[] = "usuario.estado_usuario = 1";

$where_sql = "";
if (count($where_conditions) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_conditions);
}

$sql_registro = mysqli_query($conn, "SELECT COUNT(*) as total_registro
    FROM usuario
    $where_sql ");

$result_registro = mysqli_fetch_array($sql_registro);
$total_registro = $result_registro['total_registro'];

$por_pagina = 5;
if (empty($_GET['pagina'])) {
    $pagina = 1;
} else {
    $pagina = $_GET['pagina'];
}

$desde = ($pagina - 1) * $por_pagina;
$total_paginas = ceil($total_registro / $por_pagina);

$query = mysqli_query($conn, "SELECT usuario.id_usuario, usuario.nombre_usuario
    FROM usuario
    $where_sql
    LIMIT $desde, $por_pagina");

$result = mysqli_num_rows($query);
?>

<html>

<head>
<?php include "../assets/head_gerente.php"?>
<link rel="stylesheet" href="../assets/css/lista_empleados.css">
<title>Lista de Cajeros</title>
</head>

<body>
<?php include "../assets/lista_gerente.php"?>
<div class="main-content-lista">
    <h3>Lista de Cajeros</h3>
    <form action="" method="GET" class="attendance__form">
        <span class="material-symbols-outlined ico-search"></span>
        <input type="text" data-valid="text" name="busqueda" placeholder="Buscar Cajero" value="<?php echo $busqueda; ?>">
        <input type="submit" value="Buscar" class="attendance__button">
    </form>
    <a href="../registrar/registrar_cajero.php" class="btn btn--secondary">Registrar Cajero</a>
    <table class="attendance__table">
        <tr class="attendance__table-header">
            <th class="attendance__table-header-cell ">Codigo usuario</th>
            <th class="attendance__table-header-cell ">Nombre de Usuario</th>
            <th class="attendance__table-header-cell ">Acciones</th>
        </tr>
        <?php
        if ($result > 0) {
            while ($data = mysqli_fetch_array($query)) {
        ?>
                <tr>
                    <td class="attendance__table-cell"><?php echo $data["id_usuario"]; ?></td>
                    <td class="attendance__table-cell"><?php echo $data["nombre_usuario"]; ?></td>
                    <td class="attendance__table-cell">
                        <a title="editar" href="../editar/editar_cajero.php?Cajero=<?php echo $data["id_usuario"]; ?>">
                            <span class="material-symbols-rounded ico-edit md-24 fill-1 wght-18 leading-none error">
                            </span>
                        </a>
                        <button type="button" title="Eliminar" class="btn-icon" onclick="document.getElementById('confirmDeleteModal_<?php echo htmlspecialchars($data['id_usuario']); ?>').showModal();">
                            <span class="material-symbols-rounded ico-delete md-24 fill-1 wght-18 leading-none error">
                            </span>
                        </button>
                        <dialog id="confirmDeleteModal_<?php echo htmlspecialchars($data['id_usuario']); ?>" class="confirm-dialog">
                            <h3 class="dialog-title">Confirmar Eliminación</h3>
                            <p class="dialog-message">
                                ¿Estás seguro de que deseas eliminar al cajero
                                <strong><?php echo htmlspecialchars($data["nombre_usuario"]); ?></strong>?
                            </p>
                            <p class="dialog-warning">Al eliminarlo, solo se marcará como inactivo.</p>
                            <div class="dialog-actions">
                                <a href="../eliminar/eliminar_cajero.php?Cajero=<?php echo htmlspecialchars($data["id_usuario"]); ?>" class="btn btn--danger">Eliminar</a>
                                <button type="button" class="btn btn--secondary" onclick="document.getElementById('confirmDeleteModal_<?php echo htmlspecialchars($data['id_usuario']); ?>').close();">Cancelar</button>
                            </div>
                        </dialog>
                    </td>
                </tr>
        <?php
            }
        } else {
        ?>
                <tr>
                    <td colspan="3" class="attendance__table-cell">No hay cajeros registrados.</td>
                </tr>
        <?php
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
                    <a href="?pagina=<?php echo ($pagina - 1); ?>&busqueda=<?php echo $busqueda; ?>" class="pagination__link"><<</a>
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
</div>
<script src="../assets/js/menu.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>