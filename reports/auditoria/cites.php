<?php
$msj_log = "REPORTE DE CITES DE CORRESPONDENCIA - NUEVO REPORTE";
$consulta = "SELECT * FROM correspondencia.cites WHERE estado<>'ELIMINADO'";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " AND fecha LIKE '%$fecha_dia%'";
    $titulo = "VISTA PREVIA DE REPORTE CITES DEL $fecha_dia";
} else {
    $consulta .= " AND fecha >= '$fecha_inicio' AND fecha <= '$fecha_final'";
    $titulo = "VISTA PREVIA DE REPORTE CITES DESDE $fecha_inicio HASTA $fecha_final";
}
$consulta .= " ORDER BY fecha ASC";

$resultado = mysqli_query($con, $consulta);
?>

<h2 align="center"><?= $titulo ?></h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class="tabla_datos table-striped table-bordered table table-hover" cellspacing="0" width="100%" id="tabla_generar">
        <thead>
            <tr class="text-center">
                <th>Id cite</th>
                <th>Área</th>
                <th>Nro cite</th>
                <th>Regional</th>
                <th>Tipo de documento</th>
                <th>Fecha de registro</th>
                <th>Fecha del documento</th>
                <th>Elaborador</th>
                <th>Destinatario</th>
                <th>Referencia</th>
                <th>Número carta</th>
                <th>Gestión</th>
                <th>Usuario</th>
                <th>Id número</th>
                <th>Distrito</th>
                <th>Estado</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($resultado)) {
                echo '<tr>';
                echo '<td>' . $row['id_cite'] . '</td>';
                echo '<td>' . $row['area'] . '</td>';
                echo '<td>' . $row['cite'] . '</td>';
                echo '<td>' . $row['regional'] . '</td>';
                echo '<td>' . $row['tipo'] . '</td>';
                echo '<td>' . $row['fecha'] . '</td>';
                echo '<td>' . $row['fecha'] . '</td>'; // Si tienes otro campo para fecha del documento, cámbialo aquí
                echo '<td>' . $row['elaborado'] . '</td>';
                echo '<td>' . $row['destinatario'] . '</td>';
                echo '<td>' . $row['referencia'] . '</td>';
                echo '<td>' . $row['numero_carta'] . '</td>';
                echo '<td>' . $row['gestion'] . '</td>';
                echo '<td>' . $row['usuario'] . '</td>';
                echo '<td>' . $row['idnumero'] . '</td>';
                echo '<td>' . $row['distrito'] . '</td>';
                echo '<td>' . $row['estado'] . '</td>';
                echo '<td>' . $row['observaciones'] . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>