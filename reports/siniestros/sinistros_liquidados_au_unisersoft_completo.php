<?php
$consulta = "SELECT s.cod_siniestro, s.f_siniestro as f_siniestro, op.f_registro as f_registro_op, s.f_registro as f_r_siniestro, s.f_denuncia as fecha_denuncia, s.lugar_siniestro, s.distrito, s.ramo_general as ramo, op.cobertura_afectada, s.cobertura as cobertura_aplicar, s.asegurado, s.cod_poliza as num_poliza, s.inicio_v as inicio_vigencia, s.fin_v as final_vigencia, op.importe_bs, op.cod_orden as num_orden, op.receptor, s.detalle_siniestro as narracion, op.doc_descargo, op.f_indemnizacion, op.concepto, op.usuario as user_reclamo,s.inspector, s.cod_cliente as codigo, op.importe_bs,op.retencion_bs,op.pago_total_bs,op.indemnizacion,s.estado, s.id_item, fo.nit_factura, fo.num_factura, fo.fecha_factura, fo.autorizacion_factura, fo.importe_factura
FROM (comercial.orden_pago as op INNER JOIN comercial.siniestros as s on s.cod_siniestro=op.cod_siniestro) INNER JOIN comercial.factura_opago as fo on fo.cod_orden_pago = op.cod_orden";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " WHERE op.f_registro like '%$fecha_dia%'";
    $fecha_aux = $fecha_dia;
    $msj_log = "REPORTE SINIESTROS LIQUIDADOS AUTOMOTORES BASE UNISERSOFT EL $fecha_dia";
    $titulo = "VISTA PREVIA DE SINISTROS LIQUIDADOS AUTOMOTORES SISTEMA UNISERSOFT DEL $fecha_dia";
} else {
    $consulta .= " WHERE op.f_registro >= '$fecha_inicio' and op.f_registro <= '$fecha_final'";
    $fecha_aux = $fecha_final;
    $msj_log = "REPORTE SINIESTROS LIQUIDADOS AUTOMOTORES BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
    $titulo = "VISTA PREVIA DE SINISTROS LIQUIDADOS AUTOMOTORES SISTEMA UNISERSOFT DESDE $fecha_inicio HASTA $fecha_final";
}

