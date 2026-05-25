<?php
include_once "utils/items.php";
?>
<h3 style="text-align:center; font-family:'Courier New', Courier, monospace" align="center">REGISTRO DEL SISTEMA DE
    CAUCIONES</h3>
<table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%'
    id='tabla_generar'>
    <thead>
        <tr>
            <th colspan="1" style="text-align: center; border:1px solid black"></th>
            <th colspan="4" style="text-align: center; border:1px solid black">LEGAL INFORME</th>
            <th colspan="4" style="text-align: center; border:1px solid black">DATOS ACTA</th>
            <th colspan="4" style="text-align: center; border:1px solid black">ACTA DE APROBACION DE OPERACIONES
                PUNTUALES</th>
            <th colspan="4" style="text-align: center; border:1px solid black">ANALISIS DE RIESGO</th>
            <th colspan="4" style="text-align: center; border:1px solid black">CONTRATO LEGAL</th>
            <th colspan="4" style="text-align: center; border:1px solid black"><?= utf8_decode('RESPALDO - TRÁMITE') ?>
            </th>
        </tr>
        <tr class='text-center'>
            <th style="text-align: center; border:1px solid black">POLIZA</th>

            <th style="text-align: center; border:1px solid black">FECHA DE CARGA DEL DOCUMENTO</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>
            <th style="text-align: center; border:1px solid black">FECHA DE ULTIMA MODIFICACION</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>

            <th style="text-align: center; border:1px solid black">FECHA DE REGISTRO</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>
            <th style="text-align: center; border:1px solid black">FECHA DE MODIFICACION</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>

            <th style="text-align: center; border:1px solid black">FECHA DE CARGA DEL DOCUMENTO</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>
            <th style="text-align: center; border:1px solid black">FECHA DE ULTIMA MODIFICACION</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>

            <th style="text-align: center; border:1px solid black">FECHA DE CARGA DEL DOCUMENTO</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>
            <th style="text-align: center; border:1px solid black">FECHA DE ULTIMA MODIFICACION</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>

            <th style="text-align: center; border:1px solid black">FECHA DE CARGA DEL DOCUMENTO</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>
            <th style="text-align: center; border:1px solid black">FECHA DE ULTIMA MODIFICACION</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>

            <th style="text-align: center; border:1px solid black">FECHA DE CARGA DEL DOCUMENTO</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>
            <th style="text-align: center; border:1px solid black">FECHA DE ULTIMA MODIFICACION</th>
            <th style="text-align: center; border:1px solid black">USUARIO</th>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $msj_log = "REPORTE DE REGISTRO DE CAUCIONES - LOG - NUEVO REPORTE";
        $consulta = "SELECT poliza,id_registro_cot FROM cauciones.ramo_datos_generales as rd
             WHERE rd.id_registro_cot IS NOT NULL and ";
        if (!isset($_POST['cb_lapso'])) {
            $consulta .= " rd.fecha_registro like '%$fecha_dia%'";
            $titulo = "VISTA PREVIA DE REGISTROS DE CAUCIONES DEL $fecha_dia";
        } else {
            $consulta .= " rd.fecha_registro >= '$fecha_inicio' and rd.fecha_registro <= '$fecha_final'";
            $titulo = "VISTA PREVIA DE REGISTROS DE CAUCIONES DESDE $fecha_inicio HASTA $fecha_final";
        }
        //echo $consulta;
        $result = mysqli_query($con, $consulta);
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

                $id_registro_cot = $row['id_registro_cot'];

                $query_legal_informe = "SELECT usuario, f_registro, f_actualizacion, usuario_actualizacion FROM cauciones.revision_legal_informe WHERE id_registro_cot ='$id_registro_cot'";
                $sql1 = $con->query($query_legal_informe);
                $f1 = $sql1->fetch_assoc();
                $usuario = isset($f1['usuario']) ? $f1['usuario'] : 'SIN USUARIO';
                $f_registro = isset($f1['f_registro']) ? $f1['f_registro'] : 'SIN FECHA';
                $f_actualizacion = isset($f1['f_actualizacion']) ? $f1['f_actualizacion'] : 'SIN FECHA';
                $usuario_actualizacion = isset($f1['usuario_actualizacion']) ? $f1['usuario_actualizacion'] : 'SIN USUARIO';

                $query_acta = "SELECT usuario, f_registro,fecha_modificacion FROM cauciones.actas WHERE id_registro_cot ='$id_registro_cot'";
                $sql2 = $con->query($query_acta);
                $f2 = $sql2->fetch_assoc();
                $usuario_acta = isset($f2['usuario']) ? $f2['usuario'] : 'SIN USUARIO';
                $f_registro_acta = isset($f2['f_registro']) ? $f2['f_registro'] : 'SIN FECHA';
                $f_actualizacion_acta = isset($f2['fecha_modificacion']) ? $f2['fecha_modificacion'] : 'SIN FECHA';
                $usuario_actualizacion_acta = isset($f2['usuario']) ? $f2['usuario'] : 'SIN USUARIO';

                $query_riesto = "SELECT usuario, f_registro FROM cauciones.informes_ada WHERE id_registro_cot ='$id_registro_cot'";
                $sql3 = $con->query($query_riesto);
                $f3 = $sql3->fetch_assoc();
                $usuario_riesto = isset($f3['usuario']) ? $f3['usuario'] : 'SIN USUARIO';
                $f_registro_riesto = isset($f3['f_registro']) ? $f3['f_registro'] : 'SIN FECHA';

                $query_cotizacion = "SELECT c.fecha_contrato, u.usuario,c.fecha_respaldos FROM cauciones.cotizaciones as c INNER JOIN cauciones.usuarios_cauciones as u ON c.id_legal = u.cod_usuario WHERE c.id_registro ='$id_registro_cot'";
                $sql4 = $con->query($query_cotizacion);
                $f4 = $sql4->fetch_assoc();
                $usuario_contrato_legal = isset($f4['usuario']) ? $f4['usuario'] : 'SIN USUARIO';
                $f_registro_contrato = isset($f4['fecha_contrato']) ? $f4['fecha_contrato'] : 'SIN FECHA';
                $f_respaldos = isset($f4['fecha_respaldos']) ? $f4['fecha_respaldos'] : 'SIN FECHA';

                $query_aprob = "SELECT min(f_registro) as min_f_registro, id_usuario FROM cauciones.log_registros WHERE id_registro_cot ='$id_registro_cot' and movimiento in ('ACTA_ACTUALIZADA')";
                $resultado_aprob = mysqli_query($con, $query_aprob);
                $f5 = mysqli_fetch_assoc($resultado_aprob);
                $usuario_aprob = isset($f5['id_usuario']) ? getUser($f5['id_usuario']) : 'SIN USUARIO';
                $f_registro_aprob = isset($f5['min_f_registro']) ? $f5['min_f_registro'] : 'SIN FECHA';


                $query_aprob_modificacion = "SELECT max(f_registro) as max_f_registro, id_usuario FROM cauciones.log_registros WHERE id_registro_cot ='$id_registro_cot' and movimiento in ('APROBACION_ACTA_ADJUNTOS')";
                $res_aprob_modificacion = mysqli_query($con, $query_aprob_modificacion);
                $f6 = mysqli_fetch_assoc($res_aprob_modificacion);
                $usuario_aprob_modificacion = isset($f6['id_usuario']) ? getUser($f6['id_usuario']) : 'SIN USUARIO';
                $f_registro_aprob_modificacion = isset($f6['max_f_registro']) ? $f6['max_f_registro'] : 'SIN FECHA';

                // respaldos de tramite maximo
                $query_respaldos = "SELECT max(f_registro) as max_f_registro, id_usuario FROM cauciones.log_registros WHERE id_registro_cot ='$id_registro_cot' and movimiento in ('RESPALDOS_TRAMITE_CARGADOS')";
                $res_respaldos = mysqli_query($con, $query_respaldos);
                $f7 = mysqli_fetch_assoc($res_respaldos);
                $f_registro_respaldos_max = isset($f7['max_f_registro']) ? $f7['max_f_registro'] : 'SIN FECHA';
                $usuario_respaldos_max = isset($f7['id_usuario']) ? getUser($f7['id_usuario']) : 'SIN USUARIO';

                $query_respaldos = "SELECT min(f_registro) as max_f_registro, id_usuario FROM cauciones.log_registros WHERE id_registro_cot ='$id_registro_cot' and movimiento in ('RESPALDOS_TRAMITE_CARGADOS')";
                $res_respaldos = mysqli_query($con, $query_respaldos);
                $f7 = mysqli_fetch_assoc($res_respaldos);
                $f_registro_respaldos_min = isset($f7['max_f_registro']) ? $f7['max_f_registro'] : 'SIN FECHA';
                $usuario_respaldos_min = isset($f7['id_usuario']) ? getUser($f7['id_usuario']) : 'SIN USUARIO';

                $query_respaldos = "SELECT max(f_registro) as max_f_registro, id_usuario FROM cauciones.log_registros WHERE id_registro_cot ='$id_registro_cot' and movimiento in ('CONTRATO_APROBADO_LEGAL')";
                $res_respaldos = mysqli_query($con, $query_respaldos);
                $f7 = mysqli_fetch_assoc($res_respaldos);
                $f_registro_contrato_max = isset($f7['max_f_registro']) ? $f7['max_f_registro'] : 'SIN FECHA';
                $usuario_contrato_max = isset($f7['id_usuario']) ? getUser($f7['id_usuario']) : 'SIN USUARIO';

                $query_respaldos = "SELECT max(f_registro) as max_f_registro, id_usuario FROM cauciones.log_registros WHERE id_registro_cot ='$id_registro_cot' and movimiento in ('INFORME_RIESGO_ADA_ENVIADO')";
                $res_respaldos = mysqli_query($con, $query_respaldos);
                $f7 = mysqli_fetch_assoc($res_respaldos);
                $f_registro_informe_riesgo_max = isset($f7['max_f_registro']) ? $f7['max_f_registro'] : 'SIN FECHA';
                $usuario_informe_riesgo_max = isset($f7['id_usuario']) ? getUser($f7['id_usuario']) : 'SIN USUARIO';

                $query_respaldos = "SELECT max(f_registro) as max_f_registro, id_usuario FROM cauciones.log_registros WHERE id_registro_cot ='$id_registro_cot' and movimiento in ('INFORME_LEGAL_APROBADO')";
                $res_respaldos = mysqli_query($con, $query_respaldos);
                $f7 = mysqli_fetch_assoc($res_respaldos);
                $f_registro_informe_legal_max = isset($f7['max_f_registro']) ? $f7['max_f_registro'] : 'SIN FECHA';
                $usuario_informe_legal_max = isset($f7['id_usuario']) ? getUser($f7['id_usuario']) : 'SIN USUARIO';

                $query_respaldos = "SELECT max(f_registro) as max_f_registro, id_usuario FROM cauciones.log_registros WHERE id_registro_cot ='$id_registro_cot' and movimiento in ('ACTA_ACTUALIZADA')";
                $res_respaldos = mysqli_query($con, $query_respaldos);
                $f7 = mysqli_fetch_assoc($res_respaldos);
                $f_registro_acta_max = isset($f7['max_f_registro']) ? $f7['max_f_registro'] : 'SIN FECHA';
                $usuario_acta_max = isset($f7['id_usuario']) ? getUser($f7['id_usuario']) : 'SIN USUARIO';
                ?>
                <tr>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $row['poliza']; ?>
                    </td>


                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_informe_legal_max ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_informe_legal_max ?>
                    </td>


                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_aprob ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_acta ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_acta_max ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_aprob ?>
                    </td>


                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_aprob ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_aprob ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_aprob_modificacion ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_aprob_modificacion ?>
                    </td>


                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_riesto ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_riesto ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_informe_riesgo_max ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_informe_riesgo_max ?>
                    </td>


                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_contrato ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_contrato_legal ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_contrato_max ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_contrato_max ?>
                    </td>

                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_respaldos_min ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_respaldos_min ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $f_registro_respaldos_max ?>
                    </td>
                    <td style="text-align: center; border:1px solid black">
                        <?php echo $usuario_respaldos_max ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>
<?php
function getUser($id)
{
    global $con;
    $id = $con->real_escape_string($id);
    $query = "SELECT usuario FROM cauciones.usuarios_cauciones WHERE cod_usuario = '$id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return isset($row['usuario']) ? $row['usuario'] : 'SIN USUARIO';
}