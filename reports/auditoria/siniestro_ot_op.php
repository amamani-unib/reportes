<?php
// Construir la consulta UNION para combinar registros de múltiples tablas
$filtro_log = "";
$filtro_otc = "";
$filtro_op = "";

if (!isset($_POST['cb_lapso'])) {
    $filtro_log = " AND DATE(lc.fecha) = '$fecha_dia'";
    $filtro_otc = " AND DATE(otc.f_registro) = '$fecha_dia'";
    $filtro_op = " AND DATE(op.f_registro) = '$fecha_dia'";
    $fecha_aux = $fecha_dia;
    $msj_log = "REPORTE AUDITORÍA DE SINIESTROS UNISERSOFT EL $fecha_dia";
    $titulo = "REPORTE AUDITORÍA DE SINIESTROS - SISTEMA UNISERSOFT DEL $fecha_dia";
} else {
    $filtro_log = " AND DATE(lc.fecha) >= DATE('$fecha_inicio') AND DATE(lc.fecha) <= DATE('$fecha_final')";
    $filtro_otc = " AND DATE(otc.f_registro) >= DATE('$fecha_inicio') AND DATE(otc.f_registro) <= DATE('$fecha_final')";
    $filtro_op = " AND DATE(op.f_registro) >= DATE('$fecha_inicio') AND DATE(op.f_registro) <= DATE('$fecha_final')";
    $fecha_aux = $fecha_final;
    $msj_log = "REPORTE AUDITORÍA DE SINIESTROS BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
    $titulo = "REPORTE AUDITORÍA DE SINIESTROS - SISTEMA UNISERSOFT DESDE $fecha_inicio HASTA $fecha_final";
}

// Consulta UNION: Registros de log_comercial + trabajo_compra + orden_pago
$consulta = "SELECT 
    SUBSTRING_INDEX(SUBSTRING_INDEX(lc.movimiento, '. ', -1), ' - ', 1) AS cod_siniestro,
    DATE(lc.fecha) AS fecha_registro,
    TIME(lc.fecha) AS hora_registro,
    lc.usuario AS usuario_registro,
    'LOG' AS tipo_fuente,
    lc.movimiento,
    lc.fecha,
    NULL AS otc_cod,
    NULL AS otc_moneda,
    NULL AS otc_monto,
    NULL AS otc_estado,
    NULL AS op_cod,
    NULL AS op_monto,
    NULL AS op_estado,
    NULL AS trabajo_compra_id
FROM comercial.log_comercial lc
WHERE (lc.movimiento LIKE 'REGISTRO DE SINIESTRO%' 
       OR lc.movimiento LIKE 'ACTUALIZACION DE ESTADO DEL SINIESTRO%')
AND TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(lc.movimiento, '. ', -1), ' - ', 1)) <> ''
AND UPPER(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(lc.movimiento, '. ', -1), ' - ', 1))) NOT IN ('ANULADO', 'ELIMINADO')
$filtro_log

UNION ALL

SELECT 
    otc.cod_siniestro,
    DATE(otc.f_registro) AS fecha_registro,
    TIME(otc.f_registro) AS hora_registro,
    otc.usuario AS usuario_registro,
    'OTC' AS tipo_fuente,
    otc.cod_trabajo_compra,
    otc.f_registro,
    otc.cod_trabajo_compra,
    otc.moneda,
    otc.total,
    otc.estado,
    NULL AS op_cod,
    NULL AS op_monto,
    NULL AS op_estado,
    otc.id_registro
FROM comercial.trabajo_compra otc
WHERE UPPER(TRIM(otc.cod_siniestro)) NOT IN ('', 'CORTE', 'ANULADO', 'ELIMINADO')
AND UPPER(TRIM(IFNULL(otc.estado, ''))) NOT IN ('ANULADO', 'ELIMINADO')
$filtro_otc

UNION ALL

SELECT 
    op.cod_siniestro,
    DATE(op.f_registro) AS fecha_registro,
    TIME(op.f_registro) AS hora_registro,
    op.usuario AS usuario_registro,
    'OP' AS tipo_fuente,
    op.cod_orden,
    op.f_registro,
    NULL,
    NULL,
    NULL,
    NULL,
    op.cod_orden,
    op.pago_total_bs,
    op.estado,
    NULL
FROM comercial.orden_pago op
WHERE UPPER(TRIM(op.cod_siniestro)) NOT IN ('', 'CORTE', 'ANULADO', 'ELIMINADO')
AND UPPER(TRIM(IFNULL(op.estado, ''))) NOT IN ('ANULADO', 'ELIMINADO')
$filtro_op
ORDER BY cod_siniestro, fecha DESC";

$result = mysqli_query($con, $consulta);

// Validar si la consulta tuvo error
if (!$result) {
    die("Error en la consulta SQL: " . mysqli_error($con) . "<br>Consulta: " . $consulta);
}

?>

<?php
// Funciones auxiliares para procesar datos según el tipo de fuente
function extraerMontoDelMovimiento($movimiento)
{
    // Extrae el número al final del texto después de "MONTO DE RESERVA:"
    if (preg_match('/MONTO DE RESERVA:\s*([\d.,]+)/', $movimiento, $matches)) {
        return trim($matches[1]);
    }
    return '';
}

