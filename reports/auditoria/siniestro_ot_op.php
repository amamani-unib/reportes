<?php
$consulta = "SELECT 
    s.cod_siniestro AS codigo_siniestro,
    DATE(lr.f_registro) AS fecha_registro_modificacion,
    TIME(lr.f_registro) AS hora_registro_modificacion,
    CONCAT(u.usuario_nombre,' ', u.usuario_apellido,' ', u.segundo_apellido) AS usuario_registro,
    lr.valores_old AS glosa,
    lr.valores_new AS importe_movimiento,
    otc.cod_trabajo_compra,
    otc.moneda,
    otc.sum_monto,
    otc.estado AS estado_trabajo_compra,
    op.cod_orden,
    op.pago_total_bs,
    op.estado AS estado_orden_pago,
    s.monto_reserva,
    lr.estado AS estado_registro,
    s.estado AS estado_siniestro
FROM comercial.siniestros s
LEFT JOIN comercial.log_modificaciones lr
    ON s.id_sin = lr.valor
    AND lr.tabla = 'siniestros'
LEFT JOIN comercial.orden_pago op
    ON op.cod_siniestro = s.cod_siniestro
LEFT JOIN comercial.trabajo_compra otc
    ON otc.cod_siniestro = s.cod_siniestro
LEFT JOIN comercial.usuarios_comercial u
    ON u.usuario = lr.usuario  ";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " WHERE s.f_registro like '%$fecha_dia%'";
    $fecha_aux = $fecha_dia;
    $msj_log = "REPORTE SINIESTROS - ORDENES DE PAGO Y TRABAJO-COMPRA UNISERSOFT EL $fecha_dia";
    $titulo = "VISTA PREVIA DE REPORTE SINIESTSROS - ORDENES DE PAGO Y TRABAJO-COMPRA SISTEMA UNISERSOFT DEL $fecha_dia";
} else {
    $consulta .= " WHERE s.f_registro >= '$fecha_inicio' AND s.f_registro <= '$fecha_final'";
    $fecha_aux = $fecha_final;
    $msj_log = "REPORTE SINIESTROS - ORDENES DE PAGO Y TRABAJO-COMPRA BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
    $titulo = "VISTA PREVIA DE REPORTE SINIESTROS - ORDENES DE PAGO Y TRABAJO-COMPRA SISTEMA UNISERSOFT DESDE $fecha_inicio HASTA $fecha_final";
}

$consulta .= " AND s.cod_siniestro<>'CORTE' AND s.cod_siniestro <> ' ' GROUP BY s.cod_siniestro, lr.id_registro";
echo $consulta;

$result = mysqli_query($con, $consulta);

?>
<h2 align="center"> <?= $titulo ?></h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th>Nro. Sinistro</th>
                <th>Fecha modificacion</th>
                <th>Hora modificacion</th>
                <th>Usuario que registro modificacion</th>
                <th>Glosa (datos antes de la modificacion)</th>
                <th>Importe (Datos despues de la modificacion)</th>
                <th>Orden de trabajo / compra</th>
                <th>Moneda (OT/OC)</th>
                <th>Monto (OT/OC)</th>
                <th>Estado</th>
                <th>Codigo de Orden de pago</th>
                <th>Monto de Orden de pago (BS)</th>
                <th>Estado Orden de pago</th>
                <th>Monto de reserva inicial</th>
                <th>Estado del registro de modificacion</th>
                <th>Estado del siniestro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $cod_siniestro = $row['codigo_siniestro'];
                $mensaje = "REGISTRO DE SINIESTRO. $cod_siniestro - MONTO DE RESERVA:";
                $sele2 = $con->query("SELECT  TRIM(SUBSTRING_INDEX(movimiento, 'MONTO DE RESERVA:', -1)) AS reserva_inicial 
                FROM comercial.log_comercial where movimiento LIKE CONCAT('$mensaje', '%') limit 1");
                //echo $sele2;
                $filas2 = $sele2->fetch_assoc();
                $reserva_inicial = $filas2['reserva_inicial'];

            ?>
                <tr>
                    <td><?php echo $row['codigo_siniestro']; ?></td>
                    <td><?php echo $row['fecha_registro_modificacion']; ?></td>
                    <td><?php echo $row['hora_registro_modificacion']; ?></td>
                    <td><?php echo $row['usuario_registro']; ?></td>
                    <td><?php echo $row['glosa']; ?></td>
                    <td><?php echo $row['importe_movimiento']; ?></td>
                    <td><?php echo $row['cod_trabajo_compra']; ?></td>
                    <td><?php echo $row['moneda']; ?></td>
                    <td><?php echo $row['sum_monto']; ?></td>
                    <td><?php echo $row['estado_trabajo_compra']; ?></td>
                    <td><?php echo $row['cod_orden']; ?></td>
                    <td><?php echo $row['pago_total_bs']; ?></td>
                    <td><?php echo $row['estado_orden_pago']; ?></td>
                    <td><?php echo $reserva_inicial; ?></td>
                    <td><?php echo $row['estado_registro']; ?></td>
                    <td><?php echo $row['estado_siniestro']; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>