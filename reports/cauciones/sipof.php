<?php
$tabla = array(
    "ramo_adm_activo_ritex",
    "ramo_adm_reexportacion_mismo_estado",
    "ramo_agencias_agentes_aduana",
    "ramo_buena_ejecucion_transporte",
    "ramo_consolidadores_desconsoliladores",
    "ramo_contrato_obra",
    "ramo_contrato_obra_privada",
    "ramo_contrato_servicios",
    "ramo_contrato_servicios_privado",
    "ramo_contrato_suministros",
    "ramo_contrato_suministros_privado",
    "ramo_correcta_inversion_anticipos",
    "ramo_correcta_inversion_anticipos_privada",
    "ramo_deposito_transitorio_mercancias",
    "ramo_ejecucion_obra",
    "ramo_emprea_servicio_expreso",
    "ramo_funcionamiento_aquinaria_equipo",
    "ramo_obligaciones_adminstradoras_fondos_inversiones",
    "ramo_obligaciones_legales_telecomunicaciones",
    "ramo_reexpediciones_zona_franca",
    "ramo_seriedad_propuesta",
    "ramo_seriedad_propuesta_privada",
    "ramo_transportador_internacional_carga"
);
$campo = array(
    "cantidad_ar",
    "cantidad_rme",
    "cantidad_a",
    "cantidad_bet",
    "cantidad_cd",
    "cantidad_co",
    "cantidad_co",
    "cantidad_cse",
    "cantidad_cs",
    "cantidad_cs",
    "cantidad_cu",
    "cantidad_cia",
    "cantidad_cia",
    "cantidad_dtm",
    "cantidad_eo",
    "cantidad_ese",
    "cantidad_fme",
    "cantidad_oafi",
    "cantidad_olt",
    "cantidad_rzf",
    "cantidad_sp",
    "cantidad_sp",
    "cantidad_tic"
);
$consulta = "";
$i = 0;
while ($i < 25) {
    $consulta .= "SELECT r.razon_social, r.nit, g.beneficiario,g.poliza ,g.vigencia_inicial, g.vigencia_final, 
    a.lugar_emision,a.fecha_emision,a.suma_garantizada,g.moneda,a.prima_total,g.prima_neta, g.intermediario,g.comision,g.ramo,g.cod_cliente,g.objeto,g.tipo_operacion
    FROM (cauciones.registra_cauciones as r inner join cauciones.ramo_datos_generales as g on g.cod_cliente=r.cod_cliente) 
    inner join cauciones.$tabla[$i] as a on a.$campo[$i] = g.cantidad WHERE g.estado='EMITIDO' ";
    if (!isset($_POST['cb_lapso'])) {
        $consulta .= " and a.fecha_registro like '%$fecha_dia%'";
        $fecha_aux = $fecha_dia;
        $msj_log = "REPORTE CAUCIONES $fecha_dia";
        $titulo = "VISTA PREVIA DE REPORTE CAUCIONES. DEL $fecha_dia";
    } else {
        $consulta .= " and a.fecha_registro >= '$fecha_inicio' and a.fecha_registro <= '$fecha_final'";
        $fecha_aux = $fecha_final;
        $msj_log = "REPORTE CAUCIONES ENTRE $fecha_inicio Y $fecha_final";
        $titulo = "VISTA PREVIA DE REPORTE CAUCIONES. DESDE $fecha_inicio HASTA $fecha_final";
    }
    $consulta .= " ORDER BY a.fecha_registro ASC;";
    $i++;
}

date_default_timezone_set('America/La_Paz');
$hoy = date('Y-m-d', time());
//echo $consulta;

