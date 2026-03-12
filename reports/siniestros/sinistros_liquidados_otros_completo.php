<?php
$consulta = "SELECT op.cod_siniestro, op.f_registro, op.ramo, op.cobertura_afectada, op.asegurado, op.cod_poliza, op.importe_dls, op.importe_bs, op.cod_orden, op.receptor, op.doc_descargo, op.f_indemnizacion, op.indemnizacion, op.usuario, op.ramo, op.retencion_bs, op.pago_total_bs, op.concepto,s.ramo_general, s.estado, s.valor_asegurado, s.tipo_asegurado, s.cod_cliente, s.inicio_v, s.fin_v, s.dep_siniestro,s.cobertura AS cobertura_siniestro,s.sucursal, s.observaciones,s.f_siniestro,s.f_denuncia,s.detalle_siniestro, s.f_registro as fecha_reg, s.inspector, fo.nit_factura, fo.num_factura, fo.fecha_factura, fo.autorizacion_factura, fo.importe_factura
FROM (comercial.orden_pago AS op INNER JOIN comercial.siniestros AS s ON op.cod_siniestro = s.cod_siniestro) INNER JOIN comercial.factura_opago as fo ON op.cod_orden = fo.cod_orden_pago";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " WHERE op.f_registro like '%$fecha_dia%'";
    $fecha_aux = $fecha_dia;
    $msj_log = "REPORTE SINIESTROS LIQUIDADOS OTROS BASE UNISERSOFT EL $fecha_dia";
    $titulo = "VISTA PREVIA DE SINIESTROS LIQUIDADOS OTROS RAMOS (Sisitema Unisersoft). DEL $fecha_dia";
} else {
    $consulta .= " WHERE op.f_registro >= '$fecha_inicio' and op.f_registro <= '$fecha_final'";
    $fecha_aux = $fecha_final;
    $msj_log = "REPORTE SINIESTROS LIQUIDADOS OTROS BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
    $titulo = "VISTA PREVIA DE SINIESTROS LIQUIDADOS OTROS RAMOS (Sisitema Unisersoft). DESDE $fecha_inicio HASTA $fecha_final";
}

//$consulta .= " AND s.ramo_general NOT in ('AUTOMOTORES','AUTOMOTOR') GROUP BY op.cod_orden";
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
                <th>Fecha Registro Orden Pago</th>
                <th>Fecha Registro del Siniestro</th>
                <th>Fecha de Siniestro</th>
                <th>Fecha Denuncia</th>
                <th>Tipo Poliza</th>
                <th>Ramo</th>
                <th>Cobertura</th>
                <th>Asegurado</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Nro. Poliza</th>
                <th>Liquidados por Pagar(Bs)</th>
                <th>Nro. Orden Pago</th>
                <th>Beneficiario</th>
                <th>Tipo Documento</th>
                <th>Fecha Indemnizacion</th>
                <th>Indemnizacion</th>
                <th>Usuario</th>
                <th>Valor Asegurado</th>
                <th>Tipo de Cliente</th>
                <th>Tipo de Cartera</th>
                <th>Código de Cliente</th>
                <th>Inicio de Vigencia</th>
                <th>Final de Vigencia</th>
                <th>Departamento Ocurrencia Siniestro</th>
                <th>Sucursal</th>
                <th>Cobertura Afectada (Siniestro)</th>
                <th>Retención Bs.</th>
                <th>Pago Total Bs.</th>
                <th>Concepto de Pago</th>
                <th>Observaciones</th>
                <th>Detalle de Siniestro</th>
                <th>Estado del Siniestro</th>
                <th>Inspector</th>
                <th>NIT</th>
                <th>Cod. de Autorización</th>
                <th>Número de Factura</th>
                <th>Fecha de Factura</th>
                <th>Importe de Factura</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $cod_pol = $row['cod_poliza'];
                $cod_cliente = $row['cod_cliente'];
                $sele22 = $con->query("SELECT telefono_fijo,email from comercial.clientes where cod_cliente = '$cod_cliente' limit 1");
                $filas22 = $sele22->fetch_assoc();
                $telefono_fijo = $filas22['telefono_fijo'];
                $email = $filas22['email'];

                $query_1 = "SELECT tipo_cartera, subtipo_cartera FROM comercial.pol_reporte_comercial WHERE cod_poliza='$cod_pol'";
                $sql1 = $con->query($query_1);
                $f1 = $sql1->fetch_assoc();
                $sector = $f1['tipo_cartera'];
                $subtipo_cartera = $f1['subtipo_cartera'];
            ?>
                <tr>
                    <td><?php echo $row['cod_siniestro']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['fecha_reg']; ?></td>
                    <td><?php echo $row['f_siniestro']; ?></td>
                    <td><?php echo $row['f_denuncia']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['ramo_general']; ?></td>
                    <td><?php echo $row['cobertura_afectada']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $telefono_fijo; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['importe_bs']; ?></td>
                    <td><?php echo $row['cod_orden']; ?></td>
                    <td><?php echo $row['receptor']; ?></td>
                    <td><?php echo $row['doc_descargo']; ?></td>
                    <td><?php echo $row['f_indemnizacion']; ?></td>
                    <td><?php echo $row['indemnizacion']; ?></td>
                    <td><?php echo $row['usuario']; ?></td>
                    <td><?php echo number_format($row['valor_asegurado'], 2); ?></td>
                    <td><?php echo $sector; ?></td>
                    <td><?php echo $subtipo_cartera; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['inicio_v']; ?></td>
                    <td><?php echo $row['fin_v']; ?></td>
                    <td><?php echo $row['dep_siniestro']; ?></td>
                    <td><?php echo $row['sucursal']; ?></td>
                    <td><?php echo $row['cobertura_siniestro']; ?></td>
                    <td><?php echo $row['retencion_bs']; ?></td>
                    <td><?php echo number_format($row['pago_total_bs'], 2); ?></td>
                    <td><?php echo $row['concepto']; ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                    <td><?php echo $row['detalle_siniestro']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['inspector']; ?></td>
                    <td><?php echo $row['nit_factura']; ?></td>
                    <td><?php echo $row['autorizacion_factura']; ?></td>
                    <td><?php echo $row['num_factura']; ?></td>
                    <td><?php echo $row['fecha_factura']; ?></td>
                    <td><?php echo $row['importe_factura']; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>