<?php
//$title ="Dashboard - ";
if (!isset($_SESSION)) {
  session_start();
}
include "head.php";
include "sidebar.php";
?>

<!--inicio de contadores-->
<div class="right_col" role="main"> <!-- page content -->
  <div class="">
    <div class="col-md-12">
      <div class="col-md-12">
        <h3 style="color:#31708F;">UNIBIENES NACIONAL</h3>
      </div>
      <div class="col-md-12">

        <div class="col-md-3">
          <div class="panel panel-default">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-user" style="font-size:40px;"></i>
              <p> Número de Clientes </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_clientes ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-info">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-th" style="font-size:40px;"></i>
              <p> Número de Cotizaciones </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_cotizacion ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-warning">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-th-list" style="font-size:40px;"></i>
              <p> Número de Polizas </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_polizas ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-off" style="font-size:40px;"></i>
              <p> Número de Siniestros </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_siniestros ?></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <h3 style="color:#31708F;">LA PAZ</h3>
      </div>
      <div class="col-md-12">

        <div class="col-md-3">
          <div class="panel panel-default">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-user" style="font-size:40px;"></i>
              <p> Número de Clientes </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_clienteslp ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-info">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-th" style="font-size:40px;"></i>
              <p> Número de Cotizaciones </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_cotizacionlp ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-warning">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-th-list" style="font-size:40px;"></i>
              <p> Número de Polizas </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_polizaslp ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-off" style="font-size:40px;"></i>
              <p> Número de Siniestros </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_siniestroslp ?></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <h3 style="color:#31708F;">SANTA CRUZ</h3>
      </div>
      <div class="col-md-12">

        <div class="col-md-3">
          <div class="panel panel-default">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-user" style="font-size:40px;"></i>
              <p> Número de Clientes </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_clientessc ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-info">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-th" style="font-size:40px;"></i>
              <p> Número de Cotizaciones </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_cotizacionsc ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-warning">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-th-list" style="font-size:40px;"></i>
              <p> Número de Polizas </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_polizassc ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-off" style="font-size:40px;"></i>
              <p> Número de Siniestros </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_siniestrossc ?></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <h3 style="color:#31708F;">COCHABAMBA</h3>
      </div>
      <div class="col-md-12">

        <div class="col-md-3">
          <div class="panel panel-default">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-user" style="font-size:40px;"></i>
              <p> Número de Clientes </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_clientescb ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-info">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-th" style="font-size:40px;"></i>
              <p> Número de Cotizaciones </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_cotizacioncb ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-warning">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-th-list" style="font-size:40px;"></i>
              <p> Número de Polizas </p>
            </div>
            <div class="panel-body">
              <div style="font-size:20px;"><?php echo $num_polizascb ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <i class="glyphicon glyphicon-off" style="font-size:40px;"></i>
              <p> Número de Siniestros </p>
            </div>
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
$con->close(); ?>

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