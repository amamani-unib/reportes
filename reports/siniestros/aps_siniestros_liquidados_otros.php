<?php
$msj_log = "REPORTE SINIESTROS LIQUIDADOS OTROS ACTUALIZADO";
$consulta = "SELECT op.cod_siniestro, op.f_registro, op.ramo, op.cobertura_afectada, op.asegurado,
op.cod_poliza, op.importe_dls, op.importe_bs, op.cod_orden, op.receptor,op.nit_receptor , op.doc_descargo, op.f_indemnizacion,
op.indemnizacion, op.usuario, op.ramo, op.retencion_bs, op.pago_total_bs, op.concepto,s.ramo_general,
s.estado, s.valor_asegurado, s.tipo_asegurado, s.cod_cliente, s.inicio_v, s.fin_v, s.dep_siniestro,s.cobertura AS cobertura_siniestro,s.sucursal,
s.observaciones,s.f_siniestro,s.f_denuncia,s.detalle_siniestro, s.f_registro as fecha_reg, s.inspector, op.f_cambio, op.cambio_usd, s.canal,op.cambio_usd
FROM 20260903_comercial.orden_pago AS op INNER JOIN 20260903_comercial.siniestros AS s ON op.cod_siniestro = s.cod_siniestro";

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
//$consulta .= " AND s.cod_siniestro ='SIAUCBS00002709'";
//echo $consulta;

$result = mysqli_query($con, $consulta);
?>
<h2 align="center"> <?= $titulo ?> </h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th>Código Entidad</th>
                <th>Nro. Sinistro</th>
                <th>Fecha Registro Orden Pago</th>
                <th>Fecha Registro del Siniestro</th>
                <th>Fecha de Siniestro</th>
                <th>Fecha Denuncia</th>
                <th>Tipo Poliza</th>
                <th>Código modalidad</th>
                <th>Código ramo</th>
                <th>Ramo</th>
                <th>Cobertura</th>
                <th>Tomador</th>
                <th>Asegurado</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Nro. Poliza</th>
                <th>Liquidados por Pagar(Bs)</th>
                <th>Nro. Orden Pago</th>
                <th>Beneficiario</th>
                <th>NIT Beneficiario</th>
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
                <th>Código Oficina</th>
                <th>Cobertura Afectada (Siniestro)</th>
                <th>Retención Bs.</th>
                <th>Pago Total Bs.</th>
                <th>Concepto de Pago</th>
                <th>Observaciones</th>
                <th>Detalle de Siniestro</th>
                <th>Estado del Siniestro</th>
                <th>Inspector</th>
                <th>Canal de Denuncia</th>
                <th>Tipo Cambio</th>
                <th>fecha Tipo Cambio</th>
                <th>Sector</th>
                <th>Código Sector</th>
                <th>Moneda (Orden de pago)</th>
                <th>Codigo de moneda</th>
            </tr>
        </thead>
        <tbody>
            <?php

            while ($row = mysqli_fetch_assoc($result)) {
                $cod_pol = $row['cod_poliza'];
                $cod_cliente = $row['cod_cliente'];
                $sele22 = $con->query("SELECT telefono_fijo,email from 20260903_comercial.clientes where cod_cliente = '$cod_cliente' limit 1");
                $filas22 = $sele22->fetch_assoc();
                $telefono_fijo = $filas22['telefono_fijo'];
                $email = $filas22['email'];

                $query_1 = "SELECT tipo_cartera, subtipo_cartera, tomador,cia FROM 20260903_comercial.pol_reporte_comercial WHERE cod_poliza='$cod_pol'";
                $sql1 = $con->query($query_1);
                $f1 = $sql1->fetch_assoc();
                $sector = $f1['tipo_cartera'];
                $tomador = $f1['tomador'];
                $cia = $f1['cia'];
                $subtipo_cartera = $f1['subtipo_cartera'];
                $cod_poliza = $row['cod_poliza'];
                if ($cia == '') {
                    $query_1 = "SELECT tipo_cartera, subtipo_cartera, tomador FROM unibienes.reporte_comercial WHERE nro_poliza='$cod_pol'";
                    $sql1 = $con->query($query_1);
                    $f1 = $sql1->fetch_assoc();
                    $sector = $f1['tipo_cartera'];
                    $tomador = $f1['tomador'];
                    $cia = 116;
                }
                if ($sector == 'ESTATAL') {
                    $cod_sector = "E";
                } else {
                    $cod_sector = "P";
                }
                if ($tomador == '') {
                    $tomador = "NO DEFINIDO";
                    $sector = "NO DEFINIDO";
                    $subtipo_cartera = "NO DEFINIDO";
                    $cod_sector = "NO DEFINIDO";
                }


                $sigla_ramo = substr($cod_poliza, 0, -10);

                if ($sigla_ramo !== '') {
                    $sele2 = $con->query("SELECT modalidad,cod_ramo from 20260903_comercial.x_ramo where sigla = '$sigla_ramo' order by id_ramo desc limit 1");
                    $filas2 = $sele2->fetch_assoc();
                    $modalidad = $filas2['modalidad'];
                    $cod_ramo = $filas2['cod_ramo'];
                } else {
                    $modalidad = "NO DEFINIDO";
                    $cod_ramo = "NO DEFINIDO";
                }
                $sucursal = $row['sucursal'];
                switch ($sucursal) {
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
            ?>
                <tr>
                    <td><?php echo $cia; ?></td>
                    <td><?php echo $row['cod_siniestro']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['fecha_reg']; ?></td>
                    <td><?php echo $row['f_siniestro']; ?></td>
                    <td><?php echo $row['f_denuncia']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $modalidad; ?></td>
                    <td><?php echo $cod_ramo; ?></td>
                    <td><?php echo $row['ramo_general']; ?></td>
                    <td><?php echo $row['cobertura_afectada']; ?></td>
                    <td><?php echo $tomador ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $telefono_fijo; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['importe_bs']; ?></td>
                    <td><?php echo $row['cod_orden']; ?></td>
                    <td><?php echo $row['receptor']; ?></td>
                    <td><?php echo $row['nit_receptor']; ?></td>
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
                    <td><?php echo $sucursal; ?></td>
                    <td><?php echo $cod_oficina; ?></td>
                    <td><?php echo $row['cobertura_siniestro']; ?></td>
                    <td><?php echo $row['retencion_bs']; ?></td>
                    <td><?php echo number_format($row['pago_total_bs'], 2); ?></td>
                    <td><?php echo $row['concepto']; ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                    <td><?php echo $row['detalle_siniestro']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['inspector']; ?></td>
                    <td><?php echo $row['canal']; ?></td>
                    <td><?php echo number_format((float)$row['cambio_usd'], 2, '.', ''); ?></td>
                    <td><?php echo $row['f_cambio']; ?></td>
                    <td><?php echo $sector; ?></td>
                    <td><?php echo $cod_sector; ?></td>
                    <td><?php echo "Bolivianos"; ?></td>
                    <td><?php echo "1"; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>

    </table>
</div>