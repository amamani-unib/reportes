<?php
$consulta = "SELECT pc.intermediario,

rc.modalidad_ramo,rc.cia,pc.lugar,rc.modalidad,rc.cod_poliza,pc.cambio,pc.movimiento,pc.id_calculo_prima,
            ROUND(pc.valor_asegurado,2) as valor_asegurado,ROUND(pc.valor_bolivianos,2) as valor_bolivianos,ROUND(pc.valor_dolares,2) as valor_dolares, rc.valor_asegurados,
                                ROUND (case 
                                  when pc.tipo_pago='CONTADO' THEN pc.prima_contado  
                                  when pc.tipo_pago='CREDITO' THEN pc.prima_credito
                                END ,2) as prima_total_c,
            pc.moneda,pc.fecha_emision,rc.cod_ramo,rc.tipo_poliza,pc.cambio
            FROM comercial.pol_reporte_comercial as rc INNER JOIN comercial.pol_calculo_prima as pc ON rc.cod_poliza=pc.cod_poliza AND rc.cod_cotizacion = pc.cod_cotizacion";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " WHERE pc.f_registro like '%$fecha_dia%'";
    $fecha_aux = $fecha_dia;
    $msj_log = "REPORTE PRODUCCIÓN COMERCIAL BASE UNIERSOFT EL $fecha_dia";
    $titulo = "VISTA PREVIA DE REPORTE PRODUCCIÓN SISTEMA UNISERSOFT. DEL $fecha_dia";
} else {
    $consulta .= " WHERE pc.f_registro >= '$fecha_inicio' and pc.f_registro <= '$fecha_final'";
    $fecha_aux = $fecha_final;
    $msj_log = "REPORTE PRODUCCIÓN COMERCIAL BASE UNIERSOFT ENTRE $fecha_inicio Y $fecha_final";
    $titulo = "VISTA PREVIA DE REPORTE PRODUCCIÓN SISTEMA UNISERSOFT. DESDE $fecha_inicio HASTA $fecha_final";
}

