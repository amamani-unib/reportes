<?php
//include "config/config.php";;
//$cierre = $_POST['cierre'];
$msj_log = "REPORTE DE REGISTRO DE CAUCIONES - LOG - NUEVO REPORTE";
$consulta = "SELECT poliza,id_registro_cot FROM cauciones.ramo_datos_generales as rd
             WHERE  ";


if (!isset($_POST['cb_lapso'])) {
    $consulta .= " rd.fecha_registro like '%$fecha_dia%'";
    $titulo = "VISTA PREVIA DE REGISTROS DE CAUCIONES DEL $fecha_dia";
} else {
    $consulta .= " rd.fecha_registro >= '$fecha_inicio' and rd.fecha_registro <= '$fecha_final'";
    $titulo = "VISTA PREVIA DE REGISTROS DE CAUCIONES DESDE $fecha_inicio HASTA $fecha_final";
}
//$consulta .= " and (lg.movimiento LIKE '%REGISTRO DE SINIESTRO. %' OR lg.movimiento LIKE '%ACTUALIZACION DE ESTADO DEL SINIESTRO:. %') ORDER BY id_log ASC";
//$consulta .= " and poliza='ADACB00000156'";
//echo $consulta;

$resultado = mysqli_query($con, $consulta);

?>
<h2 align="center"><?= $titulo ?></h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th style="text-align: center;">Poliza</th>
                <th style="text-align: center;">Id</th>
                <th style="text-align: center;">Fecha carga doc legal</th>
                <th style="text-align: center;">Usuario</th>
                <th style="text-align: center;">Fecha ultima modificacion</th>
                <th style="text-align: center;">Usuario Modificacion</th>
                <th style="text-align: center;">Fecha carga acta</th>
                <th style="text-align: center;">Usuario acta</th>
                <th style="text-align: center;">Fecha ultima modificacion acta</th>
                <th style="text-align: center;">Usuario Modificacion acta</th>
                <th style="text-align: center;">Fecha registro informe riesgo</th>
                <th style="text-align: center;">Usuario riesgo</th>
                <th style="text-align: center;">Fecha registro contrato</th>
                <th style="text-align: center;">Usuario contrato</th>
                <th style="text-align: center;">Fecha respaldos</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($resultado)) {
                $id_registro_cot = $row['id_registro_cot'];

                $query_legal_informe = "SELECT usuario, f_registro, f_actualizacion, usuario_actualizacion FROM cauciones.revision_legal_informe WHERE id_registro_cot ='$id_registro_cot'";
                $sql1 = $con->query($query_legal_informe);
                $f1 = $sql1->fetch_assoc();
                $usuario = isset($f1['usuario']) ? $f1['usuario'] : 'SIN USUARIO';
                $f_registro  = isset($f1['f_registro']) ? $f1['f_registro'] : 'SIN FECHA';
                $f_actualizacion  = isset($f1['f_actualizacion']) ? $f1['f_actualizacion'] : 'SIN FECHA';
                $usuario_actualizacion = isset($f1['usuario_actualizacion']) ? $f1['usuario_actualizacion'] : 'SIN USUARIO';

                $query_acta = "SELECT usuario, f_registro,fecha_modificacion FROM cauciones.actas WHERE id_registro_cot ='$id_registro_cot'";
                $sql2 = $con->query($query_acta);
                $f2 = $sql2->fetch_assoc();
                $usuario_acta = isset($f2['usuario']) ? $f2['usuario'] : 'SIN USUARIO';
                $f_registro_acta  = isset($f2['f_registro']) ? $f2['f_registro'] : 'SIN FECHA';
                $f_actualizacion_acta  = isset($f2['fecha_modificacion']) ? $f2['fecha_modificacion'] : 'SIN FECHA';
                $usuario_actualizacion_acta = isset($f2['usuario']) ? $f2['usuario'] : 'SIN USUARIO';

                $query_riesto = "SELECT usuario, f_registro FROM cauciones.informes_ada WHERE id_registro_cot ='$id_registro_cot'";
                $sql3 = $con->query($query_riesto);
                $f3 = $sql3->fetch_assoc();
                $usuario_riesto = isset($f3['usuario']) ? $f3['usuario'] : 'SIN USUARIO';
                $f_registro_riesto  = isset($f3['f_registro']) ? $f3['f_registro'] : 'SIN FECHA';

                $query_cotizacion = "SELECT c.fecha_contrato, u.usuario,c.fecha_respaldos FROM cauciones.cotizaciones as c INNER JOIN cauciones.usuarios_cauciones as u ON c.id_legal = u.cod_usuario WHERE c.id_registro ='$id_registro_cot'";
                $sql4 = $con->query($query_cotizacion);
                $f4 = $sql4->fetch_assoc();
                $usuario_contrato_legal = isset($f4['usuario']) ? $f4['usuario'] : 'SIN USUARIO';
                $f_registro_contrato  = isset($f4['fecha_contrato']) ? $f4['fecha_contrato'] : 'SIN FECHA';
                $f_respaldos  = isset($f4['fecha_respaldos']) ? $f4['fecha_respaldos'] : 'SIN FECHA';

            ?>
                <tr>
                    <td><?php echo $row['poliza']; ?></td>
                    <td><?php echo $id_registro_cot ?></td>
                    <td><?php echo $f_registro ?></td>
                    <td><?php echo $usuario ?></td>
                    <td><?php echo $f_actualizacion ?></td>
                    <td><?php echo $usuario_actualizacion ?></td>
                    <td><?php echo $f_registro_acta ?></td>
                    <td><?php echo $usuario_acta ?></td>
                    <td><?php echo $f_actualizacion_acta ?></td>
                    <td><?php echo $usuario_actualizacion_acta ?></td>
                    <td><?php echo $f_registro_riesto ?></td>
                    <td><?php echo $usuario_riesto ?></td>
                    <td><?php echo $f_registro_contrato ?></td>
                    <td><?php echo $usuario_contrato_legal ?></td>
                    <td><?php echo $f_respaldos ?></td>
                </tr>
            <?php
            }
            ?>

        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>