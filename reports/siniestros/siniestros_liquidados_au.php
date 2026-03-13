<?php
$consulta = "SELECT s.num_siniestro, s.fecha_siniestro as f_siniestro, op.f_registro as f_registro_op,s.fecha_registro as f_r_siniestro, s.fecha_denuncia, c.lugar_incidente,
s.distrito, op.ramo, op.cobertura_afectada,s.cobertura_aplicar,s.clase, s.marca, s.placa_sin,s.uso, s.asegurado, s.num_poliza, a.inicio_vigencia, a.final_vigencia,
op.importe_bs, op.num_orden, op.receptor, c.narracion_hecho, op.doc_descargo, op.f_indemnizacion,op.concepto , op.user_reclamo,
c.fecha_incidente,c.fecha_reclamo,s.insperctor, a.cod_cliente as codigo, op.f_registro as op_fecha_registro,
op.importe_bs,op.retencion_bs,op.pago_total_bs,op.num_cliente,op.f_indemnizacion,op.indemnizacion,s.estado
FROM ((unibienes.orden_pago as op INNER JOIN unibienes.siniestros as s on s.num_siniestro=op.num_siniestro)
INNER JOIN unibienes.circun_siniestro as c on c.num_siniestro=s.num_siniestro)
INNER JOIN unibienes.automovil as a on a.nro_poliza=s.num_poliza";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " WHERE op.f_registro like '%$fecha_dia%'";
    $fecha_aux = $fecha_dia;
    $msj_log = "REPORTE SINIESTROS LIQUIDADOS AUTOMOVIL BASE UNIBIENES EL $fecha_dia";
    $titulo = "VISTA PREVIA DE SINISTROS LIQUIDADOS DEL $fecha_dia";
} else {
    $consulta .= " WHERE op.f_registro >= '$fecha_inicio' and op.f_registro <= '$fecha_final'";
    $fecha_aux = $fecha_final;
    $msj_log = " REPORTE SINIESTROS LIQUIDADOS AUTOMOVIL BASE UNIBIENES ENTRE $fecha_inicio Y $fecha_final";
    $titulo = "VISTA PREVIA DE SINISTROS LIQUIDADOS. DESDE $fecha_inicio HASTA $fecha_final";
}

$consulta .= " GROUP BY op.num_orden";
//echo $consulta;


$result = mysqli_query($con, $consulta);

?>
<h2 align="center"> <?= $titulo ?></h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th>Nro. Sinistro</th>
                <th>Fecha Sinistro</th>
                <th>Fecha Registro Siniestro</th>
                <th>Fecha Registro Orden de Pago</th>
                <th>Fecha Denuncia</th>
                <th>Fecha incidente</th>
                <th>Fecha Reclamo</th>
                <th>Departamento Ocurrencia Siniestro</th>
                <th>Sucursal</th>
                <th>Ramo</th>
                <th>Cobertura Afectada (OP)</th>
                <th>Cobertura Aplicar(Siniestro)</th>
                <th>Concepto</th>
                <th>Asegurado</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Clase</th>
                <th>Marca</th>
                <th>Placa</th>
                <th>Uso Vehiculo</th>
                <th>Nro. Poliza</th>
                <th>Tipo de Cartera</th>
                <th>Inicio Vigencia</th>
                <th>Final Vigencia</th>
                <th>Liquidados por Pagar(Bs)</th>
                <th>Nro. Orden Pago</th>
                <th>Beneficiario</th>
                <th>Detalle Siniestro</th>
                <th>Tipo Documento</th>
                <th>Fecha de Liquidacion</th>
                <th>Liquidador</th>
                <th>Inspector</th>
                <th>Retención Bs</th>
                <th>Pago Total Bs</th>
                <th>Estado del Siniestro</th>
                <th>Código del Cliente</th>
                <th>Fecha Indemnización</th>
                <th>Indemnización</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $cod_pol = $row['num_poliza'];
                $sele2 = $con->query("SELECT cod_cliente,tipo_cartera from unibienes.reporte_comercial 
                                     where nro_poliza = '$cod_pol' limit 1");
                $filas2 = $sele2->fetch_assoc();
                $cod_cliente = $filas2['cod_cliente'];
                $tipo_cartera = $filas2['tipo_cartera'];

                $sele22 = $con->query("SELECT telefono_fijo,email from unibienes.clientes 
                                      where cod_cliente = '$cod_cliente' limit 1");
                $filas22 = $sele22->fetch_assoc();
                $telefono_fijo = $filas22['telefono_fijo'];
                $email = $filas22['email'];

            ?>
                <tr>
                    <td><?php echo $row['num_siniestro']; ?></td>
                    <td><?php echo $row['f_siniestro']; ?></td>
                    <td><?php echo $row['f_r_siniestro']; ?></td>
                    <td><?php echo $row['f_registro_op']; ?></td>
                    <td><?php echo $row['fecha_denuncia']; ?></td>
                    <td><?php echo $row['fecha_incidente']; ?></td>
                    <td><?php echo $row['fecha_reclamo']; ?></td>
                    <td><?php echo $row['lugar_incidente']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['cobertura_afectada']; ?></td>
                    <td><?php echo $row['cobertura_aplicar']; ?></td>
                    <td><?php $borrado = quitar_caracter($row['concepto']); ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $telefono_fijo; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $row['clase']; ?></td>
                    <td><?php echo $row['marca']; ?></td>
                    <td><?php echo $row['placa_sin']; ?></td>
                    <td><?php echo $row['uso']; ?></td>
                    <td><?php echo $row['num_poliza']; ?></td>
                    <td><?php echo $tipo_cartera; ?></td>
                    <td><?php echo $row['inicio_vigencia']; ?></td>
                    <td><?php echo $row['final_vigencia']; ?></td>
                    <td><?php echo $row['importe_bs']; ?></td>
                    <td><?php echo $row['num_orden']; ?></td>
                    <td><?php echo $row['receptor']; ?></td>
                    <td><?php echo $row['narracion_hecho']; ?></td>
                    <td><?php echo $row['doc_descargo']; ?></td>
                    <td><?php echo $row['f_indemnizacion']; ?></td>
                    <td><?php echo $row['user_reclamo']; ?></td>
                    <td><?php echo $row['insperctor']; ?></td>
                    <td><?php echo $row['retencion_bs']; ?></td>
                    <td><?php echo $row['pago_total_bs']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['num_cliente']; ?></td>
                    <td><?php echo $row['f_indemnizacion']; ?></td>
                    <td><?php echo $row['indemnizacion']; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>