$consulta .= " AND s.ramo_general in ('AUTOMOTORES','AUTOMOTOR') GROUP BY op.cod_orden";
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
                <th>Departamento Ocurrencia Siniestro</th>
                <th>Sucursal</th>
                <th>Ramo</th>
                <th>Cobertura Afectada (OP)</th>
                <th>Cobertura Aplicar(Siniestro)</th>
                <th>Concepto</th>
                <th>Asegurado</th>
                <th>Sector</th>
                <th>Tipo de Cartera</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Clase</th>
                <th>Marca</th>
                <th>Placa</th>
                <th>Uso Vehiculo</th>
                <th>Nro. Poliza</th>
                <th>Inicio Vigencia</th>
                <th>Final Vigencia</th>
                <th>Liquidados por Pagar(Bs)</th>
                <th>Nro. Orden Pago</th>
                <th>Beneficiario</th>
                <th>Detalle Siniestro</th>
                <th>Tipo Documento</th>
                <th>Liquidador</th>
                <th>Inspector</th>
                <th>Retención Bs</th>
                <th>Pago Total Bs</th>
                <th>Estado del Siniestro</th>
                <th>Código del Cliente</th>
                <th>Fecha Indemnización</th>
                <th>Indemnización</th>
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
                $cod_pol = $row['num_poliza'];
                $cod_cliente = $row['codigo'];
                $id_item = $row['id_item'];
                $f_reg = $row['f_r_siniestro'];
                $sele22 = $con->query("SELECT telefono_fijo,email from comercial.clientes 
                                      where cod_cliente = '$cod_cliente' limit 1");
                $filas22 = $sele22->fetch_assoc();
                $telefono_fijo = $filas22['telefono_fijo'];
                $email = $filas22['email'];

                $query_1 = "SELECT `14`,`15`,`13`,`21` FROM comercial.items WHERE id_registro='$id_item'";
                $sql1 = $con->query($query_1);
                $f1 = $sql1->fetch_assoc();
                $clase = $f1['14'];
                $marca = $f1['15'];
                $placa = $f1['13'];
                $uso = $f1['21'];

                $query_1 = "SELECT tipo_cartera, subtipo_cartera FROM comercial.pol_reporte_comercial WHERE cod_poliza='$cod_pol'";
                $sql1 = $con->query($query_1);
                $f1 = $sql1->fetch_assoc();
                $sector = $f1['tipo_cartera'];
                $subtipo_cartera = $f1['subtipo_cartera'];

                if ($placa == '') {
                    $query_1 = "SELECT certificado FROM comercial.siniestro_detalles WHERE f_registro='$f_reg'";
                    $sql1 = $con->query($query_1);
                    $f1 = $sql1->fetch_assoc();
                    $certificado = $f1['certificado'];
                    if ($cod_pol == 'AULP00003314') {
                        $query_1 = "SELECT clase,marca,placa FROM unibienes.certi_bdp WHERE nro_poliza='$cod_pol' and nro_certificado='$certificado'";
                        $sql1 = $con->query($query_1);
                        $f1 = $sql1->fetch_assoc();
                        $clase = $f1['clase'];
                        $marca = $f1['marca'];
                        $placa = $f1['placa'];
                        $uso = "ND";
                    } else {
                        if (substr($certificado, 0, 2) == 'CE') {
                            $query_1 = "SELECT `14`,`15`,`13`,`21` FROM comercial.items WHERE cod_poliza='$cod_pol' and `0`='$certificado'";
                            $sql1 = $con->query($query_1);
                            $f1 = $sql1->fetch_assoc();
                            $clase = $f1['14'];
                            $marca = $f1['15'];
                            $placa = $f1['13'];
                            $uso = $f1['21'];
                        } else {
                            $query_1 = "SELECT clase,marca,placa FROM unibienes.certi_bunion WHERE nro_poliza='$cod_pol' and num_cert='$certificado'";
                            $sql1 = $con->query($query_1);
                            $f1 = $sql1->fetch_assoc();
                            $clase = $f1['clase'];
                            $marca = $f1['marca'];
                            $placa = $f1['placa'];
                            $uso = "ND";
                        }
                    }
                }
            ?>
                <tr>
                    <td><?php echo $row['cod_siniestro']; ?></td>
                    <td><?php echo $row['f_siniestro']; ?></td>
                    <td><?php echo $row['f_r_siniestro']; ?></td>
                    <td><?php echo $row['f_registro_op']; ?></td>
                    <td><?php echo $row['fecha_denuncia']; ?></td>
                    <td><?php echo $row['lugar_siniestro']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['cobertura_afectada']; ?></td>
                    <td><?php echo $row['cobertura_aplicar']; ?></td>
                    <td><?php $borrado = quitar_caracter($row['concepto']); ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $sector; ?></td>
                    <td><?php echo $subtipo_cartera; ?></td>
                    <td><?php echo $telefono_fijo; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $clase; ?></td>
                    <td><?php echo $marca; ?></td>
                    <td><?php echo $placa; ?></td>
                    <td><?php echo $uso; ?></td>
                    <td><?php echo $row['num_poliza']; ?></td>
                    <td><?php echo $row['inicio_vigencia']; ?></td>
                    <td><?php echo $row['final_vigencia']; ?></td>
                    <td><?php echo $row['importe_bs']; ?></td>
                    <td><?php echo $row['num_orden']; ?></td>
                    <td><?php echo $row['receptor']; ?></td>
                    <td><?php echo $row['narracion']; ?></td>
                    <td><?php echo $row['doc_descargo']; ?></td>
                    <td><?php echo $row['user_reclamo']; ?></td>
                    <td><?php echo $row['inspector']; ?></td>
                    <td><?php echo $row['retencion_bs']; ?></td>
                    <td><?php echo $row['pago_total_bs']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><?php echo $row['f_indemnizacion']; ?></td>
                    <td><?php echo $row['indemnizacion']; ?></td>
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