$consulta .= " and rc.cod_poliza<>'ELIMINADO' GROUP BY pc.id_calculo_prima ORDER BY pc.f_registro ASC";
//echo $consulta;
$result = mysqli_query($con, $consulta);
?>
<h2 align="center"><?= $titulo ?> </h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%'
        id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th>Corredor de Seguros</th>
                <th>Código corredor de seguros</th>
                <th>Tomador</th>
                <th>Asegurado</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>

            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['tipo_cartera'] == 'ESTATAL') {
                    $inprime = $row['intermediario'];
                    $inter = "CARTERA DIRECTA";
                    $cod_sector = "E";
                } else {
                    $inprime = "";
                    $inter = $row['intermediario'];
                    $cod_sector = "P";
                }
                if ($row['prima_total_c'] < 0) {
                    $asistencia_vial = 'NO';
                } else {
                    $asistencia_vial = $row['asi_vial'];
                }
                $parte = "P";
                $lugar = $row['lugar'];
                switch ($lugar) {
                    case 'LA PAZ':
                        $cod_oficina = "LPZ";
                        break;
                    case 'COCHABAMBA':
                        $cod_oficina = "CBB";
                        break;
                    case 'SANTA CRUZ':
                        $cod_oficina = "SCZ";
                        break;
                    case 'SUCRE':
                        $cod_oficina = "SUC";
                        break;
                    case 'POTOSI':
                        $cod_oficina = "PTS";
                        break;
                    case 'TARIJA':
                        $cod_oficina = "TJA";
                        break;
                    case 'ORURO':
                        $cod_oficina = "ORU";
                        break;
                    case 'PANDO':
                        $cod_oficina = "PAN";
                        break;
                    case 'BENI':
                        $cod_oficina = "BEN";
                        break;
                    default:
                        $cod_oficina = "LPZ";
                }
                $cod_moneda = $row['moneda'];
                switch ($cod_moneda) {
                    case '01':
                        $cod_moneda = "1";
                        break;
                    case '02':
                        $cod_moneda = "2";
                        break;
                    case '04':
                        $cod_moneda = "4";
                        break;
                    case '05':
                        $cod_moneda = "5";
                        break;
                    default:
                        $cod_moneda = "";
                }
                if ($row['valor_asegurados'] == '0') {
                    $valor_asegurado_total = $row['valor_asegurado'];
                } else {
                    $valor_asegurado_total = $row['valor_boliviano'] + $row['valor_dolares'] * $row['cambio'];
                }
                $prima_total_c = $row['prima_total_c'];
                $prima_neta_anulada = $prima_total_c;
                $prima_neta_anulada_bs = $prima_total_c * $row['cambio'];
                $prima_directa_boliviano = 0;
                $prima_directa = 0;
                $prima_anulada = 0;
                $prima_anulada_bs = 0;
                $monto_capital_asegurado = 0;
                $capital_anulado = 0;
                $polizas_netas_anuladas = 1;
                $capital_asegurado_neto = $valor_asegurado_total * $row['cambio'];
                if ($prima_total_c > 0) {
                    $monto_capital_asegurado = $valor_asegurado_total * $row['cambio'];
                    $prima_directa = $prima_total_c;
                    $prima_directa_boliviano = $row['prima_total_c'] * $row['cambio'];
                } else if ($prima_total_c < 0) {
                    $capital_anulado = $valor_asegurado_total * $row['cambio'] * -1;
                    $prima_anulada = $prima_total_c * -1;
                    $prima_anulada_bs = $prima_anulada * $row['cambio'] * -1;
                    $polizas_netas_anuladas = -1;
                }
                $movimiento = $row['movimiento'];
                $poliza_nueva_emitida = 0;
                $poliza_renovada = 0;
                $polizas_anuladas = 0;

                switch ($movimiento) {
                    case 'NUEVO':
                        $cod_movimiento = "N";
                        $poliza_nueva_emitida = 1;

                        break;
                    case 'RENOVACION':
                        $cod_movimiento = "R";
                        $poliza_renovada = 1;
                        break;
                    case 'DEVOLUCION':
                    case 'RESCISION':
                    case 'ANULACION POR FALTA DE PAGO':
                        $cod_movimiento = "A";
                        break;
                    case 'ANULACION':
                        $cod_movimiento = "A";
                        $polizas_anuladas = 1;
                        break;
                    case 'INCLUSION':
                        $cod_movimiento = "I";
                        break;
                    case 'EXCLUSION':
                        $cod_movimiento = "E";
                        break;
                    case 'AMPLIACION':
                        $cod_movimiento = "V";
                        break;
                    case 'DECREMENTO':
                        $cod_movimiento = "D";
                        break;
                    case 'INCREMENTO':
                        $cod_movimiento = "T";
                        break;
                    case 'APLICACION':
                        $cod_movimiento = "P";
                        break;
                    case 'LIQUIDACION':
                        $cod_movimiento = "L";
                        break;
                    default:
                        $cod_movimiento = "";
                }

                $cod_modalidad = $row['modalidad_ramo'];
                if ($cod_modalidad == '') {
                    $cod_modalidad = 91;
                } else {
                    $cod_modalidad = $row['modalidad_ramo'];
                }
                $cod_ramo = $row['cod_ramo'];
                if ($cod_ramo == '' || !is_numeric($cod_ramo)) {
                    $tipo_poliza = $row['tipo_poliza'];
                    $sele2 = $con->query("SELECT cod_ramo from comercial.x_ramo where tipo_poliza = '$tipo_poliza'order by id_ramo desc limit 1");
                    $filas2 = $sele2->fetch_assoc();
                    $cod_ramo = $filas2['cod_ramo'];
                } else {
                    $cod_ramo = $row['cod_ramo'];
                }

            ?>
                <tr>
                    <td><?php echo $row['id_calculo_prima']; ?></td>
                    <td><?php echo $row['cia']; ?></td>
                    <td><?php echo $parte; ?></td>
                    <td><?php echo $cod_oficina; ?></td>
                    <td><?php echo $cod_sector; ?></td>
                    <td><?php echo $cod_moneda; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $cod_modalidad; ?></td>
                    <td><?php echo $cod_ramo; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo ROUND($row['cambio'], 2); ?></td>
                    <td><?php echo $cod_movimiento; ?></td>
                    <td><?php echo $monto_capital_asegurado; ?></td>
                    <td><?php echo $poliza_nueva_emitida; ?></td>
                    <td><?php echo $poliza_renovada; ?></td>
                    <td><?php echo $prima_directa; ?></td>
                    <td><?php echo $prima_directa_boliviano; ?></td>
                    <td><?php echo $capital_anulado; ?></td>
                    <td><?php echo $polizas_anuladas; ?></td>
                    <td><?php echo $prima_anulada; ?></td>
                    <td><?php echo $prima_neta_anulada_bs; ?></td>
                    <td><?php echo $capital_asegurado_neto; ?></td>
                    <td><?php echo $polizas_netas_anuladas; ?></td>
                    <td><?php echo $prima_neta_anulada; ?></td>
                    <td><?php echo $prima_neta_anulada_bs; ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>