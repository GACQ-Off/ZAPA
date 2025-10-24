<?php

session_start();

include "../conexion/conexion.php";

if (!empty($_POST)) {
    if (
        empty($_POST['Cedula']) || empty($_POST['Nombre']) || empty($_POST['Apellido']) ||
        empty($_POST['Fecha_Nacimiento']) || empty($_POST['Telefono']) || empty($_POST['Cargo'])
    ) {
        $alert = 'Todos los campos obligatorios deben ser completados.';
        echo "<script>alert('$alert'); window.history.back();</script>";
        exit;
    } else {
        $id_usuario = $_SESSION['id_usuario'];
        $cedula = htmlspecialchars(trim($_POST['Cedula']));
        $nombre = ucwords(htmlspecialchars(trim($_POST['Nombre'])));
        $apellido = ucwords(htmlspecialchars(trim($_POST['Apellido'])));
        $fecha_nacimiento = $_POST['Fecha_Nacimiento'];
        $telefono = htmlspecialchars(trim($_POST['Telefono']));
        $cargo_id = (int)$_POST['Cargo'];

        if (!preg_match("/^[0-9]+$/", $cedula)) {
            $alert = 'La Cédula solo debe contener números.';
            echo "<script>alert('$alert'); window.history.back();</script>";
            exit;
        }
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            $alert = 'El Nombre solo debe contener letras y espacios.';
            echo "<script>alert('$alert'); window.history.back();</script>";
            exit;
        }
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido)) {
            $alert = 'El Apellido solo debe contener letras y espacios.';
            echo "<script>alert('$alert'); window.history.back();</script>";
            exit;
        }
        if (!preg_match("/^[0-9\s\-\(\)]+$/", $telefono)) {
            $alert = 'El Teléfono contiene caracteres inválidos.';
            echo "<script>alert('$alert'); window.history.back();</script>";
            exit;
        }
        if (!strtotime($fecha_nacimiento)) {
            $alert = 'La Fecha de Nacimiento no es válida.';
            echo "<script>alert('$alert'); window.history.back();</script>";
            exit;
        }

        $query_cedula = mysqli_prepare($conn, "SELECT cedula_emple FROM empleado WHERE cedula_emple = ?");
        if ($query_cedula === false) {
            $alert = 'Error interno del servidor.';
            echo "<script>alert('$alert'); window.history.back();</script>";
            exit;
        }
        mysqli_stmt_bind_param($query_cedula, "s", $cedula);
        mysqli_stmt_execute($query_cedula);
        mysqli_stmt_store_result($query_cedula);

        if (mysqli_stmt_num_rows($query_cedula) > 0) {
            $alert = 'Ya existe un empleado registrado con esta cédula.';
            echo "<script>alert('$alert'); window.history.back();</script>";
            exit;
        }
        mysqli_stmt_close($query_cedula);

        $query_cargo = mysqli_prepare($conn, "SELECT id_cargo FROM cargo WHERE id_cargo = ? AND estado_cargo = 1");
        if ($query_cargo === false) {
            $alert = 'Error interno del servidor.';
            echo "<script>alert('$alert'); window.history.back();</script>";
            exit;
        }
        mysqli_stmt_bind_param($query_cargo, "i", $cargo_id);
        mysqli_stmt_execute($query_cargo);
        mysqli_stmt_store_result($query_cargo);

        if (mysqli_stmt_num_rows($query_cargo) === 0) {
            $alert = 'El cargo seleccionado no es válido o está inactivo.';
            echo "<script>alert('$alert'); window.history.back();</script>";
            exit;
        }
        mysqli_stmt_close($query_cargo);

        $query_insert = mysqli_prepare(
            $conn,
            "INSERT INTO empleado(cedula_emple,nombre_emp,apellido_emp,fecha_nacimiento,telefono_emple,id_cargo,id_usuario,estado_empleado) VALUES(?,?,?,?,?,?,?,1)"
        );
        if ($query_insert === false) {
            $alert = 'Error interno del servidor.';
            echo "<script>alert('$alert'); window.history.back();</script>";
            exit;
        }
        mysqli_stmt_bind_param($query_insert, "isssisi", $cedula, $nombre, $apellido, $fecha_nacimiento, $telefono, $cargo_id, $id_usuario);

        if (mysqli_stmt_execute($query_insert)) {

            $_SESSION['alert_message'] = "Empleado registrado exitosamente.";
            $_SESSION['alert_type'] = 'success';
            header("Location: ../listas/lista_empleado.php");
            exit;
        } else {
            $alert = 'Error al Registrar el Empleado: ' . mysqli_stmt_error($query_insert);
            echo "<script>alert('$alert'); window.history.back();</script>";
        }
        mysqli_stmt_close($query_insert);
    }
    if (isset($conn) && is_object($conn)) {
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include "../assets/head_gerente.php" ?>
    <link rel="stylesheet" href="../assets/css/registrar_empleado.css">
    <title>Registro de empleado</title>
    <style>
        .error-message {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
            display: block;
        }
        .input-error {
            border: 1px solid red !important;
        }
    </style>
</head>
<body>
    <?php include "../assets/lista_gerente.php" ?>
    <section>
        <form action="" method="post" class="formulario" id="empleadoForm">
            <h2 class="title">Registro de Empleado</h2>
            <div class="form-group">
                <label for="Cedula" class="text">Cédula:</label>
                <input type="text" required name="Cedula" id="Cedula" placeholder="Ingrese Cédula" class="input" pattern="[0-9]+" title="La Cédula solo debe contener números." autocomplete="off">
                <span class="error-message" id="cedulaError"></span>
            </div>
            <div class="form-group">
                <label for="Nombre" class="text">Nombre:</label>
                <input type="text" required name="Nombre" id="Nombre" placeholder="Ingrese el nombre" class="input" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="El Nombre solo debe contener letras y espacios." autocomplete="off">
                <span class="error-message" id="nombreError"></span>
            </div>
            <div class="form-group">
                <label for="Apellido" class="text">Apellido:</label>
                <input type="text" autocomplete="off" required name="Apellido" id="Apellido" placeholder="Ingrese el apellido" class="input" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="El Apellido solo debe contener letras y espacios.">
                <span class="error-message" id="apellidoError"></span>
            </div>
            <div class="form-group">
                <label for="Fecha_Nacimiento" class="text">Fecha de Nacimiento:</label>
                <input type="date" required name="Fecha_Nacimiento" id="Fecha_Nacimiento">
                <span class="error-message" id="fechaNacimientoError"></span>
            </div>
            <div class="form-group">
                <label for="Telefono" class="text">Teléfono:</label>
                <input type="text" required name="Telefono" id="Telefono" placeholder="Ingrese el teléfono" class="input" pattern="[0-9\s\-\(\)]+" title="El Teléfono solo debe contener números, espacios, guiones o paréntesis." autocomplete="off">
                <span class="error-message" id="telefonoError"></span>
            </div>
            <div class="form-group">
                <label for="cargo_input" class="text">Cargo:</label>
                <input type="text" list="cargos_datalist" name="cargo_nombre" id="cargo_input" placeholder="Buscar cargo..." class="input" required autocomplete="off">
                <input type="hidden" name="Cargo" id="cargo_id_hidden" required>
                <span class="error-message" id="cargoError"></span>
                <datalist id="cargos_datalist">
                    <?php
                    if (isset($conn) && is_object($conn)) {
                        $sql_cargos_datalist = "SELECT id_cargo, nom_cargo FROM cargo WHERE estado_cargo = 1 ORDER BY nom_cargo ASC";
                        $result_cargos_datalist = mysqli_query($conn, $sql_cargos_datalist);
                        if ($result_cargos_datalist) {
                            while ($mostrar_cargo_datalist = mysqli_fetch_array($result_cargos_datalist)) {
                                ?>
                                <option value="<?php echo htmlspecialchars($mostrar_cargo_datalist['nom_cargo']); ?>"
                                        data-id="<?php echo htmlspecialchars($mostrar_cargo_datalist['id_cargo']); ?>">
                                <?php
                            }
                            mysqli_free_result($result_cargos_datalist);
                        } else {
                            error_log("Error fetching cargos for datalist: " . mysqli_error($conn));
                        }
                    }
                    ?>
                </datalist>
            </div>
            <div class="form-group buttons">
                <input type="submit" value="Crear" class="input">
                <a href="../listas/lista_empleado.php" class="btn btn--cancel">Regresar</a>
            </div>
        </form>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("Fecha_Nacimiento");
        const hoy = new Date();
        const anio = hoy.getFullYear();
        let mes = hoy.getMonth() + 1;
        let dia = hoy.getDate();
        mes = mes < 10 ? '0' + mes : mes;
        dia = dia < 10 ? '0' + dia : dia;
        const fechaMaxima = `${anio}-${mes}-${dia}`;
        inputFecha.max = fechaMaxima;
    });
        const empleadoForm = document.getElementById('empleadoForm');
        const cargoInput = document.getElementById('cargo_input');
        const cargoIdHidden = document.getElementById('cargo_id_hidden');
        const cargosDatalist = document.getElementById('cargos_datalist');

        const cedulaInput = document.getElementById('Cedula');
        const nombreInput = document.getElementById('Nombre');
        const apellidoInput = document.getElementById('Apellido');
        const fechaNacimientoInput = document.getElementById('Fecha_Nacimiento');
        const telefonoInput = document.getElementById('Telefono');

        const cedulaError = document.getElementById('cedulaError');
        const nombreError = document.getElementById('nombreError');
        const apellidoError = document.getElementById('apellidoError');
        const fechaNacimientoError = document.getElementById('fechaNacimientoError');
        const telefonoError = document.getElementById('telefonoError');
        const cargoError = document.getElementById('cargoError');

        function validateInput(inputElement, errorMessageElement, regex, errorMessage) {
            if (!regex.test(inputElement.value)) {
                errorMessageElement.textContent = errorMessage;
                inputElement.classList.add('input-error');
                return false;
            } else {
                errorMessageElement.textContent = '';
                inputElement.classList.remove('input-error');
                return true;
            }
        }

        cedulaInput.addEventListener('input', () => validateInput(cedulaInput, cedulaError, /^[0-9]+$/, 'La Cédula solo debe contener números.'));
        nombreInput.addEventListener('input', () => validateInput(nombreInput, nombreError, /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El Nombre solo debe contener letras y espacios.'));
        apellidoInput.addEventListener('input', () => validateInput(apellidoInput, apellidoError, /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El Apellido solo debe contener letras y espacios.'));
        telefonoInput.addEventListener('input', () => validateInput(telefonoInput, telefonoError, /^[0-9\s\-\(\)]+$/, 'El Teléfono solo debe contener números, espacios, guiones o paréntesis.'));

        fechaNacimientoInput.addEventListener('input', () => {
            if (fechaNacimientoInput.value === '') {
                fechaNacimientoError.textContent = 'La Fecha de Nacimiento es obligatoria.';
                fechaNacimientoInput.classList.add('input-error');
                return false;
            } else {
                fechaNacimientoError.textContent = '';
                fechaNacimientoInput.classList.remove('input-error');
                return true;
            }
        });


        cargoInput.addEventListener('input', function() {
            let selectedOption = null;
            for (let i = 0; i < cargosDatalist.options.length; i++) {
                if (cargosDatalist.options[i].value === cargoInput.value) {
                    selectedOption = cargosDatalist.options[i];
                    break;
                }
            }

            if (selectedOption) {
                cargoIdHidden.value = selectedOption.getAttribute('data-id');
                cargoError.textContent = '';
                cargoInput.classList.remove('input-error');
            } else {
                cargoIdHidden.value = '';
                cargoError.textContent = 'Por favor, seleccione un cargo válido de la lista.';
                cargoInput.classList.add('input-error');
            }
        });

        window.addEventListener('load', function() {
            const initialValue = cargoInput.value;
            if (initialValue) {
                let foundOption = null;
                for (let i = 0; i < cargosDatalist.options.length; i++) {
                    if (cargosDatalist.options[i].value === initialValue) {
                        foundOption = cargosDatalist.options[i];
                        break;
                    }
                }
                if (foundOption) {
                    cargoIdHidden.value = foundOption.getAttribute('data-id');
                    cargoError.textContent = '';
                    cargoInput.classList.remove('input-error');
                } else {
                    cargoIdHidden.value = '';
                    cargoError.textContent = 'Por favor, seleccione un cargo válido de la lista.';
                    cargoInput.classList.add('input-error');
                }
            }
        });

        empleadoForm.addEventListener('submit', function(event) {
            let isValid = true;

            if (!validateInput(cedulaInput, cedulaError, /^[0-9]+$/, 'La Cédula solo debe contener números.')) isValid = false;
            if (!validateInput(nombreInput, nombreError, /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El Nombre solo debe contener letras y espacios.')) isValid = false;
            if (!validateInput(apellidoInput, apellidoError, /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El Apellido solo debe contener letras y espacios.')) isValid = false;
            if (fechaNacimientoInput.value === '') {
                fechaNacimientoError.textContent = 'La Fecha de Nacimiento es obligatoria.';
                fechaNacimientoInput.classList.add('input-error');
                isValid = false;
            } else {
                fechaNacimientoError.textContent = '';
                fechaNacimientoInput.classList.remove('input-error');
            }
            if (!validateInput(telefonoInput, telefonoError, /^[0-9\s\-\(\)]+$/, 'El Teléfono solo debe contener números, espacios, guiones o paréntesis.')) isValid = false;

            if (cargoIdHidden.value === '' || cargoInput.value === '') {
                cargoError.textContent = 'Por favor, seleccione un cargo válido de la lista.';
                cargoInput.classList.add('input-error');
                isValid = false;
            } else {
                cargoError.textContent = '';
                cargoInput.classList.remove('input-error');
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>