<?php

// Asegura que las rutas a los modelos sean absolutas usando __DIR__
// Esto evita problemas si el archivo controlador es incluido desde diferentes ubicaciones.
require_once __DIR__ . "/../modelos/usuarios.modelo.php";
require_once __DIR__ . "/../modelos/empleados.modelo.php";
require_once __DIR__ . "/../modelos/eventolog.modelo.php";

class ControladorUsuarios{

    /*=============================================
    INGRESO DE USUARIO
    =============================================*/

    static public function ctrIngresoUsuario(){

        if(isset($_POST["ingUsuario"])){

            if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingUsuario"])){

                $encriptar = crypt($_POST["ingPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                $tabla = "usuarios";
                $item = "usuario";
                $valor = $_POST["ingUsuario"];
                $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);

                if (empty($respuesta)) {
                    // La lógica para registrar "usuario no encontrado" ha sido ELIMINADA.
                    echo '<br><div class="alert alert-danger">Usuario no encontrado</div>';
                } else {
                    if($respuesta["usuario"] == $_POST["ingUsuario"] && $respuesta["password"] == $encriptar){

                        if($respuesta["estado"] == 1){

                            $_SESSION["iniciarSesion"] = "ok";
                            $_SESSION["cedula"] = $respuesta["cedula"];
                            $_SESSION["nombre"] = $respuesta["nombre"];
                            $_SESSION["usuario"] = $respuesta["usuario"];
                            $_SESSION["foto"] = $respuesta["foto"];
                            $_SESSION["perfil"] = $respuesta["perfil"];

                            /*=============================================
                            REGISTRAR FECHA PARA SABER EL ÚLTIMO LOGIN (Si la habilitas, puedes loggearla aquí)
                            =============================================*/
                            // date_default_timezone_set('America/Caracas');
                            // $fecha = date('Y-m-d');
                            // $hora = date('H:i:s');
                            // $fechaActual = $fecha.' '.$hora;
                            // $item1 = "ultimo_login";
                            // $valor1 = $fechaActual;
                            // $ultimoLogin = ModeloUsuarios::mdlActualizarUsuario($tabla, $item1, $valor1); // Asegúrate de que este método exista y funcione

                            // =============================================
                            // REGISTRAR LOGIN EXITOSO EN EL LOG DE EVENTOS
                            // =============================================
                            $logData = array(
                                "event_type" => "Login Exitoso",
                                "description" => "El empleado '{$_SESSION["usuario"]}' ({$_SESSION["perfil"]}) ha iniciado sesión.",
                                "employee_cedula" => $_SESSION["cedula"],
                                "affected_table" => "usuarios",
                                "affected_row_id" => $_SESSION["usuario"]
                            );
                            ModeloEventoLog::mdlGuardarEvento($logData);


                            echo '<script>
                                        window.location = "inicio";
                                    </script>';

                        }else{
                            // =============================================
                            // REGISTRAR INTENTO DE LOGIN CON USUARIO INACTIVO
                            // =============================================
                            $logData = array(
                                "event_type" => "Login Fallido",
                                "description" => "Intento de login con usuario inactivo: '{$_POST["ingUsuario"]}'.",
                                "employee_cedula" => $respuesta["cedula"], // Si la cédula existe en la respuesta
                                "affected_table" => "usuarios",
                                "affected_row_id" => $_POST["ingUsuario"]
                            );
                            ModeloEventoLog::mdlGuardarEvento($logData);

                            echo '<br>
                                <div class="alert alert-danger">El usuario aún no está activado</div>';

                        }

                    }else{
                        // =============================================
                        // REGISTRAR INTENTO DE LOGIN CON CONTRASEÑA INCORRECTA
                        // =============================================
                        $logData = array(
                            "event_type" => "Login Fallido",
                            "description" => "Contraseña incorrecta para el usuario: '{$_POST["ingUsuario"]}'.",
                            "employee_cedula" => $respuesta["cedula"], // Si la cédula existe en la respuesta
                            "affected_table" => "usuarios",
                            "affected_row_id" => $_POST["ingUsuario"]
                        );
                        ModeloEventoLog::mdlGuardarEvento($logData);

                        echo '<br><div class="alert alert-danger">Error al ingresar, contraseña incorrecta</div>';

                    }
                }


            }

        }

    }

    /*=============================================
    REGISTRO DE USUARIO
    =============================================*/