?>
<h2 align="center"><?= $titulo ?> </h2>
<br>
<div id="datos_reportes" class="table-responsive table">
    <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
        <thead>
            <tr class='text-center'>
                <!-- <th>NFormulario</th> -->
                <th>Fecha Reporte</th>
                <th>Sucursal</th>
                <th>Fecha Emision</th>
                <th>Número Ramo</th>
                <th>Tipo de Póliza</th>
                <th>Nro Póliza</th>
                <th>Estado Póliza</th>
                <th>Cod. Cliente</th>
                <th>Nombre Afianzado</th>
                <th>Tipo Documento</th>
                <th>ID Afianzado</th>
                <th>Lugar de Expedición</th>
                <th>Tipo Beneficiario</th>
                <th>Nombre Beneficiario</th>
                <th>Nombre Clausula</th>
                <th>Objeto</th>
                <th>Fecha Inicial Vigencia</th>
                <th>Fecha Final Vigencia</th>
                <th>Moneda</th>
                <th>Monto Caucionado</th>
                <th>Monto Prima Comercial </th>
                <th>Monto Prima Neta</th>
                <th>Tipo de Garantia</th>
                <th>Tipo Intermediario</th>
                <th>Nombre Intermediario</th>
                <th>Porcentaje Comisión</th>
                <th>Numero Siniestrro</th>
                <th>Fecha de Siniestro</th>
                <!-- <th>Cod Cliente</th> -->
                <th>Monto Reserva</th>
                <th>Monto Siniestro Pagado</th>
                <th>Estado Siniestro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($con->multi_query($consulta)) {
                // echo $consulta;
                do {
                    if ($resultado = $con->store_result()) {

                        while ($fila = $resultado->fetch_row()) {
                            $fecha_hoy = $hoy;
                            $fecha_vi = $fila[4];
                            $fecha_vf = $fila[5];
                            $fecha_emision = $fila[7];
                            $codigo_sipof = "116-" . $fila[3] . "-N";
                            switch ($fila[9]) {
                                case 'UFV':
                                    $cod_moneda = "4";
                                    break;
                                case 'BS':
                                    $cod_moneda = "1";
                                    break;
                                case 'SUS':
                                    $cod_moneda = "2";
                                    break;
                                case 'EU':
                                    $cod_moneda = "3";
                                    break;
                            }
                            $aux = $fila[12];
                            if ($aux == 'CARTERA DIRECTA') {
                                $cod_inter = "D";
                            } else {
                                $connect = mysqli_connect('localhost', 'root', '', 'comercial');
                                $q = $connect->query("SELECT tipo FROM comercial.intermediarios where intermediario ='$aux';");
                                $f = $q->fetch_assoc();
                                $tipo_in = $f["tipo"];
                                switch ($tipo_in) {
                                    case 'AGENTE':
                                        $cod_inter = "A";
                                        break;
                                    case 'BROKER':
                                        $cod_inter = "B";
                                        break;
                                }
                                $connect->close();
                            }
                            $distrito = $fila[6];
                            switch ($distrito) {
                                case 'LA PAZ':
                                    $pref = 'LP';
                                    break;
                                case 'SANTA CRUZ':
                                    $pref = 'SC';
                                    break;
                                case 'COCHABAMBA':
                                    $pref = 'CB';
                                    break;
                                case 'ORURO':
                                    $pref = 'OR';
                                    break;
                                case 'POTOSI':
                                    $pref = 'PT';
                                    break;
                                case 'CHUQUISACA':
                                    $pref = 'CH';
                                    break;
                                case 'TARIJA':
                                    $pref = 'TA';
                                    break;
                                case 'BENI':
                                    $pref = 'BE';
                                    break;
                                case 'PANDO':
                                    $pref = 'PA';
                                    break;
                            }
            ?><tr>
                                <!-- <td> <?php echo $codigo_sipof ?></td> -->
                                <td> <?php echo $fecha_hoy ?></td>
                                <td> <?php echo $pref ?></td>
                                <td> <?php echo $fecha_emision ?></td>
                                <td> <?php echo "29" ?></td>
                                <td> <?php echo $fila[14] //ramo
                                        ?></td>
                                <td> <?php echo $fila[3] //cod poliza
                                        ?></td>
                                <td> <?php echo "N" ?></td>
                                <td> <?php echo $fila[15] //cod_cliente
                                        ?></td>
                                <td> <?php echo $fila[0] ?></td>
                                <td> <?php echo "NIT" ?></td>
                                <td> <?php echo $fila[1] ?></td>
                                <td> <?php echo "" ?></td>
                                <td> <?php echo "E" ?></td>
                                <td> <?php echo $fila[2] //beneficiario
                                        ?></td>
                                <td> <?php echo "B" ?></td>
                                <td> <?php echo $fila[16] //objeto
                                        ?></td>
                                <td> <?php echo $fecha_vi ?></td>
                                <td> <?php echo $fecha_vf ?></td>
                                <td> <?php echo $cod_moneda ?></td>
                                <td> <?php echo number_format($fila[8], 2) ?></td>
                                <td> <?php echo number_format($fila[10], 2) ?></td>
                                <td> <?php echo number_format($fila[11], 2) ?></td>
                                <td> <?php echo $fila[17] ?></td>
                                <td> <?php echo $cod_inter ?></td>
                                <td> <?php echo $fila[12] ?></td>
                                <td> <?php echo number_format($fila[13], 2) ?></td>
                                <td> <?php echo "" ?></td>
                                <td> <?php echo "" ?></td>
                                <td> <?php echo "" ?></td>
                                <td> <?php echo "" ?></td>
                                <td> <?php echo "" ?></td>
                            </tr>
            <?php
                        }
                        $resultado->free();
                    }
                } while ($con->next_result());
            }
            ?>
        </tbody>
        <?php echo $script_tabla; ?>
    </table>
</div>