<?php
include_once "utils/items.php";
?>
<h3 style="text-align:center; font-family:'Courier New', Courier, monospace" align="center">VISTA PREVIA REPORTE PARA UNIDAD DE CUMPLIMIENTO</h3>
<table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
  <thead>
    <tr class='text-center'>
      <th style="text-align: center; border:1px solid black">Nro. REGISTRO</th>
      <th style="text-align: center; border:1px solid black">MES</th>
      <th style="text-align: center; border:1px solid black"><?= utf8_decode('AÑOS') ?></th>
      <th style="text-align: center; border:1px solid black"><?= utf8_decode('Nro. PÓLIZA') ?></th>
      <th style="text-align: center; border:1px solid black">Cod. CLIENTE</th>
      <th style="text-align: center; border:1px solid black">REGIONAL</th>
      <th style="text-align: center; border:1px solid black">TOMADOR</th>
      <th style="text-align: center; border:1px solid black">ASEGURADO</th>
      <th style="text-align: center; border:1px solid black">RAMO</th>
      <th style="text-align: center; border:1px solid black">TIPO POLIZA</th>
      <th style="text-align: center; border:1px solid black">TIPO MOVIMIENTO</th>
      <th style="text-align: center; border:1px solid black">NRO. ANEXO</th>
      <th style="text-align: center; border:1px solid black">ASISTENCIA VIAL</th>
      <th style="text-align: center; border:1px solid black">CANTIDAD DE ITEMS</th>
      <th style="text-align: center; border:1px solid black">OBSERVACIONES</th>
      <th style="text-align: center; border:1px solid black">VALOR ASEGURADO</th>
      <th style="text-align: center; border:1px solid black">VALOR PRIMERA</th>
      <th style="text-align: center; border:1px solid black">TERREMOTO</th>
      <th style="text-align: center; border:1px solid black">TERRORISMO</th>
      <th style="text-align: center; border:1px solid black">PRIMA TOTAL</th>
      <th style="text-align: center; border:1px solid black">MONEDA</th>
      <th style="text-align: center; border:1px solid black">FACTOR DE NETO</th>
      <th style="text-align: center; border:1px solid black">PRIMA NETA</th>
      <th style="text-align: center; border:1px solid black">FECHA DE EMISION</th>
      <th style="text-align: center; border:1px solid black">FECHA INICIO DE VIGENCIA</th>
      <th style="text-align: center; border:1px solid black">FECHA FIN DE VIGENCIA</th>
      <th style="text-align: center; border:1px solid black">DÍAS DE VIGENCIA</th>
      <th style="text-align: center; border:1px solid black">SECTOR</th>
      <th style="text-align: center; border:1px solid black">TIPO DE CARTERA</th>
      <th style="text-align: center; border:1px solid black">SUBTIPO DE CARTERA</th>
      <th style="text-align: center; border:1px solid black">INTERMEDIARIO</th>
      <th style="text-align: center; border:1px solid black">TIPO PAGO</th>
      <th style="text-align: center; border:1px solid black">COMISION INTERMEDIARIO GNV</th>
      <th style="text-align: center; border:1px solid black">COMISION INTERMEDIARIO</th>
      <th style="text-align: center; border:1px solid black">NUMERO DE CUOTAS</th>
      <th style="text-align: center; border:1px solid black">FECHA Y HORA DE REGISTRO</th>
      <th style="text-align: center; border:1px solid black">USUARIO</th>
      <th style="text-align: center; border:1px solid black">EMISOR</th>
      <th style="text-align: center; border:1px solid black">MODALIDAD</th>

      <th style="text-align: center; border:1px solid black">USO</th>
      <th style="text-align: center; border:1px solid black">CLASE</th>
      <th style="text-align: center; border:1px solid black"><?= utf8_decode('PLAZA DE CIRCULACIÓN') ?></th>
      <th style="text-align: center; border:1px solid black"><?= utf8_decode('AÑO') ?></th>
      <th style="text-align: center; border:1px solid black">MARCA</th>
      <th style="text-align: center; border:1px solid black">MODELO</th>
      <th style="text-align: center; border:1px solid black">PLACA</th>
    </tr>
    </tr>
  </thead>
  <tbody>
    <?php
    $consulta = "SELECT pc.id_calculo_prima,month(pc.f_registro) as mes,year(pc.f_registro) as anio, rc.cod_poliza,rc.cod_cliente, rc.regional,rc.tomador,pc.asegurado, rc.cod_cotizacion, rc.ramo,rc.tipo_poliza,pc.movimiento,rc.nro_anexo, rc.asi_vial, rc.cant_autos, pc.observaciones, ROUND(pc.valor_asegurado,2), ROUND(pc.valor_primera_perdida,2),ROUND(pc.valor_terremoto,2), ROUND(pc.valor_terrorismo,2),  ROUND (case when pc.tipo_pago='CONTADO' THEN pc.prima_contado when pc.tipo_pago='CREDITO' THEN pc.prima_credito END ,2) as prima_total_c,  rc.moneda,pc.p_factor_tasa_tecnica, ROUND(pc.prima_neta,2),pc.fecha_emision,pc.fecha_inicio,pc.fecha_fin, pc.dias_transcurridos,rc.tipo_cartera, rc.subtipo_cartera, pc.tipo_pago, pc.emisor, rc.intermediario,ROUND(pc.com_intermediario_gnv,2),ROUND(pc.com_intermediario,2),pc.num_cuota, pc.f_registro,pc.usuario,rc.modalidad 
        FROM comercial.pol_reporte_comercial as rc INNER JOIN comercial.pol_calculo_prima as pc ON rc.cod_poliza=pc.cod_poliza
        WHERE rc.cod_cotizacion = pc.cod_cotizacion AND rc.tipo_poliza in ('AUTO-IND','TIC','AUTO-GRUP') AND ";

    if (!isset($_POST['cb_lapso'])) {
      $consulta .= " pc.f_registro like '%$fecha_dia%'";
      $fecha_aux = $fecha_dia;
      $msj_log = "REPORTE PRODUCCIÓN COMERCIAL BASE UNIERSOFT EL $fecha_dia";
      $titulo = "VISTA PREVIA DE REPORTE PRODUCCIÓN SISTEMA UNISERSOFT - ITEMS. DEL $fecha_dia";
    } else {
      $consulta .= " pc.f_registro >= '$fecha_inicio' and pc.f_registro <= '$fecha_final'";
      $fecha_aux = $fecha_final;
      $msj_log = "REPORTE PRODUCCIÓN COMERCIAL BASE UNIERSOFT ENTRE $fecha_inicio Y $fecha_final";
      $titulo = "VISTA PREVIA DE REPORTE PRODUCCIÓN SISTEMA UNISERSOFT - ITEMS. DESDE $fecha_inicio HASTA $fecha_final";
    }

    $consulta .= "  ORDER BY pc.f_registro ASC";
    //echo $consulta;
    $result = mysqli_query($con, $consulta);
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        if ($row['tipo_cartera'] == 'ESTATAL') {
          $inprime = $row['intermediario'];
          $inter = "CARTERA DIRECTA";
        } else {
          $inprime = "";
          $inter = $row['intermediario'];
        }
        if ($row['prima_total_c'] < 0) {
          $asistencia_vial = 'NO';
        } else {
          $asistencia_vial = $row['asi_vial'];
        }
        $cod_cotizacion = $row['cod_cotizacion'];
        $cod_poliza = $row['cod_poliza'];

        $filas = getFilas($cod_poliza, $cod_cotizacion);
        $tablas = getComplementos($cod_poliza, $cod_cotizacion, $filas);
    ?>
        <tr>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $filas; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['mes']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['anio']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['cod_poliza']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['cod_cliente']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= utf8_decode($row['regional']); ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= utf8_decode($row['tomador']); ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= utf8_decode($row['asegurado']); ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= utf8_decode($row['ramo']); ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= utf8_decode($row['tipo_poliza']); ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['movimiento']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['nro_anexo']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $asistencia_vial; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['cant_autos']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= utf8_decode($row['observaciones']); ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['ROUND(pc.valor_asegurado,2)']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['ROUND(pc.valor_primera_perdida,2)']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['ROUND(pc.valor_terremoto,2)']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['ROUND(pc.valor_terrorismo,2)']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['prima_total_c']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['moneda']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['p_factor_tasa_tecnica']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['ROUND(pc.prima_neta,2)']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['fecha_emision']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['fecha_inicio']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['fecha_fin']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['dias_transcurridos']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= utf8_decode($row['tipo_cartera']); ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= utf8_decode($row['subtipo_cartera']); ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $inprime ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= utf8_decode($inter) ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['tipo_pago']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['ROUND(pc.com_intermediario_gnv,2)']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['ROUND(pc.com_intermediario,2)']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['num_cuota']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['f_registro']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['usuario']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= $row['emisor']; ?></td>
          <td style="text-align: center; border:1px solid black" rowspan="<?= $filas ?>"><?= utf8_decode($row['modalidad']); ?></td>
          <?php
          $k = 0;
          while ($k < $filas) {
            echo $tablas[$k];
            $k++;
          }
          ?>
        </tr>
    <?php
      }
    } else {
      echo "<tr><td colspan='40' style='text-align:center; border:1px solid black'>NO SE ENCONTRARON REGISTROS PARA LA FECHA SELECCIONADA</td></tr>";
    }
    ?>
  </tbody>
</table>