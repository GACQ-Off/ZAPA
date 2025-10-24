<?php
require_once '../conexion/conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $rif_original = $_POST['rif_original'] ?? '';
    $rif_nuevo = trim($_POST['rif'] ?? ''); 
    $nombre = trim($_POST['nombre_provedor'] ?? ''); 
    $telefono = trim($_POST['telefono_pro'] ?? '');
    $correo = trim($_POST['correo_pro'] ?? '');

    if (empty($rif_original) || empty($rif_nuevo) || empty($nombre)) {
        echo "<script>alert('Error: Faltan datos obligatorios (RIF original o Nombre).'); window.history.back();</script>";
        exit;
    }

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $sql = "UPDATE proveedor SET
                RIF = ?,
                nombre_provedor = ?, 
                 
                telefono_pro = ?,
                correo_pro = ?
            WHERE RIF = ? AND estado_proveedor = 1"; 


    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssiss", $rif_nuevo, $nombre, $telefono, $correo, $rif_original);

        if ($stmt->execute()) {
            header("Location: ../listas/lista_provedor.php?status=updated");
            exit;
        } else {
            echo "<script>alert('Error al actualizar el proveedor: " . addslashes($stmt->error) . "'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error al preparar la consulta de actualización: " . addslashes($conn->error) . "'); window.history.back();</script>";
    }

    $conn->close();

} else {
    header("Location: ../listas/lista_provedor.php");
    exit;
}
?>