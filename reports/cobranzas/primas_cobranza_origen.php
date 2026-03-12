<?php
$consulta = "SELECT *
                      FROM comercial.primas_cobranzas";
if (!isset($_POST['cb_lapso'])) {
    $consulta .= " WHERE f_registro like '%$fecha_dia%'";
    $fecha_aux = $fecha_dia;
    $msj_log = "REPORTE DE PRIMAS POR COBRAR - BASE UNISERSOFT EL $fecha_dia";
    $titulo = "VISTA PREVIA DE PRIMAS POR COBRAR (Base UNISERSOFT). DEL $fecha_dia";
} else {
    $consulta .= " WHERE f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
    $fecha_aux = $fecha_final;
    $msj_log = "REPORTE DE PRIMAS POR COBRAR - BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
    $titulo = "VISTA PREVIA DE PRIMAS POR COBRAR (Base UNISERSOFT). ENTRE $fecha_inicio HASTA $fecha_final";
}

$consulta .= " and estado != 'ELIMINADO'";

//echo $consulta;

$result = mysqli_query($con, $consulta);
?>
<h2 align="center"><?= $titulo ?> </h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th>Nro.</th>
                <th>Nro. Póliza</th>
                <th>Cod. Cliente</th>
                <th>Cod. Cotización</th>
                <th>Movimiento</th>
                <th>Cod. Unico</th>
                <th>Fecha de Emisión</th>
                <th>Prima Total</th>
                <th>Tipo de Pago</th>
                <th>Cuotas</th>
                <th>Estado</th>
                <th>Numero de Factura</th>
                <th>Intermediario</th>
                <th>Canal</th>
                <th>Comentario</th>
                <th>Fecha de Registro</th>
                <th>Fecha de Pago</th>
                <th>Moneda</th>
                <th>Tipo de cambio</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $cod_cotizacion = $row['cod_cotizacion'];
                $cod_poliza = $row['cod_poliza'];
                $sele2 = $con->query("SELECT moneda,tipo_cambio from comercial.pol_reporte_comercial where cod_cotizacion = '$cod_cotizacion' and cod_poliza='$cod_poliza'  order by id_rc desc limit 1");
                $filas2 = $sele2->fetch_assoc();
                $moneda = $filas2['moneda'];
                $tipo_cambio = $filas2['tipo_cambio'];
            ?>
                <tr>
                    <td><?php echo $row['id_pxc']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['cod_cotizacion']; ?></td>
                    <td><?php echo $row['movimiento']; ?></td>
                    <td><?php echo $row['cod_unico']; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $row['prima_total']; ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['cuotas']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['numero_factura']; ?></td>
                    <td><?php echo $row['intermediario']; ?></td>
                    <td><?php echo $row['canal']; ?></td>
                    <td><?php echo $row['estadopxc']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['f_pago']; ?></td>
                    <td><?php echo $moneda; ?></td>
                    <td><?php echo $tipo_cambio; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>