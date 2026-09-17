<?php
$msj_log = "SINIESTROS GENERALES APS";
$consulta = "SELECT s.*, c.telefono_fijo, c.email, s.estado as estado_siniestro
    FROM (20260903_comercial.siniestros as s inner join 20260903_comercial.clientes as c on c.cod_cliente = s.cod_cliente)";

if (!isset($_POST['cb_lapso'])) {
    $consulta .= " WHERE s.f_registro like '%$fecha_dia%'";
    $fecha_aux = $fecha_dia;
    $msj_log = "REPORTE DE APS SINIESTROS GENERALES - BASE UNISERSOFT EL $fecha_dia";
    $titulo = "VISTA PREVIA DE SINIESTROS GENERALES (Base Unisersoft). DEL $fecha_dia";
} else {
    $consulta .= " WHERE s.f_registro >= '$fecha_inicio' and s.f_registro <= '$fecha_final'";
    $fecha_aux = $fecha_final;
    $msj_log = " REPORTE DE APS SINIESTROS GENERALES - BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
    $titulo = "VISTA PREVIA DE SINIESTROS GENERALES (Base Unisersoft). ENTRE $fecha_inicio Y $fecha_final";
}

$consulta .= " and s.cod_poliza <> 'CORTE'";

$result = mysqli_query($con, $consulta);
//echo $consulta;
?>
<h2 align="center"><?= $titulo ?> </h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%'
        id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <th>Nro.</th>
                <th>Código Entidad</th>
                <th>Cod. Siniestro</th>
                <th>Cod. Póliza</th>
                <th>Cod. Cliente</th>
                <th>Sucursal</th>
                <th>Código Oficina</th>
                <th>Departamento Siniestro</th>
                <th>Tipo Póliza</th>
                <th>Código modalidad </th>
                <th>Código ramo</th>
                <th>Ramo</th>
                <th>Cobertura</th>
                <th>Tomador</th>
                <th>Asegurado</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Tipo de Asegurado</th>
                <th>Tipo de Cartera</th>
                <th>Fecha de Siniestro</th>
                <th>Fecha de Denuncia</th>
                <th>Detalle del Siniestro</th>
                <th>Inicio de Vigencia</th>
                <th>Fin de Vigencia</th>
                <th>Valor Asegurado</th>
                <th>Monto de Reserva</th>
                <th>Observaciones</th>
                <th>Inspector</th>
                <th>Fecha de Registro</th>
                <th>Estado del siniestro</th>
                <th>Clase</th>
                <th>Marca</th>
                <th>Placa</th>
                <th>Uso Vehículo</th>
                <th>Canal de Denuncia</th>
                <th>Sector</th>
                <th>Código de Sector</th>
                <th>Moneda (Monto de reserva)</th>
                <th>Codigo de moneda</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $estado = $row['estado_siniestro'];
                $cod_poliza = $row['cod_poliza'];
                $id_item = $row['id_item'];
                $query_1 = "SELECT subtipo_cartera,tipo_cartera,tomador,cia FROM 20260903_comercial.pol_reporte_comercial WHERE cod_poliza='$cod_poliza'";
                $sql1 = $con->query($query_1);
                $f1 = $sql1->fetch_assoc();
                $sector = $f1['tipo_cartera'];
                $tomador = $f1['tomador'];
                $cia = $f1['cia'];
                if ($sector == 'ESTATAL') {
                    $cod_sector = "E";
                } else {
                    $cod_sector = "P";
                }
                $subtipo_cartera = $f1['subtipo_cartera'];

                $query_1 = "SELECT `14`,`15`,`13`,`21` FROM 20260903_comercial.items WHERE id_registro='$id_item' AND `0` LIKE 'CE%'";
                $sql1 = $con->query($query_1);
                $f1 = $sql1->fetch_assoc();
                $clase = $f1['14'];
                $marca = $f1['15'];
                $placa = $f1['13'];
                $uso = $f1['21'];

                $detalle_siniestros = $row['detalle_siniestro'];
                $cod_siniestro = $row['cod_siniestro'];

                if ($detalle_siniestros == '') {
                    $query_narracion = "SELECT narracion FROM 20260903_comercial.siniestro_detalles WHERE cod_siniestro='$cod_siniestro'";
                    $sql_narracion = $con->query($query_narracion);
                    $f2 = $sql_narracion->fetch_assoc();
                    $detalle_siniestros = $f2['narracion'];
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
                $cod_poliza = $row['cod_poliza'];
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

            ?>
                <tr>
                    <td><?php echo $row['id_sin']; ?></td>
                    <td><?php echo $cia; ?></td>
                    <td><?php echo $row['cod_siniestro']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $sucursal ?></td>
                    <td><?php echo $cod_oficina ?></td>
                    <td><?php echo $row['dep_siniestro']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $modalidad; ?></td>
                    <td><?php echo $cod_ramo; ?></td>
                    <td><?php echo $row['ramo_general']; ?></td>
                    <td><?php echo $row['cobertura']; ?></td>
                    <td><?php echo $tomador; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $row['telefono_fijo']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $sector; ?></td>
                    <td><?php echo $subtipo_cartera; ?></td>
                    <td><?php echo $row['f_siniestro']; ?></td>
                    <td><?php echo $row['f_denuncia']; ?></td>
                    <td><?php echo $detalle_siniestros ?></td>
                    <td><?php echo $row['inicio_v']; ?></td>
                    <td><?php echo $row['fin_v']; ?></td>
                    <td><?php echo round($row['valor_asegurado'], 2); ?></td>
                    <td><?php echo round($row['monto_reserva'], 2); ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                    <td><?php echo $row['inspector']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $estado; ?></td>
                    <td><?php echo $clase; ?></td>
                    <td><?php echo $marca; ?></td>
                    <td><?php echo $placa; ?></td>
                    <td><?php echo $uso; ?></td>
                    <td><?php echo $row['canal']; ?></td>
                    <td><?php echo $sector; ?></td>
                    <td><?php echo $cod_sector; ?></td>
                    <td><?php echo "Dólares"; ?></td>
                    <td><?php echo "2"; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>