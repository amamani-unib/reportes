<?php
  //$title ="Dashboard - ";
  if(!isset($_SESSION)) {
    session_start();
  }
  include "head.php";
  include "sidebar.php";
  include "config/config.php";
  $con->query("SET NAMES 'utf8'");

// $sel = $con ->query("SELECT count(nombres) as clientes FROM unibienes.clientes");
// $fila = $sel -> fetch_assoc();
// $num_clientes = $fila['clientes'];

// $cot = $con ->query("SELECT count(placa) as cotizacion FROM unib_unibienes.automovil_cotizacion");
// $fila_cot = $cot -> fetch_assoc();
// $num_cotizacion = $fila_cot['cotizacion'];

// $pol = $con ->query("SELECT count(placa) as polizas FROM unib_unibienes.automovil WHERE estado='NUEVO'");
// $fila_pol = $pol -> fetch_assoc();
// $num_polizas = $fila_pol['polizas'];

// $sin = $con ->query("SELECT count(num_siniestro) as siniestro FROM unib_unibienes.siniestros");
// $fila_sin = $sin -> fetch_assoc();
// $num_siniestros = $fila_sin['siniestro'];

// $sellp = $con ->query("SELECT count(nombres) as clientes FROM unib_unibienes.clientes WHERE distrito='LA PAZ'");
// $filalp = $sellp -> fetch_assoc();
// $num_clienteslp = $filalp['clientes'];

// $cotlp = $con ->query("SELECT count(placa) as cotizacion FROM unib_unibienes.automovil_cotizacion WHERE distrito='LA PAZ'");
// $fila_cotlp = $cotlp -> fetch_assoc();
// $num_cotizacionlp = $fila_cotlp['cotizacion'];

// $pollp = $con ->query("SELECT count(placa) as polizas FROM unib_unibienes.automovil WHERE distrito='LA PAZ' and estado='NUEVO'");
// $fila_pollp = $pollp -> fetch_assoc();
// $num_polizaslp = $fila_pollp['polizas'];

// $sinlp = $con ->query("SELECT count(num_siniestro) as siniestro FROM unib_unibienes.siniestros WHERE distrito='LA PAZ'");
// $fila_sinlp = $sinlp -> fetch_assoc();
// $num_siniestroslp = $fila_sinlp['siniestro'];


// $selcb = $con ->query("SELECT count(nombres) as clientes FROM unib_unibienes.clientes WHERE distrito='COCHABAMBA'");
// $filacb = $selcb -> fetch_assoc();
// $num_clientescb = $filacb['clientes'];

// $cotcb = $con ->query("SELECT count(placa) as cotizacion FROM unib_unibienes.automovil_cotizacion WHERE distrito='COCHABAMBA'");
// $fila_cotcb = $cotcb -> fetch_assoc();
// $num_cotizacioncb = $fila_cotcb['cotizacion'];

// $polcb = $con ->query("SELECT count(placa) as polizas FROM unib_unibienes.automovil WHERE distrito='COCHABAMBA' and estado='NUEVO'");
// $fila_polcb = $polcb -> fetch_assoc();
// $num_polizascb = $fila_polcb['polizas'];

// $sincb = $con ->query("SELECT count(num_siniestro) as siniestro FROM unib_unibienes.siniestros WHERE distrito='COCHABAMBA'");
// $fila_sincb = $sincb -> fetch_assoc();
// $num_siniestroscb = $fila_sincb['siniestro'];


// $selsc = $con ->query("SELECT count(nombres) as clientes FROM unib_unibienes.clientes WHERE distrito='SANTA CRUZ'");
// $filasc = $selsc -> fetch_assoc();
// $num_clientessc = $filasc['clientes'];

// $cotsc = $con ->query("SELECT count(placa) as cotizacion FROM unib_unibienes.automovil_cotizacion WHERE distrito='SANTA CRUZ'");
// $fila_cotsc = $cotsc -> fetch_assoc();
// $num_cotizacionsc = $fila_cotsc['cotizacion'];

// $polsc = $con ->query("SELECT count(placa) as polizas FROM unib_unibienes.automovil WHERE distrito='SANTA CRUZ' and estado='NUEVO'");
// $fila_polsc = $polsc -> fetch_assoc();
// $num_polizassc = $fila_polsc['polizas'];

// $sinsc = $con ->query("SELECT count(num_siniestro) as siniestro FROM unib_unibienes.siniestros WHERE distrito='SANTA CRUZ'");
// $fila_sinsc = $sinsc -> fetch_assoc();
// $num_siniestrossc = $fila_sinsc['siniestro']; 
// ?>


<!--inicia-->






<!--fin-->

