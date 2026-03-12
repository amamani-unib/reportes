
<?php
//include "config/config.php";;
//$cierre = $_POST['cierre'];
$msj_log = "REPORTE DE MONTO DE RESERVA - LOG - NUEVO REPORTE";
$consulta = "SELECT  cod_siniestro,monto_reserva,f_registro,usuario FROM comercial.siniestros as si WHERE si.estado<>'ELIMINADO' ";


if (!isset($_POST['cb_lapso'])) {
    $consulta .= " and si.f_registro like '%$fecha_dia%'";
    $titulo = "VISTA PREVIA DE MONTOS DE RESERVA DEL $fecha_dia";
} else {
    $consulta .= " and si.f_registro >= '$fecha_inicio' and si.f_registro <= '$fecha_final'";
    $titulo = "VISTA PREVIA DE MONTOS DE RESERVA DESDE $fecha_inicio HASTA $fecha_final";
}
$consulta .= " ORDER BY si.cod_siniestro ASC";
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
                <th style="text-align: center;">Cod. Siniestro</th>
                <th style="text-align: center;">Monto de reserva</th>
                <th style="text-align: center;">Fecha movimiento</th>
                <th style="text-align: center;">Hora de movimiento</th>
                <th style="text-align: center;">Usuario Movimiento</th>
            </tr>
        </thead>
        <tbody>            
            <?php            
            while ($row = mysqli_fetch_assoc($resultado)) {
                $cod_siniestro = $row['cod_siniestro'];
                $usuario_registra = $row['usuario'];
                $query_estado = "SELECT movimiento,fecha,usuario FROM comercial.log_comercial as lg
                                 WHERE  lg.movimiento LIKE '%REGISTRO DE SINIESTRO. $cod_siniestro %' OR lg.movimiento LIKE '%ACTUALIZACION DE ESTADO DEL SINIESTRO:. $cod_siniestro%'";
                $res = mysqli_query($con, $query_estado);
                //$filas = mysqli_num_rows($res);            
                $salida_dato = '
                <td>' . $row['cod_siniestro'] . '</td>                
                ';

                while ($f = mysqli_fetch_assoc($res)) {
                    echo '<tr>';
                    echo $salida_dato;
                    echo '<td>' . $f['movimiento'] . '</td>
                          <td>' . date('Y-m-d',strtotime($f['fecha'])) . '</td>
                          <td>' . date('H:i:s',strtotime($f['fecha'])) . '</td>
                          <td>' . $f['usuario'] . '</td>';
                    echo '</tr>';
                }

            }

            ?>
               
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>