function extraerEstadoDelMovimiento($movimiento)
{
    // Extrae el estado después de "ESTADO:" para ACTUALIZACION DE ESTADO
    if (preg_match('/ESTADO:\s*([A-Z]+)/', $movimiento, $matches)) {
        return trim($matches[1]);
    }
    return 'PENDIENTE'; // Por defecto para REGISTRO DE SINIESTRO
}

function determinarGlosa($movimiento)
{
    if (strpos($movimiento, 'REGISTRO DE SINIESTRO') === 0) {
        return 'REGISTRO DE SINIESTRO';
    } elseif (strpos($movimiento, 'ACTUALIZACION DE ESTADO DEL SINIESTRO') === 0) {
        return 'ACTUALIZACION DE SINIESTRO';
    }
    return '';
}

function determinarTipoOT_OC($cod_trabajo_compra)
{
    if (strpos($cod_trabajo_compra, 'OT') === 0) {
        return 'ORDEN DE TRABAJO';
    } elseif (strpos($cod_trabajo_compra, 'OC') === 0) {
        return 'ORDEN DE COMPRA';
    }
    return '';
}

function convertirImporteANumero($valor)
{
    if ($valor === null || $valor === '') {
        return 0;
    }

    if (is_numeric($valor)) {
        return (float)$valor;
    }

    $valor = trim((string)$valor);
    $valor = str_replace(' ', '', $valor);

    // Manejar casos como 1.234,56
    if (strpos($valor, ',') !== false && strpos($valor, '.') !== false) {
        if (strrpos($valor, ',') > strrpos($valor, '.')) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        } else {
            $valor = str_replace(',', '', $valor);
        }
    } else {
        $valor = str_replace(',', '.', $valor);
    }

    $valor = preg_replace('/[^0-9.\-]/', '', $valor);
    return is_numeric($valor) ? (float)$valor : 0;
}
?>

<h2 align="center"><?= $titulo ?></h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th>Código de Siniestro</th>
                <th>Fecha de Registro</th>
                <th>Hora de Registro</th>
                <th>Usuario que Registró</th>
                <th>Glosa</th>
                <th>Importe del Movimiento</th>
                <th>Moneda</th>
                <th>OT/OC Asociado</th>
                <th>Orden de Pago</th>
                <th>Estado del Registro</th>
                <th>Estado del Siniestro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $cod_siniestro = $row['cod_siniestro'];
                $tipo_fuente = $row['tipo_fuente'];

                // Variables por defecto
                $glosa = '';
                $importe = '0';
                $moneda = '';
                $ot_oc_asociado = '';
                $orden_pago = '';
                $estado_registro = '';
                $estado_siniestro = '';

                // Procesamiento según el tipo de fuente
                if ($tipo_fuente == 'LOG') {
                    // Registro de log_comercial
                    $movimiento = $row['movimiento'];
                    $glosa = determinarGlosa($movimiento);
                    $importe = extraerMontoDelMovimiento($movimiento);
                    $moneda = 'DOLARES';
                    $ot_oc_asociado = '';
                    $orden_pago = '';
                    $estado_registro = '';
                    $estado_siniestro = extraerEstadoDelMovimiento($movimiento);
                } elseif ($tipo_fuente == 'OTC') {
                    // Registro de orden_trabajo_compra
                    $cod_trabajo_compra = $row['otc_cod'];
                    $glosa = determinarTipoOT_OC($cod_trabajo_compra);
                    $importe = $row['otc_monto'];
                    $moneda = $row['otc_moneda'];
                    $ot_oc_asociado = $cod_trabajo_compra;
                    $orden_pago = '';
                    $estado_registro = $row['otc_estado'];
                    $estado_siniestro = '';
                } elseif ($tipo_fuente == 'OP') {
                    // Registro de orden_pago
                    $glosa = 'ORDEN DE PAGO';
                    $importe = $row['op_monto'];
                    $moneda = 'BOLIVIANOS';
                    $ot_oc_asociado = '';
                    $orden_pago = $row['op_cod'];
                    $estado_registro = $row['op_estado'];
                    $estado_siniestro = '';
                }

                $importe_numerico = convertirImporteANumero($importe);
            ?>
                <tr>
                    <td><?php echo $cod_siniestro; ?></td>
                    <td><?php echo $row['fecha_registro']; ?></td>
                    <td><?php echo $row['hora_registro']; ?></td>
                    <td><?php echo $row['usuario_registro']; ?></td>
                    <td><?php echo $glosa; ?></td>
                    <td align="right"><?php echo number_format($importe_numerico, 2, '.', ','); ?></td>
                    <td><?php echo $moneda; ?></td>
                    <td><?php echo $ot_oc_asociado; ?></td>
                    <td><?php echo $orden_pago; ?></td>
                    <td><?php echo $estado_registro; ?></td>
                    <td><?php echo $estado_siniestro; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
</tr>

</tbody>
<?php echo $script_tabla; ?>
</table>
</div>