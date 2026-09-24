<?php
//SEGUN REUNION DEL 2022-09-15 Se toma como dato referencia para reporte de produccion la fecha de inicio de vigencia de poliza
$msj_log = "REPORTE PRODUCCION APS COMERCIAL";

$consulta = "SELECT pc.id_calculo_prima,month(pc.f_registro) as mes,year(pc.f_registro) as anio, rc.cod_poliza,rc.cod_cliente, rc.regional,rc.tomador,pc.asegurado,
            rc.ramo,rc.tipo_poliza,pc.movimiento,pc.cod_control, rc.asi_vial, pc.observaciones, pc.items,rc.cia,rc.modalidad_ramo,rc.cod_ramo,
            ROUND(pc.valor_asegurado,2),ROUND(pc.valor_bolivianos, 2) AS vbolivianos, ROUND(pc.valor_dolares, 2) AS vdolares ,ROUND(pc.valor_primera_perdida,2),ROUND(pc.valor_terremoto,2), ROUND(pc.valor_terrorismo,2),
                                ROUND (case 
                                  when pc.tipo_pago='CONTADO' THEN pc.prima_contado  
                                  when pc.tipo_pago='CREDITO' THEN pc.prima_credito
                                END ,2) as prima_total_c,
            pc.moneda, pc.cambio, pc.cambio_usd, pc.p_factor_tasa_tecnica, ROUND(pc.prima_neta,2),pc.fecha_emision,pc.fecha_inicio,pc.fecha_fin, pc.dias_transcurridos,rc.tipo_cartera,rc.subtipo_cartera,
            pc.tipo_pago, pc.lugar, rc.intermediario,ROUND(pc.com_intermediario_gnv,2),ROUND(pc.com_intermediario,2),pc.num_cuota,pc.f_registro,pc.usuario,rc.modalidad,
            pc.hora_ini,pc.hora_fin,c.caedec, pc.cod_cotizacion, pc.auxiliar,pc.porcentaje_comision
            FROM 20260903_comercial.pol_reporte_comercial as rc INNER JOIN 20260903_comercial.pol_calculo_prima as pc ON rc.cod_poliza=pc.cod_poliza AND rc.cod_cotizacion = pc.cod_cotizacion
            LEFT JOIN 20260903_comercial.clientes as c ON rc.cod_cliente=c.cod_cliente ";

if (!isset($_POST['cb_lapso'])) {
  $consulta .= " WHERE pc.f_registro like '%$fecha_dia%'";
  $fecha_aux = $fecha_dia;
  $titulo = "VISTA PREVIA DE REPORTE PRODUCCION APS SISTEMA UNISERSOFT DEL $fecha_dia";
} else {
  $consulta .= " WHERE pc.f_registro >= '$fecha_inicio' and pc.f_registro <= '$fecha_final'";
  $fecha_aux = $fecha_final;
  $titulo = "VISTA PREVIA DE REPORTE PRODUCCION APS SISTEMA UNISERSOFT. DEL $fecha_inicio AL $fecha_final";
}

