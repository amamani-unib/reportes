<?php
$consulta = "SELECT 
    s.cod_siniestro AS codigo_siniestro,
    s.f_registro as fecha_registro_siniestro,
    DATE(lr.f_registro) AS fecha_registro_modificacion,
    TIME(lr.f_registro) AS hora_registro_modificacion,
    CONCAT(u.usuario_nombre,' ', u.usuario_apellido,' ',u.segundo_apellido) AS usuario_registro,
    lr.valores_old AS glosa,
    lr.valores_new AS importe_movimiento,
    lr.claves,
    GROUP_CONCAT(
        DISTINCT otc.cod_trabajo_compra,' ','MONEDA: ', otc.moneda,' ','MONTO TOTAL: ',otc.sum_monto
        ORDER BY otc.cod_trabajo_compra
        SEPARATOR '/ '
    ) AS orden_trabajo_compra,
    GROUP_CONCAT(
        DISTINCT op.cod_orden,' ','MONEDA: BS MONTO TOTAL:', op.pago_total_bs
        ORDER BY op.cod_orden
        SEPARATOR '/ '
    ) AS orden_pago,
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
    ON u.usuario = lr.usuario";

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
//echo $consulta;

$result = mysqli_query($con, $consulta);

?>
<h2 align="center"> <?= $titulo ?></h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th>Nro. Sinistro</th>
                <th>Fecha de registro siniestro</th>
                <th>Fecha modificacion</th>
                <th>Hora modificacion</th>
                <th>Usuario que registro modificacion</th>
                <th>Glosa (datos antes de la modificacion)</th>
                <th>Importe (Datos despues de la modificacion)</th>
                <th>Ordenes de trabajo asosiados al siniestro (CODIGO-MONEDA-MONTO)</th>
                <th>Ordenes de pago asosiado al siniestro (CODIGO-MONEDA-MONTO)</th>
                <th>Monto de reserva</th>
                <th>Estado del registro de modificacion</th>
                <th>Estado del siniestro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $row['codigo_siniestro']; ?></td>
                    <td><?php echo $row['fecha_registro_siniestro']; ?></td>
                    <td><?php echo $row['fecha_registro_modificacion']; ?></td>
                    <td><?php echo $row['hora_registro_modificacion']; ?></td>
                    <td><?php echo $row['usuario_registro']; ?></td>
                    <td><?php echo $row['glosa']; ?></td>
                    <td><?php echo $row['importe_movimiento']; ?></td>
                    <td><?php echo $row['orden_trabajo_compra']; ?></td>
                    <td><?php echo $row['orden_pago']; ?></td>
                    <td><?php echo $row['monto_reserva']; ?></td>
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