    static public function ctrCrearUsuario(){

        if(isset($_POST["nuevoUsuario"])){

            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ -]+$/', $_POST["nuevaCedula"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoUsuario"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoPassword"])){

                /*=============================================
                VALIDAR IMAGEN (Comentado en tu original, se mantiene así)
                =============================================*/

                $tabla = "usuarios";

                $encriptar = crypt($_POST["nuevoPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                $datos = array("cedula" => $_POST["nuevaCedula"],
                                "usuario" => $_POST["nuevoUsuario"],
                                "password" => $encriptar,
                                "perfil" => $_POST["nuevoPerfil"]);

                $respuesta = ModeloUsuarios::mdlIngresarUsuario($tabla, $datos);

                if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR CREACIÓN DE USUARIO EN EL LOG
                    // =============================================
                    // Intentamos obtener la cédula del usuario que está logueado y crea a este nuevo usuario.
                    // Si no hay sesión (ej. primer usuario creado), usamos "Sistema/Anonimo".
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Anonimo";

                    $logData = array(
                        "event_type" => "Usuario Creado",
                        "description" => "Nuevo usuario '{$_POST["nuevoUsuario"]}' (Cédula: {$_POST["nuevaCedula"]}) con perfil '{$_POST["nuevoPerfil"]}' fue creado.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => "usuarios",
                        "affected_row_id" => (isset($_POST["nuevaCedula"])) ? $_POST["nuevaCedula"] : 'N/A' // Usamos la cédula del nuevo usuario como ID afectado
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

                    echo '<script>
                    swal({
                        type: "success",
                        title: "¡El usuario ha sido guardado correctamente!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if(result.value){
                            window.location = "usuarios";
                        }
                    });
                    </script>';

                }else if($respuesta == "repetido"){ // Corregido de '=' a '=='
                    // =============================================
                    // REGISTRAR INTENTO DE CREACIÓN DE USUARIO REPETIDO
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Anonimo"; // Quien intenta crear el usuario
                    $logData = array(
                        "event_type" => "Creación Usuario Fallida",
                        "description" => "Intento de crear usuario '{$_POST["nuevoUsuario"]}' con cédula '{$_POST["nuevaCedula"]}' fallido por ser repetido.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => "usuarios",
                        "affected_row_id" => $_POST["nuevaCedula"]
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

                    echo'<script>
                    swal({
                        type: "error",
                        title: "El usuario ya existe",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result) {
                                if (result.value) {
                                window.location = "usuarios";
                                }
                            })
                    </script>';
                }


            }else{
                // =============================================
                // REGISTRAR INTENTO DE CREACIÓN DE USUARIO CON DATOS INVÁLIDOS
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Anonimo"; // Quien intenta crear el usuario
                $logData = array(
                    "event_type" => "Creación Usuario Fallida",
                    "description" => "Intento de crear usuario con datos inválidos (Usuario: " . (isset($_POST["nuevoUsuario"]) ? $_POST["nuevoUsuario"] : 'N/A') . ", Cédula: " . (isset($_POST["nuevaCedula"]) ? $_POST["nuevaCedula"] : 'N/A') . ").",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "usuarios",
                    "affected_row_id" => (isset($_POST["nuevaCedula"])) ? $_POST["nuevaCedula"] : 'N/A'
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo '<script>
                    swal({
                        type: "error",
                        title: "¡El usuario y contraseña puede incluir números y letras pero no puede ir vacío o llevar caracteres especiales !",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if(result.value){
                            window.location = "usuarios";
                        }
                    });
                </script>';

            }
        }
    }

    /*=============================================
    MOSTRAR USUARIO
    =============================================*/

    static public function ctrMostrarUsuarios($item, $valor){

        $tabla = "usuarios";

        $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);

