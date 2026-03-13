<?php
$consulta = "SELECT s.cod_siniestro, s.f_siniestro, s.f_registro as f_r_siniestro,s.asegurado , s.f_denuncia as fecha_denuncia, s.lugar_siniestro, s.cod_poliza,
s.detalle_siniestro,pa.concepto, pa.beneficiario as ben_razon_social, pa.beneficiario_titular as ben_titular, pa.banco, pa.cia, pa.porcentaje, pa.modalidad, pa.importe,
pa.importe_usd, pa.moneda, pa.usuario as user_pa, s.usuario as user_reclamo, pa.cod_pago,pa.f_registro as f_registro_pa,
GROUP_CONCAT(DISTINCT otc.cod_trabajo_compra ORDER BY otc.cod_trabajo_compra SEPARATOR ', ') 
        AS trabajos_compra,
GROUP_CONCAT(otc.estado ORDER BY otc.estado SEPARATOR ', ') 
        AS estado_trabajos
FROM comercial.pagos_anticipados as pa INNER JOIN comercial.siniestros as s on s.cod_siniestro=pa.cod_siniestro
LEFT JOIN comercial.trabajo_compra as otc on otc.cod_siniestro=s.cod_siniestro";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " WHERE pa.f_registro like '%$fecha_dia%'";
    $fecha_aux = $fecha_dia;
    $msj_log = "REPORTE PAGOS ANTICIPADOS UNISERSOFT EL $fecha_dia";
    $titulo = "VISTA PREVIA DE REPORTE PAGOS ANTICIPADOS SISTEMA UNISERSOFT DEL $fecha_dia";
} else {
    $consulta .= " WHERE pa.f_registro >= '$fecha_inicio' and pa.f_registro <= '$fecha_final'";
    $fecha_aux = $fecha_final;
    $msj_log = "REPORTE REPORTE PAGOS ANTICIPADOS BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
    $titulo = "VISTA PREVIA DE REPORTE PAGOS ANTICIPADOS SISTEMA UNISERSOFT DESDE $fecha_inicio HASTA $fecha_final";
}

$consulta .= " GROUP BY pa.cod_pago";
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
                <th>Fecha de solicitud </th>
                <th>Nobre asegurado</th>
                <th>Nro. póliza</th>
                <th>Fecha de siniestro</th>
                <th>Fecha de denuncia</th>
                <th>Lugar de denuncia</th>
                <th>Despcripcion del hecho</th>
                <th>Nro. Pago Anticipado</th>
                <th>Concepto</th>
                <th>Beneficiario (Titular)</th>
                <th>Banco</th>
                <th>Cuenta</th>
                <th>Beneficiario (Razón Social)</th>
                <th>Porcentaje de Anticipo Requerido</th>
                <th>Modalidad de pago</th>
                <th>Importe Total</th>
                <th>Moneda</th>
                <th>Usuario que genero el formulario</th>
                <th>Usuario que registro el siniestro</th>
                <th>Cod Orden Trabajo - Compra asociado al siniestro</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $moneda = $row['moneda'];
                if ($moneda == 'BOLIVIANOS') {
                    $monto_total = $row['importe'];
                } else {
                    $monto_total = $row['importe_usd'];;
                }
            ?>
                <tr>
                    <td><?php echo $row['cod_siniestro']; ?></td>
                    <td><?php echo $row['f_registro_pa']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['f_siniestro']; ?></td>
                    <td><?php echo $row['fecha_denuncia']; ?></td>
                    <td><?php echo $row['lugar_siniestro']; ?></td>
                    <td><?php echo $row['detalle_siniestro']; ?></td>
                    <td><?php echo $row['cod_pago']; ?></td>
                    <td><?php echo $row['concepto']; ?></td>
                    <td><?php echo $row['ben_titular']; ?></td>
                    <td><?php echo $row['banco']; ?></td>
                    <td><?php echo $row['cia']; ?></td>
                    <td><?php echo $row['ben_razon_social']; ?></td>
                    <td><?php echo $row['porcentaje']; ?></td>
                    <td><?php echo $row['modalidad']; ?></td>
                    <td><?php echo $monto_total; ?></td>
                    <td><?php echo $row['moneda']; ?></td>
                    <td><?php echo $row['user_pa']; ?></td>
                    <td><?php echo $row['user_reclamo']; ?></td>
                    <td><?php echo $row['trabajos_compra']; ?></td>
                    <td><?php echo $row['estado_trabajos']; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>