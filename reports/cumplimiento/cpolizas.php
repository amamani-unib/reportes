<?php

$msj_log = "REPORTE DE POLIZAS POR COMULO";
$consulta = "SELECT pc.f_registro, pc.cod_cliente,pc.cod_cotizacion,pc.cod_poliza,rc.asegurado, rc.tomador, pc.prima_total,rc.tipo_cartera 
              FROM comercial.primas_cobranzas as pc INNER JOIN comercial.pol_reporte_comercial as rc ON rc.cod_poliza= pc.cod_poliza
              WHERE pc.prima_total < 5000 and pc.estado<>'ELIMINADO' and ";
//$row = mysqli_fetch_assoc($result);

if (!isset($_POST['cb_lapso'])) {
  $consulta .= " pc.f_registro like '%$fecha_dia%'";
  $titulo = "VISTA PREVIA DE REPORTE CUMULO POR PÓLIZA DEL $fecha_dia";
} else {
  $consulta .= " pc.f_registro >= '$fecha_inicio' and pc.f_registro <= '$fecha_final'";
  $titulo = "VISTA PREVIA DE REPORTE CUMULO POR PÓLIZA DESDE $fecha_inicio HASTA $fecha_final";
}
$consulta .= " GROUP BY pc.cod_poliza ORDER BY pc.cod_poliza DESC";
//$consulta .= " and cod_siniestro='SIAUS00010234'";
//$consulta;

$resultado = mysqli_query($con, $consulta);

?>
<h2 align="center"><?= $titulo ?> </h2>
<br>
<div id="datos_reportes" class="table-responsive table">
  <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
    <thead>
      <tr class='text-center'>
        <th>FECHA DE REGISTRO</th>
        <th>COD. CLIENTE</th>
        <th>COD. POLIZA</th>
        <th>COD. COTIZACION</th>
        <th>TOMADOR</th>
        <th>ASEGURADO</th>
        <th>TIPO CARTERA</th>
        <th>PRIMA TOTAL</th>
      </tr>
    </thead>
    <tbody>
      <?php
      while ($row = mysqli_fetch_assoc($resultado)) {
        $query_estado = "SELECT SUM(prima_total) FROM comercial.primas_cobranzas
                                   WHERE cod_poliza = '" . $row['cod_poliza'] . "' and estado='PAGADO' and prima_total<5000 AND";

        if (!isset($_POST['cb_lapso'])) {
          $query_estado .= " f_registro like '%$fecha_dia%'";
        } else {
          $query_estado .= " f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
        }
        $query_estado;
        $res = mysqli_query($con, $query_estado);
        $f = mysqli_fetch_assoc($res);
        $prima_total_s = $f['SUM(prima_total)'];
        if ($prima_total_s > 5000) {
      ?><tr>
            <td><?php echo $row['f_registro']; ?></td>
            <td><?php echo $row['cod_cliente']; ?></td>
            <td><?php echo $row['cod_poliza']; ?></td>
            <td><?php echo $row['cod_cotizacion']; ?></td>
            <td><?php echo $row['tomador']; ?></td>
            <td><?php echo $row['asegurado']; ?></td>
            <td><?php echo $row['tipo_cartera']; ?></td>
            <td><?php echo $prima_total_s; ?></td>
          </tr>
      <?php
        }
      }
      ?>
    </tbody>
    <?php echo $script_tabla; ?>
  </table>
</div>