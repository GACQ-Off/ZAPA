
<?php
session_start();
require_once '../conexion/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ingreso.php");
    exit();
}

$id_usuario_actual = $_SESSION['id_usuario'];
$mensaje = '';
$tipo_mensaje = '';
$caja_abierta = false;
$nombre_usuario_actual = $_SESSION['nombre_usuario'];

$tasa_dolar_actual = 0;
$stmt_tasa_query = $conn->query("SELECT tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1");
if ($stmt_tasa_query && $tasa_row = $stmt_tasa_query->fetch_assoc()) {
    $tasa_dolar_actual = (float)$tasa_row['tasa_dolar'];
}

$sql_check = "SELECT id_caja, id_usuario FROM caja WHERE estado = 'Abierta' LIMIT 1";
if ($stmt_check = $conn->prepare($sql_check)) {
    $stmt_check->execute();
    $resultado_check = $stmt_check->get_result();

    if ($resultado_check->num_rows > 0) {
        $caja_abierta = true;
        $caja_data = $resultado_check->fetch_assoc();
        $mensaje = "Ya existe una caja abierta en el sistema. Debe cerrarse antes de abrir una nueva.";
        $tipo_mensaje = 'error';
    }
    $stmt_check->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$caja_abierta) {
    $monto_inicial = filter_input(INPUT_POST, 'monto_inicial', FILTER_VALIDATE_FLOAT);
    $monto_bs = filter_input(INPUT_POST, 'monto_bs', FILTER_VALIDATE_FLOAT);

    $monto_inicial = ($monto_inicial === false || $monto_inicial < 0) ? 0 : $monto_inicial;
    $monto_bs = ($monto_bs === false || $monto_bs <= 0) ? 0 : $monto_bs;

    if ($monto_inicial == 0 && $monto_bs == 0) {
        $mensaje = "Debes introducir un monto inicial en al menos una de las monedas.";
        $tipo_mensaje = 'error';
    } else {
        $sql_insert = "INSERT INTO caja (id_usuario, fecha_apertura, monto_inicial, monto_bs, estado) VALUES (?, NOW(), ?, ?, 'Abierta')";
        if ($stmt_insert = $conn->prepare($sql_insert)) {
            $stmt_insert->bind_param("idd", $id_usuario_actual, $monto_inicial, $monto_bs);

            if ($stmt_insert->execute()) {
                $mensaje = "¡Caja abierta exitosamente! Monto inicial: $" . number_format($monto_inicial, 2) . " y " . number_format($monto_bs, 2) . " Bs. Serás redirigido a la página de ventas.";
                $tipo_mensaje = 'success';
                header("refresh:3;url=venta_gerente.php");
            } else {
                $mensaje = "Error al abrir la caja: " . $conn->error;
                $tipo_mensaje = 'error';
            }
            $stmt_insert->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Apertura de Caja</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f4f4; }
        .container { max-width: 500px; margin-top: 50px; }
    </style>
    <?php include "../assets/head_gerente.php"; ?>
</head>
<body>
<?php include "../assets/lista_gerente.php"; ?>
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="text-center">Apertura de Caja</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo ($tipo_mensaje == 'success') ? 'success' : 'danger'; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            <?php if ($caja_abierta): ?>
    <div class="alert alert-warning">
        <strong>⚠️ Sistema de Caja Única</strong><br>
        Solo puede haber UNA caja abierta en el sistema en todo momento.<br>
        <?php if (isset($caja_data)): ?>
            Caja actualmente abierta por usuario ID: <?php echo $caja_data['id_usuario']; ?>
        <?php endif; ?>
    </div>
    <div class="text-center">
        <a href="cierre_caja.php" class="btn btn-primary">Ir al Cierre de Caja</a>
    </div>
<?php endif; ?>

            <form action="caja.php" method="post" <?php if ($caja_abierta) echo 'style="display:none;"'; ?>>
                <div class="form-group">
                    <label for="monto_inicial">Monto Inicial en Caja (USD)</label>
                    <input type="number" class="form-control" id="monto_inicial" name="monto_inicial" step="0.01" min="0" placeholder="0.00" autofocus>
                </div>
                <div class="form-group">
                    <label for="monto_bs">Monto Inicial en Caja (BS)</label>
                    <input type="number" class="form-control" id="monto_bs" name="monto_bs" step="0.01" min="0" placeholder="0.00">
                    <small class="form-text text-muted">Tasa de cambio actual: 1 USD = <?php echo number_format($tasa_dolar_actual, 2); ?> BS</small>
                </div>
                <button type="submit" class="btn btn-success btn-block">Abrir Caja</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>