
<?php
//include "config/config.php";;
//$cierre = $_POST['cierre'];
$msj_log = "REPORTE DE TRABAJO - COMPRA - LOG - NUEVO REPORTE";
$consulta = "SELECT  * FROM comercial.trabajo_compra as tc WHERE tc.estado='VIGENTE'";


if (!isset($_POST['cb_lapso'])) {
    $consulta .= " and tc.f_registro like '%$fecha_dia%'";
    $titulo = "VISTA PREVIA DE REPORTE ORDENES DE TRABAJO - COMPRA DEL $fecha_dia";
} else {
    $consulta .= " and tc.f_registro >= '$fecha_inicio' and tc.f_registro <= '$fecha_final'";
    $titulo = "VISTA PREVIA DE REPORTE ORDENES DE TRABAJO - COMPRA DESDE $fecha_inicio HASTA $fecha_final";
}
$consulta .= " ORDER BY tc.f_registro ASC";
//$consulta .= " and cod_siniestro='SIAUS00010234'";
//$consulta;

$resultado = mysqli_query($con, $consulta);

?>
<h2 align="center"><?= $titulo ?></h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th style="text-align: center;">Fecha de Registro</th>
                <th style="text-align: center;">Distrito</th>
                <th style="text-align: center;">Usuario</th>
                <th style="text-align: center;">Cod. Cliente</th>
                <th style="text-align: center;">Cod. Poliza</th>
                <th style="text-align: center;">Cod. Siniestro</th>
                <th style="text-align: center;">Cod Trabajo/Compra</th>
                <th style="text-align: center;">Asegurado</th>
                <th style="text-align: center;">Cobertura</th>
                <th style="text-align: center;">Proveedor</th>
                <th style="text-align: center;">Cotizacion </th>
                <th style="text-align: center;">Moneda</th>
                <th style="text-align: center;">Suma total</th>
                <th style="text-align: center;">Descuento</th>
                <th style="text-align: center;">Total</th>
                <th style="text-align: center;">Movimiento</th>
                <th style="text-align: center;">Fecha de Movimiento</th>
                <th style="text-align: center;">Usuario Modifica</th>
            </tr>
        </thead>
        <tbody>            
            <?php            
            while ($row = mysqli_fetch_assoc($resultado)) {
                $cod_trabajo_compra = $row['cod_trabajo_compra'];
                $usuario_registra = $row['usuario'];
                $query_estado = "SELECT movimiento,fecha,usuario FROM comercial.log_comercial as lg
                                 WHERE  lg.movimiento LIKE '%ORDEN DE TRABAJO. $cod_trabajo_compra %' OR lg.movimiento LIKE '%ORDEN DE compra. $cod_trabajo_compra%' or lg.movimiento LIKE '%autorizacion de edicion de orden $cod_trabajo_compra%'";
                $res = mysqli_query($con, $query_estado);
                //$filas = mysqli_num_rows($res);            
                $salida_dato = '
                <td>' . $row['f_registro'] . '</td>
                <td>' . $row['distrito'] . '</td>
                <td>' . $row['usuario'] . '</td>
                <td>' . $row['cod_cliente'] . '</td>
                <td>' . $row['cod_poliza'] . '</td>
                <td>' . $row['cod_siniestro'] . '</td>
                <td>' . $row['cod_trabajo_compra'] . '</td>
                <td>' . $row['asegurado'] . '</td>
                <td>' . $row['cobertura'] . '</td>
                <td>' . $row['proveedor'] . '</td>
                <td>' . $row['cotizacion'] . '</td>
                <td>' . $row['moneda'] . '</td>
                <td>' . $row['sum_monto'] . '</td>
                <td>' . $row['descuento'] . '</td>
                <td>' . $row['total'] . '</td>
                ';

                while ($f = mysqli_fetch_assoc($res)) {
                    echo '<tr>';
                    echo $salida_dato;
                    echo '<td>' . $f['movimiento'] . '</td>
                          <td>' . $f['fecha'] . '</td>
                          <td>' . $f['usuario'] . '</td>';
                    echo '</tr>';
                }

            }

            ?>
               
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>