$consulta .= " ORDER BY pc.f_registro ASC";
//echo $consulta;
$result = mysqli_query($con, $consulta);
?>
<h2 align="center"><?= $titulo ?></h2>
<br>
<?php
if ($cargo_u == 'JEFE EMISION') {
?>
  <div class="col-md-4">
    <label for="btn-cerrar_mes"></label><br>
    <button type="button" class="btn btn-warning" title="Cerrar Produccion del Mes" name="btn-cerrar_mes"
      id="btn-cerrar_mes" data-toggle="modal" data-target="#modal_cerrar_mes">CERRAR MES</button>
  </div>
<?php
}
?>
<div id="datos_reportes" class="table-responsive table">
  <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%'
    id='tabla_generar'>
    <thead>
      <tr class='text-center'>
        <th>Nro. REGISTRO</th>
        <th>MES</th>
        <th>AÑOS</th>
        <th>Codigo modalidad</th>
        <th>Codigo de ramo</th>
        <th>Codigo Entidad</th>
        <th>Nro. PÓLIZA</th>
        <th>Cod. CLIENTE</th>
        <th>Actividad Economica</th>
        <th>REGIONAL</th>
        <th>SUCURSAL </th>
        <th>CODIGO OFICINA </th>
        <th>TOMADOR</th>
        <th>ASEGURADO</th>
        <th>RAMO</th>
        <th>TIPO POLIZA</th>
        <th>TIPO DE OPERACIÓN </th>
        <th>CODIGO DE OPERACIÓN</th>
        <th>OPERACIÓN AUXILIAR</th>
        <th>ASISTENCIA VIAL</th>
        <th>CANTIDAD DE ITEMS</th>
        <th>OBSERVACIONES</th>
        <th>VALOR ASEGURADO</th>
        <th>VALOR ASEGURADO BOLIVIANOS (BUN)</th>
        <th>VALOR ASEGURADO DOLARES (BUN)</th>
        <th>VALOR PRIMERA</th>
        <th>TERREMOTO</th>
        <th>TERRORISMO</th>
        <th>PRIMA CONTADO</th>
        <th>PRIMA TOTAL</th>
        <th>MONEDA</th>
        <th>TIPO DE CAMBIO</th>
        <th>FACTOR DE NETO</th>
        <th>PRIMA NETA</th>
        <th>FECHA DE EMISION</th>
        <th>FECHA INCIO DE VIGENCIA</th>
        <th>HORA DE INCIO VIGENCIA</th>
        <th>FECHA FIN DE VIGENCIA</th>
        <th>HORA DE FIN VIGENCIA</th>
        <th>DÍAS DE VIGENCIA</th>
        <th>SECTOR</th>
        <th>CODIGO DE SECTOR</th>
        <th>TIPO DE CARTERA</th>
        <th>SUBTIPO DE CARTERA</th>
        <th>INTERMEDIARIO</th>
        <th>COD. APS INTERMEDIARIO</th>
        <th>TIPO PAGO</th>
        <th>PORCENTAJE DE COMISION</th>
        <th>COMISION INTERMEDIARIO GNV</th>
        <th>COMISION INTERMEDIARIO</th>
        <th>NUMERO DE CUOTAS</th>
        <th>FECHA Y HORA DE REGISTRO</th>
        <th>USUARIO</th>
        <th>MODALIDAD</th>
        <th>HISTORICO</th>
        <th>TIPO DE CAMBIO DEL DOLAR DEL DIA DEL REGISTRO</th>
      </tr>
    </thead>
    <tbody>
      <?php
      while ($row = mysqli_fetch_assoc($result)) {
        if ($row['tipo_cartera'] == 'ESTATAL' and $row['subtipo_cartera'] != 'BROKER') {
          $inprime = $row['intermediario'];
          $inter = "CARTERA DIRECTA";
          $cod_sector = "E";
        } else {
          $inprime = "";
          $inter = $row['intermediario'];
          $cod_sector = "P";
        }
        if ($row['tipo_cartera'] == 'ESTATAL') {
          $cod_sector = "E";
        } else {
          $cod_sector = "P";
        }
        if ($row['tipo_cartera'] == 'PRIVADO' and $row['cod_cliente'] == 'CUBLP00000068' and $row['fecha_inicio'] >= '2026-07-01') {
          $inprime = "COLECTIVO";
        }
        if ($row['cod_control'] == 1) {
          $nro_anexo = '';
        } else {
          $nro_anexo = $row['cod_control'] - 1;
        }

        if ($row['prima_total_c'] < 0) {
          $asistencia_vial = 'NO';
        } else {
          $asistencia_vial = $row['asi_vial'];
        }
        if ($row['lugar'] == '') {
          $row['lugar'] = $row['regional'];
        }
        if ($row['tipo_pago'] == 'CREDITO') {
          $prima_contado = ROUND(($row['prima_total_c'] / 1.06), 2);
        } else {
          $prima_contado = ROUND($row['prima_total_c'], 2);
        }

        $movimiento = $row['movimiento'];
        $id_calculo_prima = $row['id_calculo_prima'];
        $moneda = $row['moneda'];
        //if ($id_calculo_prima > 36845){
        if ($id_calculo_prima > 36845) {
          $historico = 'NUEVO';

          if ($id_calculo_prima == '37626') {
            $moneda = '02';
          }

          $cod_poliza = $row['cod_poliza'];
          $cod_cotizacion = $row['cod_cotizacion'];
          $query_historico = "SELECT movimiento from 20260903_comercial.pol_calculo_prima 
                    where cod_cotizacion = '$cod_cotizacion' and cod_poliza='$cod_poliza' and movimiento in ('NUEVO','RENOVACION') and id_calculo_prima > 36845 limit 1";
          $resultado_historico = mysqli_query($con, $query_historico);
          $num_rows_historico = mysqli_num_rows($resultado_historico);
          if ($num_rows_historico == 0) {
            $historico = 'ANTIGUO';
          }
          if ($id_calculo_prima == '37717') {
            $historico = 'NUEVO';
          }
        } else {
          $historico = 'ANTIGUO';
        }
        if ($row['subtipo_cartera'] == 'BROKER') {
          $query_aux = "SELECT cod_aps FROM 20260903_comercial.intermediarios WHERE tipo='BROKER' and intermediario='$inter'";
          $sele2 = $con->query($query_aux);
          $filas2 = $sele2->fetch_assoc();
          $cod_aps = $filas2['cod_aps'];
        } else {
          $cod_aps = '';
        }
        $cod_modalidad = $row['modalidad_ramo'];
        if ($cod_modalidad == '') {
          $cod_modalidad = 91;
        } else {
          $cod_modalidad = $row['modalidad_ramo'];
        }
        $cod_ramo = $row['cod_ramo'];
        if ($cod_ramo == '' || !is_numeric($cod_ramo)) {
          $tipo_poliza = $row['tipo_poliza'];
          $sele2 = $con->query("SELECT cod_ramo from 20260903_comercial.x_ramo where tipo_poliza = '$tipo_poliza'order by id_ramo desc limit 1");
          $filas2 = $sele2->fetch_assoc();
          $cod_ramo = $filas2['cod_ramo'];
        } else {
          $cod_ramo = $row['cod_ramo'];
        }
        $lugar = $row['lugar'];
        switch ($lugar) {
          case 'LA PAZ':
            $cod_oficina = "LPZ";
            break;
          case 'COCHABAMBA':
            $cod_oficina = "CBB";
            break;
          case 'SANTA CRUZ':
            $cod_oficina = "SCZ";
            break;
          case 'SUCRE':
            $cod_oficina = "SUC";
            break;
          case 'POTOSI':
            $cod_oficina = "PTS";
            break;
          case 'TARIJA':
            $cod_oficina = "TJA";
            break;
          case 'ORURO':
            $cod_oficina = "ORU";
            break;
          case 'PANDO':
            $cod_oficina = "PAN";
            break;
          case 'BENI':
            $cod_oficina = "BEN";
            break;
          default:
            $cod_oficina = "LPZ";
        }
        switch ($movimiento) {
          case 'NUEVO':
            $cod_movimiento = "N";
            break;
          case 'RENOVACION':
            $cod_movimiento = "R";
            break;
          case 'DEVOLUCION':
          case 'ANULACION POR FALTA DE PAGO':
          case 'ANULACION':
            $cod_movimiento = "A";
            break;
          case 'INCLUSION':
            $cod_movimiento = "I";
            break;
          case 'EXCLUSION':
            $cod_movimiento = "E";
            break;
          case 'AMPLIACION':
            $cod_movimiento = "V";
            break;
          case 'DECREMENTO':
            $cod_movimiento = "D";
            break;
          case 'INCREMENTO':
            $cod_movimiento = "T";
            break;
          case 'APLICACION':
            $cod_movimiento = "P";
            break;
          case 'LIQUIDACION':
            $cod_movimiento = "L";
            break;
          case 'RESCISION':
            $cod_movimiento = "A";
            $movimiento = "ANULACION";
            break;
          default:
            $cod_movimiento = "";
        }
      ?>
        <tr>
          <td><?php echo $row['id_calculo_prima']; ?></td>
          <td><?php echo $row['mes']; ?></td>
          <td><?php echo $row['anio']; ?></td>
          <td><?php echo $cod_modalidad; ?></td>
          <td><?php echo $cod_ramo; ?></td>
          <td><?php echo $row['cia'] ?></td>
          <td><?php echo $row['cod_poliza']; ?></td>
          <td><?php echo $row['cod_cliente']; ?></td>
          <td><?php echo $row['caedec']; ?></td>
          <td><?php echo $row['regional']; ?></td>
          <td><?php echo $row['lugar']; ?></td>
          <td><?php echo $cod_oficina; ?></td>
          <td><?php echo $row['tomador']; ?></td>
          <td><?php echo $row['asegurado']; ?></td>
          <td><?php echo $row['ramo']; ?></td>
          <td><?php echo $row['tipo_poliza']; ?></td>
          <td><?php echo $movimiento; ?></td>
          <td><?php echo $cod_movimiento; ?></td>
          <td><?php echo $row['auxiliar']; ?></td>
          <td><?php echo $asistencia_vial; ?></td>
          <td><?php echo $row['items']; ?></td>
          <td><?php echo $row['observaciones']; ?></td>
          <td><?php echo $row['ROUND(pc.valor_asegurado,2)']; ?></td>
          <td><?php echo $row['vbolivianos']; ?></td>
          <td><?php echo $row['vdolares']; ?></td>
          <td><?php echo $row['ROUND(pc.valor_primera_perdida,2)']; ?></td>
          <td><?php echo $row['ROUND(pc.valor_terremoto,2)']; ?></td>
          <td><?php echo $row['ROUND(pc.valor_terrorismo,2)']; ?></td>
          <td><?php echo $prima_contado; ?></td>
          <td><?php echo $row['prima_total_c']; ?></td>
          <td><?php echo (int)$row['moneda']; ?></td>
          <td><?php echo $row['cambio']; ?></td>
          <td><?php echo $row['p_factor_tasa_tecnica']; ?></td>
          <td><?php echo $row['ROUND(pc.prima_neta,2)']; ?></td>
          <td><?php echo $row['fecha_emision']; ?></td>
          <td><?php echo $row['fecha_inicio']; ?></td>
          <td><?php echo $row['hora_ini']; ?></td>
          <td><?php echo $row['fecha_fin']; ?></td>
          <td><?php echo $row['hora_fin']; ?></td>
          <td><?php echo $row['dias_transcurridos']; ?></td>
          <td><?php echo $row['tipo_cartera']; ?></td>
          <td><?php echo $cod_sector; ?></td>
          <td><?php echo $row['subtipo_cartera']; ?></td>
          <td><?php echo $inprime ?></td>
          <td><?php echo $inter ?></td>
          <td><?php echo $cod_aps ?></td>
          <td><?php echo $row['tipo_pago']; ?></td>
          <td><?php echo number_format((float) $row['porcentaje_comision'], 2, '.', '') ?></td>
          <td><?php echo $row['ROUND(pc.com_intermediario_gnv,2)']; ?></td>
          <td><?php echo $row['ROUND(pc.com_intermediario,2)']; ?></td>
          <td><?php echo $row['num_cuota']; ?></td>
          <td><?php echo $row['f_registro']; ?></td>
          <td><?php echo $row['usuario']; ?></td>
          <td><?php echo $row['modalidad']; ?></td>
          <td><?php echo $historico; ?></td>
          <td><?php echo $row['cambio_usd']; ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
    <?php echo $script_tabla; ?>
  </table>
</div>