<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Kamyl Style Ventas</title>
    <link rel="stylesheet" href="css/style.css">
    
    <link rel="stylesheet" href="css/all.min.css"> 
    
    </head>
<body>
    <div id="back" class="background-container"></div> <div class="main-layout">
        <div class="left-panel">
            <img src="img/logo2.png" alt="Kamyl Style Logo Grande" class="large-logo">
            </div>

        <div class="right-panel">
            <div class="login-container">
                <div class="login-header">
                    <div class="header-content">
           
                        <h2 class="main-title"><b>Sistema</b> De  Ventas</h2>
                    </div>
                </div>
                
                <div class="login-body">
                    <p class="login-msg">Ingresa al Sistema</p>
                    
                    <form method="post"> 
                        <div class="form-group">
                            <label for="ingUsuario">Nombre de Usuario</label>
                            <div class="input-icon-group">
                                <input type="text" id="ingUsuario" class="form-control" placeholder="Usuario" name="ingUsuario" required>
                                <span class="icon"><i class="fas fa-user"></i></span> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="ingPassword">Clave de Usuario</label>
                            <div class="input-icon-group">
                                <input type="password" id="ingPassword" class="form-control" placeholder="Clave" name="ingPassword" required>
                                <span class="icon"><i class="fas fa-lock"></i></span> 
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Ingresar</button>
                        </div>

                        <?php
                            $login = new ControladorUsuarios();
                            $login -> ctrIngresoUsuario();
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>