<!--inicion de contadores-->
<div class="right_col" role="main"> <!-- page content -->
  <div class="">
     <div class="col-md-12">
          <div class="col-md-12"><h3 style="color:#31708F;">UNIBIENES NACIONAL</h3></div>
            <div class="col-md-12">
                
                <div class="col-md-3">
                <div class="panel panel-default">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-user" style="font-size:40px;"></i>
                     <p> Número de Clientes </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_clientes ?></div>
                  </div>
                </div>
                </div>
                <div class="col-md-3">
                <div class="panel panel-info">
                  <div class="panel-heading">
                     <i class="glyphicon glyphicon-th" style="font-size:40px;"></i>
                     <p> Número de Cotizaciones </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_cotizacion ?></div>
                  </div>
                </div>
                </div><div class="col-md-3">
                <div class="panel panel-warning">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-th-list" style="font-size:40px;"></i>
                     <p> Número de Polizas </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_polizas ?></div>
                  </div>
                </div>
                </div><div class="col-md-3">
                <div class="panel panel-danger">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-off" style="font-size:40px;"></i>
                     <p> Número de Siniestros </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_siniestros ?></div>
                  </div>
                </div>
                </div>
                </div>
                
                <div class="col-md-12"><h3 style="color:#31708F;">LA PAZ</h3></div>
            <div class="col-md-12">
                
                <div class="col-md-3">
                <div class="panel panel-default">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-user" style="font-size:40px;"></i>
                     <p> Número de Clientes </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_clienteslp ?></div>
                  </div>
                </div>
                </div>
                <div class="col-md-3">
                <div class="panel panel-info">
                  <div class="panel-heading">
                     <i class="glyphicon glyphicon-th" style="font-size:40px;"></i>
                     <p> Número de Cotizaciones </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_cotizacionlp ?></div>
                  </div>
                </div>
                </div><div class="col-md-3">
                <div class="panel panel-warning">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-th-list" style="font-size:40px;"></i>
                     <p> Número de Polizas </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_polizaslp ?></div>
                  </div>
                </div>
                </div><div class="col-md-3">
                <div class="panel panel-danger">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-off" style="font-size:40px;"></i>
                     <p> Número de Siniestros </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_siniestroslp ?></div>
                  </div>
                </div>
                </div>
                </div>
                <div class="col-md-12"><h3 style="color:#31708F;">SANTA CRUZ</h3></div>
            <div class="col-md-12">
                
                <div class="col-md-3">
                <div class="panel panel-default">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-user" style="font-size:40px;"></i>
                     <p> Número de Clientes </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_clientessc ?></div>
                  </div>
                </div>
                </div>
                <div class="col-md-3">
                <div class="panel panel-info">
                  <div class="panel-heading">
                     <i class="glyphicon glyphicon-th" style="font-size:40px;"></i>
                     <p> Número de Cotizaciones </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_cotizacionsc ?></div>
                  </div>
                </div>
                </div><div class="col-md-3">
                <div class="panel panel-warning">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-th-list" style="font-size:40px;"></i>
                     <p> Número de Polizas </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_polizassc ?></div>
                  </div>
                </div>
                </div><div class="col-md-3">
                <div class="panel panel-danger">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-off" style="font-size:40px;"></i>
                     <p> Número de Siniestros </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_siniestrossc ?></div>
                  </div>
                </div>
                </div>
                </div>
                <div class="col-md-12"><h3 style="color:#31708F;">COCHABAMBA</h3></div>
            <div class="col-md-12">
                
                <div class="col-md-3">
                <div class="panel panel-default">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-user" style="font-size:40px;"></i>
                     <p> Número de Clientes </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_clientescb ?></div>
                  </div>
                </div>
                </div>
                <div class="col-md-3">
                <div class="panel panel-info">
                  <div class="panel-heading">
                     <i class="glyphicon glyphicon-th" style="font-size:40px;"></i>
                     <p> Número de Cotizaciones </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_cotizacioncb ?></div>
                  </div>
                </div>
                </div><div class="col-md-3">
                <div class="panel panel-warning">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-th-list" style="font-size:40px;"></i>
                     <p> Número de Polizas </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_polizascb ?></div>
                  </div>
                </div>
                </div><div class="col-md-3">
                <div class="panel panel-danger">
                  <div class="panel-heading">
                      <i class="glyphicon glyphicon-off" style="font-size:40px;"></i>
                     <p> Número de Siniestros </p></div>
                  <div class="panel-body">
                    <div style="font-size:20px;"><?php echo $num_siniestroscb ?></div>
                  </div>
                </div>
                </div>
               
                </div>
                
            </div>
            </div>
        </div>
      </div>
         
      


  
      
<?php include "footer.php";
$con->close();?>

<script>
  $('div.table-responsive').on("shown.bs.dropdown", ".dropdown", function() {

    var desplegable = $(this).children('ul.dropdown-menu');
    var boton = $(this).children(".dropdown-toggle");

    var separaciondesplegable = desplegable.offset();

    var espacioArriba = (separaciondesplegable.top - boton.height() - desplegable.height()) - $(window).scrollTop();

    var espacioAbajo = $(window).scrollTop() + $(window).height() - (separaciondesplegable.top + desplegable.height());

    if (espacioAbajo < 0 && (espacioArriba >= 0 || espacioArriba > espacioAbajo))
        $(this).addClass("dropup");

  }).on("hidden.bs.dropdown", ".dropdown", function() {
      $(this).removeClass("dropup");
  });
</script>



