<?php
$msj_log = "REPORTE MONTOS DE RESERVA DE SINIESTROS";
$consulta = "SELECT cod_siniestro, f_registro, usuario, estado, monto_reserva FROM comercial.siniestros as s WHERE ";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " s.f_registro like '%$fecha_dia%'";
    $titulo = "VISTA PREVIA DE MONTOS DE RESERVA DE SINIESTROS DEL $fecha_dia";
} else {
    $consulta .= " s.f_registro >= '$fecha_inicio' and s.f_registro <= '$fecha_final'";
    $titulo = "VISTA PREVIA DE MONTOS DE RESERVA DE SINIESTROS DESDE $fecha_inicio HASTA $fecha_final";
}
$consulta .= " and (lg.movimiento LIKE '%REGISTRO DE SINIESTRO. %' OR lg.movimiento LIKE '%ACTUALIZACION DE ESTADO DEL SINIESTRO:. %') ORDER BY id_log ASC";
//$consulta .= " and cod_siniestro='SIAUS00010234'";
//echo $consulta;

$resultado = mysqli_query($con, $consulta);

?>
<h2 align="center"><?= $titulo ?></h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th style="text-align: center;">Codigo de siniestro</th>
                <th style="text-align: center;">Fecha</th>
                <th style="text-align: center;">Hora</th>
                <th style="text-align: center;">Usuario</th>
                <th style="text-align: center;">Glosa</th>
                <th style="text-align: center;">Estado</th>
                <th style="text-align: center;">Importe</th>
                <th style="text-align: center;">Moneda</th>
                <th style="text-align: center;">Orden Trabajo-Compra</th>
                <th style="text-align: center;">Orden de Pago</th>
                <th style="text-align: center;">Saldo de Reserva</th>
                <th style="text-align: center;">Estado del Registro</th>
                <th style="text-align: center;">Estado Siniestro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($resultado)) {
                $movimiento = $row['movimiento'];
                $cod_siniestro = '';
                // Buscar el código de siniestro después de los textos conocidos
                if (preg_match('/REGISTRO DE SINIESTRO\. (\S+)/', $movimiento, $matches)) {
                    $cod_siniestro = $matches[1];
                    $monto_reserva = '0.00';
                    $estado = 'PENDIENTE';
                    if (preg_match('/MONTO DE RESERVA:\s*([\d.]+)/i', $movimiento, $monto_match)) {
                        $monto_reserva = $monto_match[1];
                    }
                } elseif (preg_match('/ACTUALIZACION DE ESTADO DEL SINIESTRO:\.\s*(\S+)/', $movimiento, $matches)) {
                    $cod_siniestro = $matches[1];
                    $monto_reserva = '0.00';
                    $estado = '';

                    // Extraer el estado (ej: PAGADO)
                    if (preg_match('/ESTADO:\s*(\S+)(?:\s*-|\.)/i', $movimiento, $estado_match)) {
                        $estado = $estado_match[1];
                    }

                    // Extraer el monto de reserva (ej: 0.00)
                    if (preg_match('/MONTO DE RESERVA:\s*([\d.]+)/i', $movimiento, $monto_match)) {
                        $monto_reserva = $monto_match[1];
                    }
                }

            ?>
                <tr>
                    <td><?= $cod_siniestro; ?></td>
                    <td><?= $row['movimiento']; ?></td>
                    <td><?= $monto_reserva ?></td>
                    <td><?= $estado ?></td>
                    <td><?= date('Y-m-d', strtotime($row['fecha']));  ?></td>
                    <td><?= date('H:i:s', strtotime($row['fecha'])); ?></td>
                    <td><?= $row['usuario']; ?></td>
                </tr>
            <?php
            }
            ?>

        </tbody>
        <?= $script_tabla; ?>
    </table>
</div>