        return $respuesta;
    }

    /*=============================================
    EDITAR USUARIO
    =============================================*/

    static public function ctrEditarUsuario(){

        if(isset($_POST["editarUsuario"])){

            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarUsuario"])){

                /*=============================================
                VALIDAR IMAGEN (Comentado en tu original, se mantiene así)
                =============================================*/
                // ... (código de validación de imagen) ...

                $tabla = "usuarios";
                // Asumo que $_POST["editarCedula"] contiene la cédula del usuario que se está editando
                $cedulaUsuarioEditado = $_POST["editarCedula"];

                if($_POST["editarPassword"] != ""){

                    if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["editarPassword"])){

                        $encriptar = crypt($_POST["editarPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                    }else{
                        // =============================================
                        // REGISTRAR INTENTO DE EDICIÓN CON CONTRASEÑA INVÁLIDA
                        // =============================================
                        $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                        $logData = array(
                            "event_type" => "Edición Usuario Fallida",
                            "description" => "Intento de editar usuario '{$_POST["editarUsuario"]}' con contraseña inválida.",
                            "employee_cedula" => $empleadoCedula,
                            "affected_table" => "usuarios",
                            "affected_row_id" => $cedulaUsuarioEditado
                        );
                        ModeloEventoLog::mdlGuardarEvento($logData);

                        echo'<script>
                                swal({
                                    type: "error",
                                    title: "¡La contraseña no puede ir vacía o llevar caracteres especiales!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar"
                                    }).then(function(result) {
                                        if (result.value) {
                                        window.location = "usuarios";
                                        }
                                    })
                            </script>';

                        return;

                    }

                }else{

                    $encriptar = $_POST["passwordActual"];

                }

                $datos = array("cedula" => $cedulaUsuarioEditado, // Asegúrate de que la cédula se pase correctamente
                                "usuario" => $_POST["editarUsuario"],
                                "password" => $encriptar,
                                "perfil" => $_POST["editarPerfil"]
                            );

                $respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);

                if($respuesta == "ok"){
                    // =============================================
                    // REGISTRAR EDICIÓN DE USUARIO EN EL LOG
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Usuario Editado",
                        "description" => "Usuario '{$_POST["editarUsuario"]}' (Cédula: {$cedulaUsuarioEditado}) editado con perfil '{$_POST["editarPerfil"]}'.",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => "usuarios",
                        "affected_row_id" => $cedulaUsuarioEditado
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);

                    echo'<script>
                    swal({
                        type: "success",
                        title: "El usuario ha sido editado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result) {
                                if (result.value) {
                                window.location = "usuarios";
                                }
                            })
                    </script>';

                } else {
                    // =============================================
                    // REGISTRAR FALLO EN EDICIÓN DE USUARIO
                    // =============================================
                    $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                    $logData = array(
                        "event_type" => "Edición Usuario Fallida",
                        "description" => "Error al intentar editar usuario '{$_POST["editarUsuario"]}' (Cédula: {$cedulaUsuarioEditado}).",
                        "employee_cedula" => $empleadoCedula,
                        "affected_table" => "usuarios",
                        "affected_row_id" => $cedulaUsuarioEditado
                    );
                    ModeloEventoLog::mdlGuardarEvento($logData);
                }


            }else{
                // =============================================
                // REGISTRAR INTENTO DE EDICIÓN CON NOMBRE DE USUARIO INVÁLIDO
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Edición Usuario Fallida",
                    "description" => "Intento de editar usuario con nombre de usuario inválido (Usuario: " . (isset($_POST["editarUsuario"]) ? $_POST["editarUsuario"] : 'N/A') . ").",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "usuarios",
                    "affected_row_id" => (isset($_POST["editarCedula"])) ? $_POST["editarCedula"] : 'N/A' // Si se envía la cédula incluso con nombre inválido
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo'<script>
                    swal({
                        type: "error",
                        title: "¡El nombre no puede ir vacío o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then(function(result) {
                            if (result.value) {

                            window.location = "usuarios";

                            }
                        })

                </script>';

            }

        }

    }

    /*=============================================
    BORRAR USUARIO
    =============================================*/

    static public function ctrBorrarUsuario(){

        if(isset($_GET["idUsuario"])){ // Aquí idUsuario es el 'usuario' (nombre de usuario) a borrar

            $tabla ="usuarios";
            $datos = $_GET["idUsuario"]; // Esto parece ser el nombre de usuario, no la cédula

            // Primero, obtenemos la cédula del usuario a borrar para un log más preciso
            // Es importante hacer esto ANTES de intentar borrarlo.
            $usuarioABorrar = ModeloUsuarios::MdlMostrarUsuarios($tabla, "usuario", $datos);
            // Reemplazo de ?? por (isset(...) ? ... : ...)
            $cedulaUsuarioBorrado = (isset($usuarioABorrar['cedula'])) ? $usuarioABorrar['cedula'] : 'N/A';
            $nombreUsuarioBorrado = (isset($usuarioABorrar['usuario'])) ? $usuarioABorrar['usuario'] : $datos; // Usar el nombre obtenido, o el que vino en el GET

            // if($_GET["fotoUsuario"] != ""){ // Comentado en tu original
            //  unlink($_GET["fotoUsuario"]);
            //  rmdir('vistas/img/usuarios/'.$_GET["usuario"]);
            // }

            $respuesta = ModeloUsuarios::mdlBorrarUsuario($tabla, $datos);

            if($respuesta == "ok"){
                // =============================================
                // REGISTRAR ELIMINACIÓN DE USUARIO EN EL LOG
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Usuario Eliminado",
                    "description" => "Usuario '{$nombreUsuarioBorrado}' (Cédula: {$cedulaUsuarioBorrado}) eliminado.",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "usuarios",
                    "affected_row_id" => $cedulaUsuarioBorrado // Usamos la cédula como ID afectado si está disponible
                );
                ModeloEventoLog::mdlGuardarEvento($logData);

                echo'<script>
                swal({
                    type: "success",
                    title: "El usuario ha sido borrado correctamente",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar",
                    closeOnConfirm: false
                    }).then(function(result) {
                                if (result.value) {
                                window.location = "usuarios";
                                }
                            })
                </script>';
            } else {
                // =============================================
                // REGISTRAR FALLO AL ELIMINAR USUARIO
                // =============================================
                $empleadoCedula = (isset($_SESSION["cedula"])) ? $_SESSION["cedula"] : "Sistema/Desconocido";
                $logData = array(
                    "event_type" => "Eliminación Usuario Fallida",
                    "description" => "Error al intentar eliminar usuario '{$nombreUsuarioBorrado}' (Cédula: {$cedulaUsuarioBorrado}).",
                    "employee_cedula" => $empleadoCedula,
                    "affected_table" => "usuarios",
                    "affected_row_id" => $cedulaUsuarioBorrado
                );
                ModeloEventoLog::mdlGuardarEvento($logData);
            }
        }
    }
}