<?php
$consulta = "SELECT 
    pc.id_calculo_prima,
    pc.f_registro,
    MONTH(pc.f_registro) AS mes,
    YEAR(pc.f_registro) AS anio,
    rc.cod_poliza,
    rc.cod_cliente,
    rc.regional,
    rc.tomador,
    pc.asegurado,
    rc.ramo,
    rc.tipo_poliza,
    pc.movimiento,
    pc.cod_control,
    rc.asi_vial,
    pc.observaciones,
    i.`0` AS certificado,

    -- VALOR ASEGURADO
    CASE 
        WHEN pc.movimiento IN ('NUEVO','RENOVACION','INCLUSION','INCLUSION MASIVA')
            THEN i.`3`
        WHEN pc.movimiento IN ('INCREMENTO','AMPLIACION','LIQUIDACION','APLICACION')
            THEN pc.valor_asegurado
    END AS valor_asegurado_final,

    -- PRIMA TOTAL
    ROUND(
        CASE 
            WHEN pc.movimiento IN ('NUEVO','RENOVACION','INCLUSION','INCLUSION MASIVA') THEN
                CASE 
                    WHEN pc.tipo_pago = 'CONTADO' THEN i.`4`
                    WHEN pc.tipo_pago = 'CREDITO' THEN i.`5`
                END
            WHEN pc.movimiento IN ('INCREMENTO','AMPLIACION','LIQUIDACION','APLICACION') THEN
                CASE 
                    WHEN pc.tipo_pago = 'CONTADO' THEN pc.prima_contado
                    WHEN pc.tipo_pago = 'CREDITO' THEN pc.prima_credito
                END
        END
    , 2) AS prima_total_c,

    rc.moneda,
    pc.fecha_emision,
    i.`7` AS fecha_inicio,
    i.`8` AS fecha_fin,
    rc.tipo_cartera,
    rc.subtipo_cartera,
    pc.tipo_pago,
    rc.intermediario,
    pc.num_cuota,
    pc.usuario,
    rc.modalidad,
    pc.hora_ini,
    pc.hora_fin,
    i.`13` AS placa,
    i.`14` AS clase,
    i.`15` AS marca,
    i.`16` AS modelo,
    i.`17` AS color,
    i.`18` AS anio,
    i.`19` AS traccion,
    i.`20` AS cilindrada,
    i.`21` AS uso,
    i.`22` AS plazas,
    i.`23` AS chasis,
    i.`24` AS motor,
    i.`25` AS plaza

FROM comercial.pol_reporte_comercial rc
INNER JOIN comercial.pol_calculo_prima pc 
    ON rc.cod_poliza = pc.cod_poliza 
    AND rc.cod_cotizacion = pc.cod_cotizacion

LEFT JOIN comercial.items i
    ON i.cod_poliza = pc.cod_poliza
    AND i.cod_cotizacion = pc.cod_cotizacion
    AND pc.cod_control = i.`12`

WHERE rc.ramo = 'AUTOMOTORES'
  AND pc.f_registro >= '2024-11-01' and pc.f_registro <= '2025-11-01'
  AND pc.movimiento IN (
        'NUEVO','RENOVACION','INCLUSION','INCLUSION MASIVA',
        'INCREMENTO','AMPLIACION','LIQUIDACION','APLICACION'
    )";
          

          //echo $consulta;
          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>Id produccion</th>
                  <th>Fecha registro produccion</th>
                  <th>MES</th>
                  <th>AÑOS</th>
                  <th>Nro. PÓLIZA</th>
                  <th>Cod. CLIENTE</th>
                  <th>REGIONAL</th>
                  <th>TOMADOR</th>
                  <th>ASEGURADO</th>
                  <th>RAMO</th>
                  <th>TIPO POLIZA</th>
                  <th>MOVIMIENTO</th>
                  <th>COD CONTROL</th>
                  <th>ASISTENCIA VIAL</th>
                  <th>OBSERVACIONES</th>
                  <th>CERTIFICADO</th>
                  <th>VALOR ASEGURADO</th>
                  <th>PRIMA TOTAL</th>
                  <th>MONEDA</th>
                  <th>FECHA EMISION</th>
                  <th>FECHA INICIO</th>
                  <th>FECHA FIN</th>
                  <th>TIPO CARTERA</th>
                  <th>SUBTIPO CARTERA</th>
                  <th>TIPO PAGO</th>
                  <th>INTERMEDIARIO</th>
                  <th>NRO. CUOATAS</th>
                  <th>USUARIO</th>
                  <th>MODALIDAD</th>
                  <th>HORA DE INICIO</th>
                  <th>HORA DE FIN</th>
                  <th>PLACA</th>
                  <th>CLASE</th>
                  <th>MARCA</th>
                  <th>MODELO</th>
                  <th>COLOR</th>
                  <th>AÑO</th>
                  <th>TRACCION</th>
                  <th>CILINDRADA</th>
                  <th>USO</th>
                  <th>PLAZAS</th>
                  <th>CHASIS</th>
                  <th>MOTOR</th>
                  <th>PLAZA</th>                  
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                  
                ?><tr>
                    <td><?php echo $row['id_calculo_prima']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['mes']; ?></td>
                    <td><?php echo $row['anio']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['regional']; ?></td>
                    <td><?php echo $row['tomador']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['tipo_poliza']; ?></td>
                    <td><?php echo $row['movimiento']; ?></td>
                    <td><?php echo $row['cod_control']; ?></td>
                    <td><?php echo $row['asi_vial']; ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                    <td><?php echo $row['certificado']; ?></td>
                    <td><?php echo $row['valor_asegurado_final']; ?></td>
                    <td><?php echo $row['prima_total_c']; ?></td>
                    <td><?php echo $row['moneda']; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $row['fecha_inicio']; ?></td>
                    <td><?php echo $row['fecha_fin']; ?></td>
                    <td><?php echo $row['tipo_cartera']; ?></td>
                    <td><?php echo $row['subtipo_cartera']; ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['intermediario']; ?></td>
                    <td><?php echo $row['num_cuota']; ?></td>
                    <td><?php echo $row['usuario']; ?></td>
                    <td><?php echo $row['modalidad']; ?></td>
                    <td><?php echo $row['hora_ini']; ?></td>
                    <td><?php echo $row['hora_fin']; ?></td>
                    <td><?php echo $row['placa']; ?></td>
                    <td><?php echo $row['clase']; ?></td>
                    <td><?php echo $row['marca']; ?></td>
                    <td><?php echo $row['modelo']; ?></td>
                    <td><?php echo $row['color']; ?></td>
                    <td><?php echo $row['anio']; ?></td>
                    <td><?php echo $row['traccion']; ?></td>
                    <td><?php echo $row['cilindrada']; ?></td>
                    <td><?php echo $row['uso']; ?></td>
                    <td><?php echo $row['plazas']; ?></td>
                    <td><?php echo $row['chasis']; ?></td>
                    <td><?php echo $row['motor']; ?></td>
                    <td><?php echo $row['plaza']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>