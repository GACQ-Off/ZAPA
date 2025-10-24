<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Mantenimiento
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Mantenimiento</li>
        </ol>
    </section>

    <section class="content">

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Herramientas de Base de Datos</h3>
            </div>
           <div class="box-body">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <form class="small-box bg-red" method="post">
                <div class="inner" style="text-align: center;">
                    <input type="hidden" name="exportar">
                    <h3>Exportar</h3>
                    <p>Base de Datos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-database"></i>
                    <i class="fa fa-arrow-down"></i>
                </div>
                <button type="submit" class="small-box-footer" style="border: none; background: none; width: 100%; text-align: center; padding: 10px 15px; color: inherit; cursor: pointer;">
                    Iniciar Exportación <i class="fa fa-arrow-circle-right"></i>
                </button>
            </form>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
            <form class="small-box bg-orange" method="post">
                <div class="inner" style="text-align: center;">
                    <input type="hidden" name="importar">
                    <h3>Importar</h3>
                    <p>Base de Datos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-database"></i>
                    <i class="fa fa-arrow-up"></i>
                </div>
                <button type="submit" class="small-box-footer" style="border: none; background: none; width: 100%; text-align: center; padding: 10px 15px; color: inherit; cursor: pointer;">
                    Iniciar Importación <i class="fa fa-arrow-circle-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>
                <?php
                // Asegúrate de que $_SESSION["perfil"] esté definida y se use correctamente
                if(isset($_SESSION["perfil"]) && ($_SESSION["perfil"] =="Especial" || $_SESSION["perfil"] =="Vendedor")){
                    echo '<div class="box box-success">
                            <div class="box-header">
                                <h3 class="box-title text-center" style="width: 100%;">Bienvenid@ ' . (isset($_SESSION["nombre"]) ? $_SESSION["nombre"] : '') . '</h3>
                            </div>
                          </div>';
                }
                ?>
            </div>
        </div>

    </section>
    </div>

<script>
    // Script para la sumisión del formulario al hacer clic en cualquier parte del small-box
    $("form.small-box").on("click", (e) => {
        // Prevenir la acción por defecto si se hizo clic en el enlace del footer
        if (!$(e.target).is('a')) {
            e.currentTarget.submit();
        }
    });

    // Script para el SweetAlert de logout (copiado tal cual de tu código)
    $("#logout").click(function(e) {
        e.preventDefault();

        swal({
            type: "warning",
            title: "¿Estás seguro de salir?",
            text: "Esta acción cerrará tu sesión.",
            showCancelButton: true,
            confirmButtonColor: "#3498db",
            confirmButtonText: "Sí, salir",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false
        }).then(function(result) {
            if (result.value) {
                window.location = "salir";
            }
        });
    });
</script>