<?php
//include "config/config.php";;
//$cierre = $_POST['cierre'];
$msj_log = "REPORTE DE MONTO DE RESERVA - LOG - NUEVO REPORTE";
$consulta = "SELECT movimiento,fecha,usuario FROM comercial.log_comercial as lg
             WHERE  ";


if (!isset($_POST['cb_lapso'])) {
    $consulta .= " lg.fecha like '%$fecha_dia%'";
    $titulo = "VISTA PREVIA DE MONTOS DE RESERVA DEL $fecha_dia";
} else {
    $consulta .= " lg.fecha >= '$fecha_inicio' and lg.fecha <= '$fecha_final'";
    $titulo = "VISTA PREVIA DE MONTOS DE RESERVA DESDE $fecha_inicio HASTA $fecha_final";
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
                <th style="text-align: center;">Monto de reserva</th>
                <th style="text-align: center;">Fecha movimiento</th>
                <th style="text-align: center;">Hora de movimiento</th>
                <th style="text-align: center;">Usuario Movimiento</th>
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
                } elseif (preg_match('/ACTUALIZACION DE ESTADO DEL SINIESTRO:\. (\S+)/', $movimiento, $matches)) {
                    $cod_siniestro = $matches[1];
                }
            ?>
                <tr>
                    <td><?php echo $cod_siniestro; ?></td>
                    <td><?php echo $row['movimiento']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['fecha']));  ?></td>
                    <td><?php echo date('H:i:s', strtotime($row['fecha'])); ?></td>
                    <td><?php echo $row['usuario']; ?></td>
                </tr>
            <?php
            }
            ?>

        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>