<?php
    
    include "head.php";
    include "sidebar.php"; 
?>
     <div class="right_col" role="main"><!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="clearfix"></div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                      <?php if($tipo_usuario == "admin") { ?> 
                        <?php
                            include("modal/new_ticket.php");
                            include("modal/upd_ticket.php");
                        ?>
                     <?php } ?>
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Datos del Cliente</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="far fa-user"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <?php if($tipo_usuario == "admin") { ?> 
                        <!-- form seach -->
                        <form class="form-horizontal" role="form" id="gastos">
                            <div class="form-group row">
                                <label for="q" class="col-md-2 control-label">Nombre/Asunto</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="q" placeholder="Nombre del ticket" onkeyup='load(1);'>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-default" onclick='load(1);'>
                                        <span class="glyphicon glyphicon-search" ></span> Buscar</button>
                                    <span id="loader"></span>
                                </div>
                            </div>
                        </form> 
                        <?php } ?>    
                        <!-- end form seach -->
                        <h3>Formulario de Registro</h3>
                       <form action="insert.php" method="POST">
                           <div class="col-md-4">
                             <label for="inputEmail4" class="form-label">Nombre</label>
                             <input type="text" class="form-control" name="nombre" id="nombre" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Celular</label>
                             <input type="Telefono" class="form-control" name="celular" id="celular" required >
                           </div>
                           <div class="col-md-5">
                             <label for="inputPassword4" class="form-label">Email</label>
                             <input type="email" class="form-control" name="email" id="email">
                           </div>
                           <div class="col-md-4">
                             <label for="inputEmail4" class="form-label">Direccion</label>
                             <input type="text" class="form-control" name="direccion" id="direccion" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Telefono Fijo</label>
                             <input type="Telefono" class="form-control" name="tel_fijo" id="tel_fijo" >
                           </div>
                           <div class="col-md-5">
                             <label for="inputPassword4" class="form-label">Fax</label>
                             <input type="Telefono" class="form-control" name="fax" id="fax" >
                           </div>
                           <div class="col-md-2">
                             <label for="inputEmail4" class="form-label">CI/NIT</label>
                             <input type="number" class="form-control" name="ci_nit" id="ci_nit" required >
                           </div>
                           <div class="col-md-1">
                             <label for="inputPassword4" class="form-label">Extencion</label>
                             <input type="text" class="form-control" name="extencion" id="extencion" pattern="[A-Za-z]{2}" >
                           </div>
                           <div class="col-md-5">
                             <label for="inputPassword4" class="form-label">Estacion</label>
                             <input type="text" class="form-control" name="estacion" id="estacion"required>
                           </div>
                           <div class="col-md-2">
                             <label for="inputPassword4" class="form-label">Disparador IDH</label>
                             <input type="text" class="form-control" name="dis_idh" id="dis_idh" required>
                           </div>
                           <div class="col-md-2">
                             <label for="inputPassword4" class="form-label">Periodo Meses</label>
                             <input type="number" class="form-control" name="periodo_mes" id="periodo_mes" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Superficie</label>
                             <input type="number" class="form-control" name="superficie" id="superficie" step="any" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Hectarias Propias</label>
                             <input type="number" class="form-control" name="h_propias" id="h_propias" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Hectarias Alquiladas</label>
                             <input type="number" class="form-control" name="h_alquiladas" id="h_alquiladas" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Cantidad de Parcelas</label>
                             <input type="number" class="form-control" name="c_parcelas" id="c_parcelas" required>
                           </div>
                           <div class="col-md-8">
                             <label for="inputPassword4" class="form-label">Descripcion de Garantia</label>
                             <input type="text" class="form-control" name="descripcion" id="descripcion" required>
                           </div>
                           <div class="col-md-4">
                             <label for="inputPassword4" class="form-label">Riesgo</label>
                             <input type="text" class="form-control" name="riesgo" id="riesgo" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Municipio</label>
                             <input type="text" class="form-control" name="municipio" id="municipio" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Comunidad</label>
                             <input type="text" class="form-control" name="comunidad" id="comunidad" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Nucleo</label>
                             <input type="text" class="form-control" name="nucleo" id="nucleo" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Colonia</label>
                             <input type="text" class="form-control" name="colonia" id="colonia" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Nombre Predio</label>
                             <input type="text" class="form-control" name="n_predio" id="n_predio" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Monto Desembolsar</label>
                             <input type="number" class="form-control" name="m_desembolso" id="m_desembolso" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Numero de Tramite</label>
                             <input type="number" class="form-control" name="n_tramite" id="n_tramite" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Banca</label>
                             <input type="text" class="form-control" name="banca" id="banca" required>
                           </div>
                           <div class="col-md-6">
                             <label for="inputPassword4" class="form-label">Agencia</label>
                             <input type="text" class="form-control" name="agencia" id="agencia" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Codigo de Producto</label>
                             <input type="text" class="form-control" name="cod_producto" id="cod_producto" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Inicio de Vigencia</label>
                             <input type="date" class="form-control" name="inicio_v" id="inicio_v" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" required>
                           </div>
                           <div class="col-md-3">
                             <label for="inputPassword4" class="form-label">Fin de Vigencia</label>
                             <input type="date" class="form-control" name="fin_v" id="fin_v" required>
                           </div>
                           <div class="col-md-2">
                             <label for="inputPassword4" class="form-label">Fecha Movimiento</label>
                             <input type="date" class="form-control" name="f_movimiento" id="f_movimiento" required >
                           </div>
                           <br><br>
                           <div class="col-md-6">
                               <div id="respuesta"></div>
                           </div>
                           <br><br>
                          <div class="col-12" style="font-size:40px;">
                             <button type="submit" class="btn btn-primary">ENVIAR</button>
                          </div>

                      </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /page content -->
<?php include "footer.php" ?>

<script type="text/javascript" src="js/project.js"></script>
<script type="text/javascript" src="js/VentanaCentrada.js"></script>
<script>
$( "#add" ).submit(function( event ) {
  $('#save_data').attr("disabled", true);
  
 var parametros = $(this).serialize();
     $.ajax({
            type: "POST",
            url: "action/addproject.php",
            data: parametros,
             beforeSend: function(objeto){
                $("#result").html("Mensaje: Cargando...");
              },
            success: function(datos){
            $("#result").html(datos);
            $('#save_data').attr("disabled", false);
            load(1);
          }
    });
  event.preventDefault();
})

// success

$( "#upd" ).submit(function( event ) {
  $('#upd_data').attr("disabled", true);
  
 var parametros = $(this).serialize();
     $.ajax({
            type: "POST",
            url: "action/updproject.php",
            data: parametros,
             beforeSend: function(objeto){
                $("#result2").html("Mensaje: Cargando...");
              },
            success: function(datos){
            $("#result2").html(datos);
            $('#upd_data').attr("disabled", false);
            load(1);
          }
    });
  event.preventDefault();
})

    function obtener_datos(id){
            var description = $("#description"+id).val();
            var name = $("#name"+id).val();
            $("#mod_id").val(id);
            $("#mod_description").val(description);
            $("#mod_name").val(name);
        }


</script>