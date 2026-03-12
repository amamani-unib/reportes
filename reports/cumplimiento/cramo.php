<?php

$msj_log = "REPORTE DE POLIZAS POR COMULO";

$condicion_fecha = "";
if (!isset($_POST['cb_lapso'])) {
    $condicion_fecha = "pc.f_registro LIKE '%$fecha_dia%'";
    $titulo = "VISTA PREVIA DE REPORTE CUMULO POR RAMO DEL $fecha_dia";
} else {
    $condicion_fecha = "pc.f_registro >= '$fecha_inicio' AND pc.f_registro <= '$fecha_final'";
    $titulo = "VISTA PREVIA DE REPORTE CUMULO POR RAMO DESDE $fecha_inicio HASTA $fecha_final";
}

$consulta = "
    SELECT rc.ramo,sum(pc.prima_total) as s_prima, pc.movimiento,rc.f_registro, GROUP_CONCAT(DISTINCT pc.cod_poliza ORDER BY pc.cod_poliza SEPARATOR ', ') AS polizas_incluidas, 
    pc.cod_cliente,rc.asegurado, rc.tomador
    FROM comercial.primas_cobranzas AS pc INNER JOIN comercial.pol_reporte_comercial as rc ON rc.cod_poliza=pc.cod_poliza
    WHERE 
        pc.estado <> 'ELIMINADO'
        AND $condicion_fecha AND pc.cod_cliente<>'CUBLP00000068'
    GROUP by rc.ramo,rc.cod_cliente having s_prima > 5000 
    ORDER BY pc.cod_cliente ASC
";
$consulta;


$resultado = mysqli_query($con, $consulta);

?>
<h2 align="center"><?= $titulo ?> </h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th>ASEGURADO</th>
                <th>TOMADOR</th>
                <th>RAMO</th>
                <th>COD. CLIENTE</th>
                <th>PRIMA TOTAL (CUMULO TOTAL)</th>
                <th>ID-COBRANZAS</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($resultado)) {
                $polizas_incluidas = explode(',', str_replace(' ', '', $row['polizas_incluidas']));
                $suma_primas = 0;
                $cumulo_id_pcx = []; // Array para almacenar los id_pxc de las cuotas sumadas
                foreach ($polizas_incluidas as $poliza) {
                    $query = "SELECT id_pxc, prima_total,movimiento,cod_poliza FROM comercial.primas_cobranzas as pc 
                    WHERE cod_poliza = '$poliza' AND estado <> 'ELIMINADO' AND $condicion_fecha";
                    $res = mysqli_query($con, $query);
                    while ($data = mysqli_fetch_assoc($res)) {
                        $prima = floatval($data['prima_total']);
                        if ($prima > 5000) {
                            continue;
                        }
                        $suma_primas += $prima;
                        $cumulo_id_pcx[] = $poliza . ' - ' . $data['id_pxc'] . ' - ' . $row['movimiento']; // Guarda el id_pxc de la cuota sumada
                        if ($suma_primas > 5000) {
            ?>
                            <tr>
                                <td><?php echo $row['asegurado']; ?></td>
                                <td><?php echo $row['tomador']; ?></td>
                                <td><?php echo $row['ramo']; ?></td>
                                <td><?php echo $row['cod_cliente']; ?></td>
                                <td><?php echo $suma_primas; ?></td>
                                <td><?php echo implode(', ', $cumulo_id_pcx); ?></td>
                            </tr>
            <?php
                            $suma_primas = 0;
                            $cumulo_id_pcx = []; // Reinicia el array para la siguiente suma
                        }
                    }
                }

                //}
            } ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>