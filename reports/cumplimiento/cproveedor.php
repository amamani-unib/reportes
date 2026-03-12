<?php

$msj_log = "REPORTE DE POLIZAS POR COMULO";
$consulta = "SELECT * FROM orden_pago WHERE ";
//$row = mysqli_fetch_assoc($result);

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " f_registro like '%$fecha_dia%'";
    $titulo = "VISTA PREVIA DE REPORTE CUMULO POR PROVEEDOR DEL $fecha_dia";
} else {
    $consulta .= " f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
    $titulo = "VISTA PREVIA DE REPORTE CUMULO POR PROVEEDOR DESDE $fecha_inicio HASTA $fecha_final";
}
$consulta .= " GROUP BY receptor HAVING pago_total_bs >= 69600 ORDER BY pago_total_bs DESC";
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
                <th>DISTRITO</th>
                <th>COD. CLIENTE</th>
                <th>COD. POLIZA</th>
                <th>COD. SINIESTRO</th>
                <th>ORDEN PAGO</th>
                <th>ASEGURADO</th>
                <th>RECEPTOR/BENEFICIARIO</th>
                <th>IMPORTE BS</th>
                <th>RETENCION</th>
                <th>PAGO TOTAL BS</th>
                <th>DOCUMENTO DE DESCARGO</th>
                <th>TOTAL IMPORTE FACTURAS</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($resultado)) {
                $cod_orden_pago = $row['cod_orden'];
                $sql_importe = $con->query("SELECT sum(importe_factura) as total_importe 
                FROM comercial.factura_opago WHERE cod_orden_pago='$cod_orden_pago'");
                $fila_i = $sql_importe->fetch_assoc();
                $total = $fila_i['total_importe'];

            ?><tr>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['cod_siniestro']; ?></td>
                    <td><?php echo $row['cod_orden']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $row['receptor']; ?></td>
                    <td><?php echo $row['importe_bs']; ?></td>
                    <td><?php echo $row['retencion_bs']; ?></td>
                    <td><?php echo $row['pago_total_bs']; ?></td>
                    <td><?php echo $row['doc_descargo']; ?></td>
                    <td><?php echo $total ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>