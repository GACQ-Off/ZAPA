<div id="back"></div>
<div class="login-box" style="display: flex; width: 800px; height: 400px; margin: 50px auto;">
    <div style="flex: 1; text-align: center; display: flex; align-items: center; justify-content: center;">
        <img src="logo.png" alt="Logo de Kamyl Style" style="max-width: 80%; max-height: 80%; object-fit: contain;">
    </div>
    <div class="login-box-body" style="flex: 1; background-color: #fff; border: 5px solid #3c8dbc;">
        <p class="login-box-msg">Ingresa al Sistema</p>
        <form method="post" action="login.php">
            <a><b>Nombre de Usuario</b></a>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Usuario" name="ingUsuario" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <a><b>Clave de Usuario</b></a>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Clave" name="ingPassword" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
                </div>
            </div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $login = new ControladorUsuarios();
                $login->ctrIngresoUsuario();
            }
            ?>
        </form>
    </div>
</div>