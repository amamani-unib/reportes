<?php
$consulta = "SELECT pc.lugar, pc.id_calculo_prima,month(pc.f_registro) as mes,year(pc.f_registro) as anio, rc.cod_poliza,rc.cod_cliente, rc.regional,rc.tomador,pc.asegurado,
              rc.cod_cotizacion, rc.ramo,rc.tipo_poliza,pc.movimiento,rc.nro_anexo, rc.asi_vial, rc.cant_autos, pc.observaciones, ROUND(pc.valor_asegurado,2),
              ROUND(pc.valor_primera_perdida,2),ROUND(pc.valor_terremoto,2), ROUND(pc.valor_terrorismo,2), 
              ROUND (case when pc.tipo_pago='CONTADO' THEN pc.prima_contado when pc.tipo_pago='CREDITO' THEN pc.prima_credito END ,2) as prima_total_c, 
              rc.moneda,pc.p_factor_tasa_tecnica, ROUND(pc.prima_neta,2),pc.fecha_emision,pc.fecha_inicio,pc.fecha_fin, pc.dias_transcurridos,rc.tipo_cartera,
              rc.subtipo_cartera, pc.tipo_pago, pc.emisor, rc.intermediario,ROUND(pc.com_intermediario_gnv,2),ROUND(pc.com_intermediario,2),pc.num_cuota,
              pc.f_registro,pc.usuario,rc.modalidad FROM comercial.pol_reporte_comercial as rc INNER JOIN comercial.pol_calculo_prima as pc ON 
              rc.cod_poliza=pc.cod_poliza AND rc.cod_cotizacion = pc.cod_cotizacion";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE pc.f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE PRODUCCIÓN COMERCIAL BASE UNIERSOFT EL $fecha_dia";
            $titulo = "VISTA PREVIA DE REPORTE PRODUCCIÓN SISTEMA UNISERSOFT. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE pc.f_registro >= '$fecha_inicio' and pc.f_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE PRODUCCIÓN COMERCIAL BASE UNIERSOFT ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE REPORTE PARA EMISIÓN PRODUCCIÓN SISTEMA UNISERSOFT. DESDE $fecha_inicio HASTA $fecha_final";
          }

          $consulta .= " ORDER BY pc.f_registro ASC";
          //echo $consulta;
          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>Nro. REGISTRO</th>
                  <th>MES</th>
                  <th>AÑOS</th>
                  <th>Nro. PÓLIZA</th>
                  <th>Cod. CLIENTE</th>
                  <th>REGIONAL</th>
                  <th>SUCURSAL</th>
                  <th>TOMADOR</th>
                  <th>ASEGURADO</th>
                  <th>RAMO</th>
                  <th>TIPO POLIZA</th>
                  <th>TIPO MOVIMIENTO</th>
                  <th>NRO. ANEXO</th>
                  <th>ASISTENCIA VIAL</th>
                  <th>CANTIDAD DE ITEMS</th>
                  <th>OBSERVACIONES</th>
                  <th>VALOR ASEGURADO</th>
                  <th>VALOR PRIMERA</th>
                  <th>TERREMOTO</th>
                  <th>TERRORISMO</th>
                  <th>PRIMA TOTAL</th>
                  <th>MONEDA</th>
                  <th>FACTOR DE NETO</th>
                  <th>PRIMA NETA</th>
                  <th>FECHA DE EMISION</th>
                  <th>FECHA INICIO DE VIGENCIA</th>
                  <th>FECHA FIN DE VIGENCIA</th>
                  <th>DÍAS DE VIGENCIA</th>
                  <th>SECTOR</th>
                  <th>TIPO DE CARTERA</th>
                  <th>SUBTIPO DE CARTERA</th>
                  <th>INTERMEDIARIO</th>
                  <th>TIPO PAGO</th>
                  <th>COMISION INTERMEDIARIO GNV</th>
                  <th>COMISION INTERMEDIARIO</th>
                  <th>NUMERO DE CUOTAS</th>
                  <th>FECHA Y HORA DE REGISTRO</th>
                  <th>USUARIO</th>
                  <th>EMISOR</th>
                  <th>MODALIDAD</th>
                </tr>
              </thead>
              <tbody>
                <?php
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
                ?><tr>
                    <td><?php echo $row['id_calculo_prima']; ?></td>
                    <td><?php echo $row['mes']; ?></td>
                    <td><?php echo $row['anio']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['regional']; ?></td>
                    <td><?php echo $row['lugar']; ?></td>
                    <td><?php echo $row['tomador']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['tipo_poliza']; ?></td>
                    <td><?php echo $row['movimiento']; ?></td>
                    <td><?php echo $row['nro_anexo']; ?></td>
                    <td><?php echo $asistencia_vial; ?></td>
                    <td><?php echo $row['cant_autos']; ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                    <td><?php echo $row['ROUND(pc.valor_asegurado,2)']; ?></td>
                    <td><?php echo $row['ROUND(pc.valor_primera_perdida,2)']; ?></td>
                    <td><?php echo $row['ROUND(pc.valor_terremoto,2)']; ?></td>
                    <td><?php echo $row['ROUND(pc.valor_terrorismo,2)']; ?></td>
                    <td><?php echo $row['prima_total_c']; ?></td>
                    <td><?php echo $row['moneda']; ?></td>
                    <td><?php echo $row['p_factor_tasa_tecnica']; ?></td>
                    <td><?php echo $row['ROUND(pc.prima_neta,2)']; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $row['fecha_inicio']; ?></td>
                    <td><?php echo $row['fecha_fin']; ?></td>
                    <td><?php echo $row['dias_transcurridos']; ?></td>
                    <td><?php echo $row['tipo_cartera']; ?></td>
                    <td><?php echo $row['subtipo_cartera']; ?></td>
                    <td><?php echo $inprime ?></td>
                    <td><?php echo $inter ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['ROUND(pc.com_intermediario_gnv,2)']; ?></td>
                    <td><?php echo $row['ROUND(pc.com_intermediario,2)']; ?></td>
                    <td><?php echo $row['num_cuota']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['usuario']; ?></td>
                    <td><?php echo $row['emisor']; ?></td>
                    <td><?php echo $row['modalidad']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>