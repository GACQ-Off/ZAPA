<div id="back"></div>
<div class="login-box" style="background-color: #fff;border: 5px solid #3c8dbc; width: 350px; height: 350px; ">
  <div class="login-logo">
    <a><b>  &nbsp;&nbsp;Ventas  </b> Kamyl Style&nbsp;&nbsp; </a>

  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Ingresa al Sistema</p>
    <form method="post">

    <form action="../../index2.html" method="post">
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
        <!-- /.col -->
      </div>

      <?php

        $login = new ControladorUsuarios();
        $login -> ctrIngresoUsuario();
        
      ?>
    </form>

    

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->