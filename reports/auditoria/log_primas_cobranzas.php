<?php
$msj_log = "REPORTE DE LOGS DE COBRANZAS - NUEVO REPORTE";

$consulta = "SELECT * FROM comercial.primas_cobranzas WHERE estado <> 'ELIMINADO'";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " and f_registro like '%$fecha_dia%'";
    $titulo = "VISTA PREVIA DE REPORTE LOGS DE COBRANZAS DEL $fecha_dia";
} else {
    $consulta .= " and f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
    $titulo = "VISTA PREVIA DE REPORTE LOGS DE COBRANZAS DESDE $fecha_inicio HASTA $fecha_final";
}

//$consulta .= " AND cod_poliza = 'AUCB00000624'";
$consulta .= " ORDER BY id_pxc";
$resultado = mysqli_query($con, $consulta);
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($con));
}

?>
<h2 align="center"><?= $titulo ?></h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class="tabla_datos table-striped table-bordered table table-hover" cellspacing="0" width="100%" id="tabla_generar">
        <thead>
            <tr class="text-center">
                <th>Fecha de Registro</th>
                <th>Código Póliza</th>
                <th>Código Cliente</th>
                <th>Código de Cotización</th>
                <th>Fecha de Pago (Emisión)</th>
                <th>Prima</th>
                <th>Tipo de Pago</th>
                <th>Cuota</th>
                <th>Estado</th>
                <th>Nro. Factura</th>
                <th>Intermediario</th>
                <th>Canal</th>
                <th>Fecha de Pago (Cobranzas)</th>
                <th>Movimiento (editar)</th>
                <th>Fecha de Modificación</th>
                <th>Usuario</th>
                <th>Antes</th>
                <th>Después</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($resultado)) {
                $id = $row['id_pxc'];
                $query_log = "SELECT * FROM comercial.log_cobranzas WHERE registro = ? AND antes <> ''";
                $stmt = mysqli_prepare($con, $query_log);
                if ($stmt === false) {
                    die('Error al preparar la consulta: ' . mysqli_error($con));
                }
                mysqli_stmt_bind_param($stmt, 'i', $id); 
                mysqli_stmt_execute($stmt);
                $res_log = mysqli_stmt_get_result($stmt);
                if ($log = mysqli_fetch_assoc($res_log)) {
                    $movimiento = $log['movimiento'];
                    $f_movimiento = $log['fecha'];
                    $usuario_m = $log['usuario'];
                    $antes = $log['antes'];
                    $despues = $log['despues'];
                } 

                
                if ($log = mysqli_fetch_assoc($res_log)) {
                    $movimiento = $log['movimiento'];
                    $f_movimiento = $log['f_registro'];
                    $usuario_m = $log['usuario'];
                    $antes = $log['antes'];
                    $despues = $log['despues'];

                    
                    echo '<tr>';
                    echo '<td>' . $row['f_registro'] . '</td>';
                    echo '<td>' . $row['cod_poliza'] . '</td>';
                    echo '<td>' . $row['cod_cliente'] . '</td>';
                    echo '<td>' . $row['cod_cotizacion'] . '</td>';
                    echo '<td>' . $row['fecha_emision'] . '</td>';
                    echo '<td>' . $row['prima_total'] . '</td>';
                    echo '<td>' . $row['tipo_pago'] . '</td>';
                    echo '<td>' . $row['cuotaS'] . '</td>';
                    echo '<td>' . $row['estado'] . '</td>';
                    echo '<td>' . $row['numero_factura'] . '</td>';
                    echo '<td>' . $row['intermediario'] . '</td>';
                    echo '<td>' . $row['canal'] . '</td>';
                    echo '<td>' . $row['f_pago'] . '</td>';
                    // Mostrar datos del log
                    echo '<td>' . $movimiento . '</td>';
                    echo '<td>' . $f_movimiento . '</td>';
                    echo '<td>' . $usuario_m . '</td>';
                    echo '<td>' . $antes . '</td>';
                    echo '<td>' . $despues . '</td>';
                    echo '</tr>';
                } else {
                   
                    echo '<tr>';
                    echo '<td>' . $row['f_registro'] . '</td>';
                    echo '<td>' . $row['cod_poliza'] . '</td>';
                    echo '<td>' . $row['cod_cliente'] . '</td>';
                    echo '<td>' . $row['cod_cotizacion'] . '</td>';
                    echo '<td>' . $row['fecha_emision'] . '</td>';
                    echo '<td>' . $row['prima_total'] . '</td>';
                    echo '<td>' . $row['tipo_pago'] . '</td>';
                    echo '<td>' . $row['cuota'] . '</td>';
                    echo '<td>' . $row['estado'] . '</td>';
                    echo '<td>' . $row['numero_factura'] . '</td>';
                    echo '<td>' . $row['intermediario'] . '</td>';
                    echo '<td>' . $row['canal'] . '</td>';
                    echo '<td>' . $row['f_pago'] . '</td>';
                    // Datos vacíos en caso de no tener logs
                    echo '<td> </td><td> </td><td> </td><td> </td><td> </td>';
                    echo '</tr>';
                }

                // Cerrar la declaración preparada
                mysqli_stmt_close($stmt);
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>