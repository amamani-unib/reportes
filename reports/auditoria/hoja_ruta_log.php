<?php
$consulta = "SELECT 
    c.`id_cartas`,c.`fecha_entrada`,c.`fecha_registro`,c.`fecha_documento`,c.`cite_externo`,
    c.`remitente`,c.`referencia`,c.`tipo_documento`,c.`num_hoja_ruta`,c.`recepcionado`,
    c.`exterior` ,c.`cod_exterior`,c.`fecha_recepcion`,c.`resp_corresp` ,c.`fecha_resp_corresp`,
    c.`archivo`,c.`loc_archivo_fisico`,c.`departamento`,c.`user`,c.`distrito`,c.`estado`,c.`observaciones`   
FROM 
    correspondencia.cartas c
WHERE c.estado NOT IN  ('ANULADO','ELIMINADO')  
";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " AND c.fecha_registro like '%$fecha_dia%'";
    $fecha_aux = $fecha_dia;
    $msj_log = "REPORTE DEL SISTEMA DE CORRESPONDENCIA HOJAS DE RUTA LOG DEL $fecha_dia";
    $titulo = "VISTA PREVIA DE REPORTE CORRESPONDENCIA UNIBIENES. DEL $fecha_dia";
} else {
    $consulta .= " AND c.fecha_registro >= '$fecha_inicio' and c.fecha_registro <= '$fecha_final'";
    $fecha_aux = $fecha_final;
    $msj_log = "REPORTE DEL SISTEMA DE CORRESPONDENCIA HOJAS DE RUTA LOG ENTRE $fecha_inicio Y $fecha_final";
    $titulo = "VISTA PREVIA DE REPORTE CORRESPONDENCIA UNIBIENES. DESDE $fecha_inicio HASTA $fecha_final";
}


//$consulta .= " ORDER BY c.id_cartas DESC";
//echo $consulta;
$resultado = mysqli_query($con, $consulta);
?>
<h2 align="center"><?= $titulo ?> </h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class="tabla_datos table-striped table-bordered table table-hover" cellspacing="0" width="100%" id="tabla_generar">
        <thead>
            <tr class="text-center">
                <th>Id cartas</th>
                <th>Fecha entrada</th>
                <th>Fecha registro</th>
                <th>Fecha documento</th>
                <th>Cite / externo</th>
                <th>Remitente</th>
                <th>Referencia</th>
                <th>Tipo documento</th>
                <th>Nro hoja ruta</th>
                <th>recepcionado</th>
                <th>Exterior</th>
                <th>Cod exterior</th>
                <th>Fecha recepción</th>
                <th>Resp. correspondencia</th>
                <th>Fecha respuesta correspondencia</th>
                <th>Archivo</th>
                <th>Loc. archivo físico</th>
                <th>Departamento</th>
                <th>Usuario</th>
                <th>Distrito</th>
                <th>Estado</th>
                <th>observaciones</th>
                <th>En posesión de</th>
                <th>Fecha de operacion</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Recorrer resultados de la consulta principal
            while ($row = mysqli_fetch_assoc($resultado)) {
                $id = $row['id_cartas'];
                $query_log = "SELECT trans_user, fecha_trans FROM correspondencia.log_cartas WHERE id_cartas = '$id'";
                $res = mysqli_query($con, $query_log);

                // Arrays para almacenar los movimientos
                $usuarios = [];
                $fechas = [];
                while ($f = mysqli_fetch_assoc($res)) {
                    $usuarios[] = $f['trans_user'];
                    $fechas[] = $f['fecha_trans'];
                }

                echo '<tr>';
                echo '<td>' . $row['id_cartas'] . '</td>';
                echo '<td>' . $row['fecha_entrada'] . '</td>';
                echo '<td>' . $row['fecha_registro'] . '</td>';
                echo '<td>' . $row['fecha_documento'] . '</td>';
                echo '<td>' . $row['cite_externo'] . '</td>';
                echo '<td>' . $row['remitente'] . '</td>';
                echo '<td>' . $row['referencia'] . '</td>';
                echo '<td>' . $row['tipo_documento'] . '</td>';
                echo '<td>' . $row['num_hoja_ruta'] . '</td>';
                echo '<td>' . $row['recepcionado'] . '</td>';
                echo '<td>' . $row['exterior'] . '</td>';
                echo '<td>' . $row['cod_exterior'] . '</td>';
                echo '<td>' . $row['fecha_recepcion'] . '</td>';
                echo '<td>' . $row['resp_corresp'] . '</td>';
                echo '<td>' . $row['fecha_resp_corresp'] . '</td>';
                echo '<td>' . $row['archivo'] . '</td>';
                echo '<td>' . $row['loc_archivo_fisico'] . '</td>';
                echo '<td>' . $row['departamento'] . '</td>';
                echo '<td>' . $row['user'] . '</td>';
                echo '<td>' . $row['distrito'] . '</td>';
                echo '<td>' . $row['estado'] . '</td>';
                echo '<td>' . $row['observaciones'] . '</td>';
                // Muestra todos los usuarios y fechas en una sola celda, separados por salto de línea
                echo '<td>' . implode('<br>', $usuarios) . '</td>';
                echo '<td>' . implode('<br>', $fechas) . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>