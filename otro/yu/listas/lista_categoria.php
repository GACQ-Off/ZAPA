<?php
session_start();
require_once '../conexion/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Categorías</title>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/lista_empleados.css">
    <link rel="stylesheet" href="../assets/css/lista_categoria.css">
</head>
<body>
    <?php include '../assets/lista_gerente.php'; ?>

    <div class="main-content-lista">
        <h2>Lista de Categorías </h2>

        <?php
        if (isset($conn) && !$conn->connect_error) {
            $sql_lista = "SELECT id_categoria, nombre_categoria FROM categoria WHERE estado_categoria = 1 ORDER BY nombre_categoria ASC";
            $result_lista = $conn->query($sql_lista);

            if ($result_lista && $result_lista->num_rows > 0) {
        ?>
                <div class="table-responsive">
                    <table class="attendance__table">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre de la Categoría</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <a href="../registrar/categoria_pro.php" class="btn btn--secondary">Nueva Categoria</a>
                        <tbody>
                            <?php while ($row_lista = $result_lista->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <a href="lista_productos_categoria.php?id=<?php echo htmlspecialchars($row_lista['id_categoria']); ?>" title="Ver productos de esta categoría">
                                            <?php echo htmlspecialchars($row_lista['nombre_categoria']); ?>
                                        </a>
                                    </td>
                                    <td class="action-links text-center">
                                        <a href="../editar/editar_categoria.php?id=<?php echo htmlspecialchars($row_lista['id_categoria']); ?>" class="btn btn-sm btn-warning me-2" title="Editar">
                                            <span class="material-symbols-rounded ico-edit alert">Editar</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" title="Eliminar" onclick="document.getElementById('confirmDeleteModal_<?php echo htmlspecialchars($row_lista['id_categoria']); ?>').showModal();">
                                            <span class="material-symbols-rounded ico-delete error">Eliminar</span>
                                        </button>

                                        <dialog id="confirmDeleteModal_<?php echo htmlspecialchars($row_lista['id_categoria']); ?>" class="confirm-dialog">
                                            <h3 class="dialog-title">Confirmar Eliminación</h3>
                                            <p class="dialog-message">
                                                ¿Estás seguro de que deseas eliminar la categoría
                                                <strong><?php echo htmlspecialchars($row_lista["nombre_categoria"]); ?></strong>?
                                            </p>
                                            <p class="dialog-warning">Al eliminarla, solo se marcará como inactiva.</p>
                                            <div class="dialog-actions">
                                                <button type="button" class="btn btn--secondary" onclick="document.getElementById('confirmDeleteModal_<?php echo htmlspecialchars($row_lista['id_categoria']); ?>').close();">Cancelar</button>
                                                <a href="../eliminar/eliminar_categoria.php?id=<?php echo htmlspecialchars($row_lista["id_categoria"]); ?>" class="btn btn--danger">Eliminar</a>
                                            </div>
                                        </dialog>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <a href="../menu.php" class="btn btn--cancel">Regresar</a>
                </div>
        <?php
            } else {
                echo "<p class='mensaje-info'>No hay categorías registradas aún.</p>";
            }
            $conn->close();
        } else {
            echo "<p class='mensaje-info'>No se pudo conectar para mostrar la lista de categorías.</p>";
        }
        ?>
    </div>
</body>
</html>