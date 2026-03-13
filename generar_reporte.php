<?php
session_start();
include "head.php";
include "sidebar.php";
include "config/config.php";
$usuario = $_SESSION["usuario"];
$distrito = $_SESSION["distrito"];
$cargo = $_SESSION["cargo"];
$nombre = $_SESSION["nombre"];
$nombre = $_SESSION["apellido"];
date_default_timezone_set('America/La_Paz');
$hoy = date('Y-m-d h:i:s', time());
$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$separador = "T";
/******************POST***************/
if (!isset($_POST['cb_lapso'])) {
  $fecha_dia = $_POST['f_dia'];
} else {
  $final_inicio = explode($separador, $_POST['f_inicio_r']);
  $fecha_inicio = $final_inicio[0] . " " . $final_inicio[1];

  $final_fin = explode($separador, $_POST['f_final_r']);
  $fecha_final = $final_fin[0] . " " . $final_fin[1];
}

/* $fecha_inicio = $_POST['f_inicio_r'];
$fecha_final = $_POST['f_final_r']; */

$repo = $_POST['repo'];

function quitar_caracter($x)
{
  $resultado = str_replace(array("<p>", "</p>"), '', $x);
  echo $resultado;
}

$script_tabla = "<script>
  $(document).ready(function () {
      $('#tabla_generar').DataTable({
        dom: 'Blftip',
        buttons:{
            dom: {
                button: {
                    className: 'btn'
                }
            },
            buttons: [
              {
                  extend: 'excel',
                  text:'Exportar a Excel',
                  className:'btn btn-success',
                  excelStyles: {                      // Add an excelStyles definition
                      cells: '2',                     // adonde se aplicaran los estilos (fila 2)
                      style: {                        // The style block
                          font: {                     // Style the font
                              name: 'Arial',          // Font name
                              size: '11',             // Font size
                              color: 'FFFFFF',        // Font Color
                              b: true,               // negrita SI
                          },
                          fill: {                     // Estilo de relleno (background)
                              pattern: {              // tipo de rellero (pattern or gradient)
                                  color: 'blue',    // color de fondo de la fila
                              }
                          }
                      }
                  },
              }
            ]            
        },
        language:{
            'sProcessing':     'Procesando...',
            'sLengthMenu':     'Mostrar _MENU_ registros',
            'sZeroRecords':    'No se encontraron resultados',
            'sEmptyTable':     'Ningún dato disponible en esta tabla',
            'sInfo':           'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
            'sInfoEmpty':      'Mostrando registros del 0 al 0 de un total de 0 registros',
            'sInfoFiltered':   '(filtrado de un total de _MAX_ registros)',
            'sInfoPostFix':    '',
            'sSearch':         'Buscar:',
            'sUrl':            '',
            'sInfoThousands':  ',',
            'sLoadingRecords': 'Cargando...',
            'oPaginate': {
                'sFirst':    'Primero',
                'sLast':     'Último',
                'sNext':     'Siguiente',
                'sPrevious': 'Anterior'
            },
            'oAria': {
                'sSortAscending':  ': Activar para ordenar la columna de manera ascendente',
                'sSortDescending': ': Activar para ordenar la columna de manera descendente'
            }
        }                      
      });
  });
</script>";

?>
<div class="right_col" role="main">
  <div class="">

    <div class="container">
      <?php
      switch ($repo) {
        case 'reporte_trabajo_compra':

          $consulta = "SELECT tc.usuario, tc.cod_siniestro, tc.cod_poliza, tc.modelo as tipo, tc.cod_trabajo_compra as codigo, tc.asegurado, tc.total as sum_monto, num_reparaciones as cantidad_items, tcr.concepto , tcr.descripcion, tcr.monto as precio, tc.moneda, tcr.f_registro, tcr.cantidad
                      FROM  trabajo_compra as tc INNER JOIN trabajo_compra_reparaciones AS tcr ON tc.cod_trabajo_compra = tcr.cod_trabajo_compra";
          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE tc.f_registro like '%$fecha_dia%' and tc.estado <> 'ELIMINADO'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE ORDENES TRABAJO COMPRA BASE UNISERSOFT EL $fecha_dia";
            $titulo = "VISTA PREVIA DE REPORTE ORDENES DE TRABAJO - COMPRA SISTEMA UNISERSOFT. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE tc.f_registro >= '$fecha_inicio' and tc.f_registro <= '$fecha_final' and tc.estado <> 'ELIMINADO'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE ORDENES TRABAJO COMPRA BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE REPORTE ORDENES DE TRABAJO - COMPRA SISTEMA UNISERSOFT. DESDE $fecha_inicio HASTA $fecha_final";
          }
          $consulta .= " ORDER BY tc.cod_trabajo_compra ASC";

          // echo $consulta;
          $result = mysqli_query($con, $consulta);
      ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>FECHA DE REGISTRO</th>
                  <th>INSPECTOR</th>
                  <th>Nro. DE SINIESTRO</th>
                  <th>NRO. DE PÓLIZA</th>
                  <th>TIPO</th>
                  <th>CÓDIGO</th>
                  <th>ASEGURADO</th>
                  <th>TOTAL BS</th>
                  <th>TOTAL USD</th>
                  <th>CANTIDAD DE ITEMS</th>
                  <th>DESCRIPCIÓN</th>
                  <th>CONCEPTO</th>
                  <th>PRECIO</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                  if ($row['moneda'] == 'DOLARES') {
                    $total_dolares = $row['sum_monto'];
                    $total_bolivianos = $row['sum_monto'] * 6.96;
                  } else {
                    $total_bolivianos = $row['sum_monto'];
                    $total_dolares = $row['sum_monto'] / 6.96;
                  }
                ?><tr>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['usuario']; ?></td>
                    <td><?php echo $row['cod_siniestro']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['tipo']; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $total_bolivianos; ?></td>
                    <td><?php echo $total_dolares; ?></td>
                    <td><?php echo $row['cantidad']; ?></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td><?php echo $row['concepto']; ?></td>
                    <td><?php echo $row['precio']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'reporte_comercial2':
          //SEGUN REUNION DEL 2022-09-15 Se toma como dato referencia para reporte de produccion la fecha de inicio de vigencia de poliza
          // $msj_log = "REPORTE PRODUCCION COMERCIAL";

          $consulta = "SELECT pc.id_calculo_prima,month(pc.f_registro) as mes,year(pc.f_registro) as anio, rc.cod_poliza,rc.cod_cliente, rc.regional,rc.tomador,pc.asegurado,rc.cod_cotizacion,
            rc.ramo,rc.tipo_poliza,pc.movimiento,rc.nro_anexo, rc.asi_vial, rc.cant_autos, pc.observaciones, 
            ROUND(pc.valor_asegurado,2),ROUND(pc.valor_primera_perdida,2),ROUND(pc.valor_terremoto,2), ROUND(pc.valor_terrorismo,2),
                                ROUND (case 
                                  when pc.tipo_pago='CONTADO' THEN pc.prima_contado  
                                  when pc.tipo_pago='CREDITO' THEN pc.prima_credito
                                END ,2) as prima_total_c,
            rc.moneda,pc.p_factor_tasa_tecnica, ROUND(pc.prima_neta,2),pc.fecha_emision,pc.fecha_inicio,pc.fecha_fin, pc.dias_transcurridos,rc.tipo_cartera,rc.subtipo_cartera,
            pc.tipo_pago, rc.intermediario,ROUND(pc.com_intermediario_gnv,2),ROUND(pc.com_intermediario,2),pc.num_cuota,pc.f_registro,pc.usuario,rc.modalidad
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

          $consulta .= " ORDER BY pc.f_registro ASC";
          //echo $consulta;
          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>Nro. REGISTRO</th>
                  <th>MES</th>
                  <th>AÑOS</th>
                  <th>Nro. PÓLIZA</th>
                  <th>Nro. COTIZACION</th>
                  <th>Cod. CLIENTE</th>
                  <th>REGIONAL</th>
                  <th>TOMADOR</th>
                  <th>ASEGURADO</th>
                  <th>RAMO</th>
                  <th>TIPO POLIZA</th>
                  <th>TIPO MOVIMIENTO</th>
                  <th>NRO. ANEXO</th>
                  <th>ASISTENCIA VIAL</th>
                  <th>CANTIDAD DE ITEMS</th>
                  <th>OBSERVACIONES</th>
                  <th>VALOR ASEGURADO</th>
                  <th>VALOR PRIMERA</th>
                  <th>TERREMOTO</th>
                  <th>TERRORISMO</th>
                  <th>PRIMA TOTAL</th>
                  <th>MONEDA</th>
                  <th>FACTOR DE NETO</th>
                  <th>PRIMA NETA</th>
                  <th>FECHA DE EMISION</th>
                  <th>FECHA INICIO DE VIGENCIA</th>
                  <th>FECHA FIN DE VIGENCIA</th>
                  <th>DÍAS DE VIGENCIA</th>
                  <th>SECTOR</th>
                  <th>TIPO DE CARTERA</th>
                  <th>SUBTIPO DE CARTERA</th>
                  <th>INTERMEDIARIO</th>
                  <th>TIPO PAGO</th>
                  <th>COMISION INTERMEDIARIO GNV</th>
                  <th>COMISION INTERMEDIARIO</th>
                  <th>NUMERO DE CUOTAS</th>
                  <th>FECHA Y HORA DE REGISTRO</th>
                  <th>USUARIO</th>
                  <th>MODALIDAD</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                  if ($row['tipo_cartera'] == 'ESTATAL') {
                    $inprime = $row['intermediario'];
                    $inter = "CARTERA DIRECTA";
                  } else {
                    $inprime = "";
                    $inter = $row['intermediario'];
                  }
                  if ($row['prima_total_c'] < 0) {
                    $asistencia_vial = 'NO';
                  } else {
                    $asistencia_vial = $row['asi_vial'];
                  }
                ?><tr>
                    <td><?php echo $row['id_calculo_prima']; ?></td>
                    <td><?php echo $row['mes']; ?></td>
                    <td><?php echo $row['anio']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['cod_cotizacion']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['regional']; ?></td>
                    <td><?php echo $row['tomador']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['tipo_poliza']; ?></td>
                    <td><?php echo $row['movimiento']; ?></td>
                    <td><?php echo $row['nro_anexo']; ?></td>
                    <td><?php echo $asistencia_vial; ?></td>
                    <td><?php echo $row['cant_autos']; ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                    <td><?php echo $row['ROUND(pc.valor_asegurado,2)']; ?></td>
                    <td><?php echo $row['ROUND(pc.valor_primera_perdida,2)']; ?></td>
                    <td><?php echo $row['ROUND(pc.valor_terremoto,2)']; ?></td>
                    <td><?php echo $row['ROUND(pc.valor_terrorismo,2)']; ?></td>
                    <td><?php echo $row['prima_total_c']; ?></td>
                    <td><?php echo $row['moneda']; ?></td>
                    <td><?php echo $row['p_factor_tasa_tecnica']; ?></td>
                    <td><?php echo $row['ROUND(pc.prima_neta,2)']; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $row['fecha_inicio']; ?></td>
                    <td><?php echo $row['fecha_fin']; ?></td>
                    <td><?php echo $row['dias_transcurridos']; ?></td>
                    <td><?php echo $row['tipo_cartera']; ?></td>
                    <td><?php echo $row['subtipo_cartera']; ?></td>
                    <td><?php echo $inprime ?></td>
                    <td><?php echo $inter ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['ROUND(pc.com_intermediario_gnv,2)']; ?></td>
                    <td><?php echo $row['ROUND(pc.com_intermediario,2)']; ?></td>
                    <td><?php echo $row['num_cuota']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['usuario']; ?></td>
                    <td><?php echo $row['modalidad']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'comercial_especifico':
          //$msj_log = "REPORTE COMERCIAL ESPECIFICO";

          $consulta = "SELECT nro_poliza, cod_cliente, tipo_movimiento, regional, valor_asegurado, tomador, prima_total, asegurado, 
          fecha_emision, ramo, f_inicio_vigencia, f_final_vigencia, tipo_cartera, fecha_registro 
          FROM unibienes.reporte_comercial";
          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE fecha_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE COMERCIAL ESPECIFICO BASE DE UNIBIENES EL $fecha_dia";
            $titulo = "VISTA PREVIA DE REPORTE COMERCIAL ESPECÍFICO. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE fecha_registro >= '$fecha_inicio' and fecha_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE COMERCIAL ESPECIFICO BASE DE UNIBIENES ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE REPORTE COMERCIAL ESPECÍFICO. DESDE $fecha_inicio HASTA $fecha_final";
          }

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>NRO POLIZA</th>
                  <th>COD CLIENTE</th>
                  <th>TIPO MOVIMIENTO</th>
                  <th>REGIONAL</th>
                  <th>VALOR ASEGURADO</th>
                  <th>TOMADOR</th>
                  <th>PRIMA TOTAL</th>
                  <th>ASEGURADO</th>
                  <th>FECHA EMISION</th>
                  <th>RAMO</th>
                  <th>INICIO VIGENCIA</th>
                  <th>FIN VIGENCIA</th>
                  <th>TIPO CARTERA</th>
                  <th>FECHA REGISTRO</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <tr>
                    <td><?php echo $row['nro_poliza']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['tipo_movimiento']; ?></td>
                    <td><?php echo $row['regional']; ?></td>
                    <td><?php echo $row['valor_asegurado']; ?></td>
                    <td><?php echo $row['tomador']; ?></td>
                    <td><?php echo $row['prima_total']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['f_inicio_vigencia']; ?></td>
                    <td><?php echo $row['f_final_vigencia']; ?></td>
                    <td><?php echo $row['tipo_cartera']; ?></td>
                    <td><?php echo $row['fecha_registro']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>

            </table>
          </div>
        <?php
          break;
        case 'reporte_comercial':
          //$msj_log = "REPORTE COMERCIAL";
          //$consulta_log = "SELECT * FROM unibienes.reporte_comercial WHERE fecha_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\'";
          $consulta = "SELECT * FROM unibienes.reporte_comercial";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE fecha_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE COMERCIAL BASE UNIBIENES EL $fecha_dia";
            $titulo = "VISTA PREVIA DE REPORTE COMERCIAL. EL $fecha_dia";
          } else {
            $consulta .= " WHERE fecha_registro >= '$fecha_inicio' and fecha_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE COMERCIAL BASE UNIBIENES ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE REPORTE COMERCIAL. DESDE $fecha_inicio HASTA $fecha_final";
          }

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>Codigo</th>
                  <th>Mes</th>
                  <th>Ano</th>
                  <th>Poliza</th>
                  <th>Cod Cliente</th>
                  <th>Regional</th>
                  <th>Tomador</th>
                  <th>Asegurado</th>
                  <th>Ramo</th>
                  <th>Tipo Poliza</th>
                  <th>Tipo Movimiento</th>
                  <th>Nro Anexo</th>
                  <th>Asistencia Vial</th>
                  <th>Cant. Autos</th>
                  <th>Observaciones</th>
                  <th>Valor Asegurado</th>
                  <th>Valor Primera</th>
                  <th>Terremoto</th>
                  <th>Terrorismo</th>
                  <th>Prima Total</th>
                  <th>Moneda</th>
                  <th>Prima Neta</th>
                  <th>Tasa Anual</th>
                  <th>Carnet</th>
                  <th>Fecha de Emision</th>
                  <th>Fecha de Inicio de Vigencia</th>
                  <th>Fecha de Final de Vigencia</th>
                  <th>Dias de Vigencia</th>
                  <th>Tipo Cartera</th>
                  <th>Subtipo Cartera</th>
                  <th>Tipo Pago</th>
                  <th>Intermediario</th>
                  <th>Num. Cuotas</th>
                  <th>Usuario</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <tr>
                    <td><?php echo $row['id_rc']; ?></td>
                    <td><?php echo $row['mes']; ?></td>
                    <td><?php echo $row['ano']; ?></td>
                    <td><?php echo $row['nro_poliza']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['regional']; ?></td>
                    <td><?php echo $row['tomador']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['tipo_poliza']; ?></td>
                    <td><?php echo $row['tipo_movimiento']; ?></td>
                    <td><?php echo $row['nro_anexo']; ?></td>
                    <td><?php echo $row['asi_vial']; ?></td>
                    <td><?php echo $row['cant_autos']; ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                    <td><?php echo $row['valor_asegurado']; ?></td>
                    <td><?php echo $row['valor_primera']; ?></td>
                    <td><?php echo $row['terremoto']; ?></td>
                    <td><?php echo $row['terrorismo']; ?></td>
                    <td><?php echo $row['prima_total']; ?></td>
                    <td><?php echo $row['moneda']; ?></td>
                    <td><?php echo $row['prima_neta']; ?></td>
                    <td><?php echo $row['tasa_anual']; ?></td>
                    <td><?php echo $row['ci_nit']; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $row['f_inicio_vigencia']; ?></td>
                    <td><?php echo $row['f_final_vigencia']; ?></td>
                    <td><?php echo $row['dias']; ?></td>
                    <td><?php echo $row['tipo_cartera']; ?></td>
                    <td><?php echo $row['subtipo_cartera']; ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['intermediario']; ?></td>
                    <td><?php echo $row['numero_cuotas']; ?></td>
                    <td><?php echo $row['usuario']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>

            </table>
          </div>
        <?php
          break;
        case 'produccion':
          //$msj_log = "REPORTE PRODUCCION";
          //$consulta_log = "SELECT * FROM unibienes.n_produccion WHERE f_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\'";
          $consulta = "SELECT * FROM unibienes.n_produccion WHERE f_registro BETWEEN '$fecha_inicio' and '$fecha_final'";
          $result = mysqli_query($con, $consulta);
          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE PRODUCCION BASE UNIBIENES EL $fecha_dia";
            $titulo = "VISTA PREVIA DE REPORTE PRODUCCIÓN. EL $fecha_dia";
          } else {
            $consulta .= " WHERE f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE PRODUCCION BASE UNIBIENES ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE REPORTE PRODUCCIÓN. DESDE $fecha_inicio HASTA $fecha_final";
          }

        ?>
          <h2 align="center"> <?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>CIA</th>
                  <th>NOMBRE CIA</th>
                  <th>CODIGO PARTE</th>
                  <th>OFICINA</th>
                  <th>COD SECTOR</th>
                  <th>COD MONEDA</th>
                  <th>FECHA INFORMACION</th>
                  <th>FECHA EMISION</th>
                  <th>FECHA INICIO VIGENCIA</th>
                  <th>FECHA FINAL VIGENCIA</th>
                  <th>MODALIDAD</th>
                  <th>RAMO</th>
                  <th>DESC RAMO</th>
                  <th>ASEGURADO</th>
                  <th>CODIGO CLIENTE</th>
                  <th>NUMERO POLIZA</th>
                  <th>TIPO PAGO</th>
                  <th>VALOR EN RIESGO</th>
                  <th>VALOR ASEGURADO</th>
                  <th>TERRORISMO</th>
                  <th>TERREMOTO</th>
                  <th>TIPO ASEGURAMIENTO</th>
                  <th>NUM POLIZAS EMITIDAS</th>
                  <th>NUM POLIZAS RENOVADAS</th>
                  <th>NUM CERTIFICADO</th>
                  <th>TIPO REASEGURO</th>
                  <th>PRIMA COMERCIAL</th>
                  <th>ITF</th>
                  <th>IT</th>
                  <th>IVA</th>
                  <th>ABA</th>
                  <th>FPA</th>
                  <th>APS</th>
                  <th>ITF REMESAS</th>
                  <th>INTERMEDIARIO</th>
                  <th>COM INTER</th>
                  <th>COM INTER FACT</th>
                  <th>IUE REMESAS</th>
                  <th>COM BANCARIA</th>
                  <th>COM COMPANIA</th>
                  <th>COM COBRANZA 1</th>
                  <th>COM COBRANZA 2</th>
                  <th>NOMB INTER</th>
                  <th>PRIMA RIESGO</th>
                  <th>PRIMA DIRECTA</th>
                  <th>PRIMA DIRECTA BS</th>
                  <th>PRIMA RENOVADAS</th>
                  <th>PRIMA RENOVADAS BS</th>
                  <th>PRIMA ACEPT COASEGURO</th>
                  <th>PRIMA ACEPT COASEGURO BS</th>
                  <th>VALOR ASEGURADO ANULADO</th>
                  <th>NRO POL ANULADAS</th>
                  <th>PRIMA ANULADA</th>
                  <th>PRIMA ANULADA BS</th>
                  <th>PRIMA RENOV ANU</th>
                  <th>PRIMA RENOV ANU BS</th>
                  <th>PRIMA COASEG ANU</th>
                  <th>NRO POLIZAS NETAS</th>
                  <th>VALOR ASEGURADO CEDIDO</th>
                  <th>VALOR ASEGURADO CEDIDO ANU</th>
                  <th>PRIMA NETA DIRECTA</th>
                  <th>PRIMA NETA DIRECTA BS</th>
                  <th>PRIMA NETA ACEP REASEG NAL</th>
                  <th>PRIMA NETA ACEP REASEG NAL BS</th>
                  <th>PRIMA ACEP ANU REASEG NAL</th>
                  <th>PRIMA ACEP ANU REASEG NAL BS</th>
                  <th>PRIMA ACEP REASEG EXT</th>
                  <th>PRIMA ACEP REASEG EXT BS</th>
                  <th>PRIMA ANU ACEP REASEG EXT</th>
                  <th>PRIMA ANU ACEP REASEG EXT BS</th>
                  <th>PRIMA CEDIDA REASEGURO</th>
                  <th>PRIMA CEDIDA REASEGURO BS</th>
                  <th>COM REASEGURO</th>
                  <th>COM REASEGURO ANU</th>
                  <th>ANU PRIMA CEDIDA REASEG</th>
                  <th>ANU PRIMA CEDIDA REASEG BS</th>
                  <th>DISTRITO</th>
                  <th>FECHA REGISTRO</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?><tr>
                    <td><?php echo $row['cia']; ?></td>
                    <td><?php echo $row['nombre_cia']; ?></td>
                    <td><?php echo $row['cod_parte']; ?></td>
                    <td><?php echo $row['oficina']; ?></td>
                    <td><?php echo $row['cod_sector']; ?></td>
                    <td><?php echo $row['cod_moneda']; ?></td>
                    <td><?php echo $row['fecha_informacion']; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $row['inicio_vigencia']; ?></td>
                    <td><?php echo $row['final_vigencia']; ?></td>
                    <td><?php echo $row['modalidad']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['desc_ramo']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['valor_en_riesgo']; ?></td>
                    <td><?php echo $row['valor_asegurado']; ?></td>
                    <td><?php echo $row['terrorismo_riesgo']; ?></td>
                    <td><?php echo $row['terremoto']; ?></td>
                    <td><?php echo $row['tipo_aseguramiento']; ?></td>
                    <td><?php echo $row['nro_polizas_emitidas']; ?></td>
                    <td><?php echo $row['nro_polizas_renovadas']; ?></td>
                    <td><?php echo $row['nro_certificado']; ?></td>
                    <td><?php echo $row['tipo_de_reaseguro']; ?></td>
                    <td><?php echo $row['prima_comercial']; ?></td>
                    <td><?php echo $row['itf']; ?></td>
                    <td><?php echo $row['it']; ?></td>
                    <td><?php echo $row['iva']; ?></td>
                    <td><?php echo $row['aba']; ?></td>
                    <td><?php echo $row['fpa']; ?></td>
                    <td><?php echo $row['aps']; ?></td>
                    <td><?php echo $row['itf_remesas']; ?></td>
                    <td><?php echo $row['nom_intermediario']; ?></td>
                    <td><?php echo $row['com_inter']; ?></td>
                    <td><?php echo $row['com_fac_inter']; ?></td>
                    <td><?php echo $row['iue_remesas']; ?></td>
                    <td><?php echo $row['com_bancaria']; ?></td>
                    <td><?php echo $row['com_compania']; ?></td>
                    <td><?php echo $row['com_cobranza_uno']; ?></td>
                    <td><?php echo $row['com_cobranza_dos']; ?></td>
                    <td><?php echo $row['nombre_inter']; ?></td>
                    <td><?php echo $row['prima_riesgo']; ?></td>
                    <td><?php echo $row['prima_directa']; ?></td>
                    <td><?php echo $row['prima_directa_bs']; ?></td>
                    <td><?php echo $row['prima_renovadas']; ?></td>
                    <td><?php echo $row['prima_renovadas_bs']; ?></td>
                    <td><?php echo $row['prima_acep_coaseguro']; ?></td>
                    <td><?php echo $row['prima_acep_coaseguro_bs']; ?></td>
                    <td><?php echo $row['valor_aseg_anulado']; ?></td>
                    <td><?php echo $row['nro_pol_anuladas']; ?></td>
                    <td><?php echo $row['prima_anulada']; ?></td>
                    <td><?php echo $row['prima_anulada_bs']; ?></td>
                    <td><?php echo $row['prima_renov_anu']; ?></td>
                    <td><?php echo $row['prima_renov_anu_bs']; ?></td>
                    <td><?php echo $row['prima_coaseg_anu']; ?></td>
                    <td><?php echo $row['nro_polizas_netas']; ?></td>
                    <td><?php echo $row['valor_aseg_cedido']; ?></td>
                    <td><?php echo $row['valor_aseg_cedido_anu']; ?></td>
                    <td><?php echo $row['prima_neta_directa']; ?></td>
                    <td><?php echo $row['prima_neta_directa_bs']; ?></td>
                    <td><?php echo $row['prima_acep_reaseg_nal']; ?></td>
                    <td><?php echo $row['prima_acep_reaseg_nal_bs']; ?></td>
                    <td><?php echo $row['pri_acep_anu_reaseg_nal']; ?></td>
                    <td><?php echo $row['pri_acep_anu_reaseg_nal_bs']; ?></td>
                    <td><?php echo $row['pri_acep_reaseg_ext']; ?></td>
                    <td><?php echo $row['pri_acep_reaseg_ext_bs']; ?></td>
                    <td><?php echo $row['pri_acep_anu_reaseg_ext']; ?></td>
                    <td><?php echo $row['pri_acep_anu_reaseg_ext_bs']; ?></td>
                    <td><?php echo $row['pri_cedidas_reaseg']; ?></td>
                    <td><?php echo $row['pri_cedidas_reaseg_bs']; ?></td>
                    <td><?php echo $row['comi_reaseg']; ?></td>
                    <td><?php echo $row['comi_reaseg_anu']; ?></td>
                    <td><?php echo $row['anu_primas_cedidas_reaseg']; ?></td>
                    <td><?php echo $row['anu_primas_cedidas_reaseg_bs']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>

            </table>
          </div>
        <?php
          break;
        case 'sinistros_liquidados_au_unisersoft':
          require_once "reports/siniestros/sinistros_liquidados_au_unisersoft.php";
          break;
        case 'sinistros_pendientes':
          //$msj_log = "REPORTE SINIESTROS PENDIENTES";
          /* $consulta_log = "SELECT s.num_siniestro, s.fecha_siniestro,s.fecha_registro, s.fecha_denuncia, c.lugar_incidente, s.distrito
          , s.asegurado,s.clase, s.marca, s.placa_sin,s.uso,c.narracion_hecho,
           s.num_poliza,s.monto_reserva,s.estado,s.insperctor, s.cod_cliente as codigo,s.cobertura_aplicar,c.datalle_dano
           FROM (unibienes.siniestros as s INNER JOIN unibienes.circun_siniestro as c on c.num_siniestro=s.num_siniestro)
           WHERE s.fecha_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\' 
          GROUP BY s.num_siniestro"; */

          $consulta = "SELECT s.num_siniestro, s.fecha_siniestro,s.fecha_registro, s.fecha_denuncia, c.lugar_incidente, s.distrito
          , s.asegurado,s.clase, s.marca, s.placa_sin,s.uso,c.narracion_hecho,
           s.num_poliza,s.monto_reserva,s.estado,s.insperctor, s.cod_cliente as codigo,s.cobertura_aplicar ,c.datalle_dano
           FROM (unibienes.siniestros as s INNER JOIN unibienes.circun_siniestro as c on c.num_siniestro=s.num_siniestro) ";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE s.fecha_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE SINIESTROS PENDIENTES BASE UNIBIENES EL $fecha_dia";
            $titulo = "VISTA PREVIA DE SINISTROS PENDIENTES. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE s.fecha_registro >= '$fecha_inicio' and s.fecha_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE SINIESTROS PENDIENTES BASE UNIBIENES ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE SINISTROS PENDIENTES. DESDE $fecha_inicio HASTA $fecha_final";
          }

          $consulta .= " GROUP BY s.num_siniestro";

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>Nro. Sinistro</th>
                  <th>Fecha Sinistros</th>
                  <th>Fecha Registro Siniestro</th>
                  <th>Fecha Denuncia</th>
                  <th>Departamento Ocurrencia Siniestro</th>
                  <th>Sucursal</th>
                  <th>Asegurado</th>
                  <th>Telefono</th>
                  <th>Correo</th>
                  <th>Clase</th>
                  <th>Marca</th>
                  <th>Placa</th>
                  <th>Uso Vehiculo</th>
                  <th>Detalle Siniestro</th>
                  <th>Nro. Poliza</th>
                  <th>Inicio Vigencia</th>
                  <th>Fin Vigencia</th>
                  <th>Cobertura</th>
                  <th>Monto Reserva (USD)</th>
                  <th>Estado</th>
                  <?php
                  if ($cargo == 'admin' or $cargo == 'ESTADISTICA') {
                  ?>
                    <th>DETALLE DE DAÑOS</th>
                    <th>CAMBIO DE ESTADO</th>
                    <th>FECHA DE CAMBIO DE ESTADO</th>
                  <?php
                  }
                  ?>
                  <th>Inspector</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                  $codigo = $row['codigo'];
                  $num_siniestro = $row['num_siniestro'];
                  $sele2 = $con->query("SELECT telefono_fijo,email from unibienes.clientes where cod_cliente = '$codigo' limit 1");
                  $filas2 = $sele2->fetch_assoc();
                  $telefono_fijo = $filas2['telefono_fijo'];
                  $email = $filas2['email'];

                  $num_poliza = $row['num_poliza'];
                  $sele23 = $con->query("SELECT estado,
                (case when estado = 'NUEVO' then inicio_vigencia end) as ini_v,
                (case when estado = 'NUEVO' then final_vigencia end) as fin_v,
                max(case when estado = 'RENOVACION' then inicio_vigencia  end) as r_ini_v,
                max(case when estado = 'RENOVACION' then final_vigencia  end) as r_fin_v 
                from unibienes.automovil where 
                nro_poliza = '$num_poliza' limit 1");
                  $filas23 = $sele23->fetch_assoc();
                  $estado_au = $filas23['estado'];
                  if ($estado_au == 'NUEVO') {
                    $ini_vig = $filas23['ini_v'];
                    $fin_vig = $filas23['fin_v'];
                  } else {
                    $ini_vig = $filas23['r_ini_v'];
                    $fin_vig = $filas23['r_fin_v'];
                  }

                ?>
                  <tr>
                    <td><?php echo $row['num_siniestro']; ?></td>
                    <td><?php echo $row['fecha_siniestro']; ?></td>
                    <td><?php echo $row['fecha_registro']; ?></td>
                    <td><?php echo $row['fecha_denuncia']; ?></td>
                    <td><?php echo $row['lugar_incidente']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $telefono_fijo; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $row['clase']; ?></td>
                    <td><?php echo $row['marca']; ?></td>
                    <td><?php echo $row['placa_sin']; ?></td>
                    <td><?php echo $row['uso']; ?></td>
                    <td><?php echo $row['narracion_hecho']; ?></td>
                    <td><?php echo $row['num_poliza']; ?></td>
                    <td><?php echo $ini_vig; ?></td>
                    <td><?php echo $fin_vig; ?></td>
                    <td><?php echo utf8_decode($row['cobertura_aplicar']); ?></td>
                    <td><?php echo $row['monto_reserva']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <?php
                    if ($cargo == 'admin' or $cargo == 'ESTADISTICA') {
                      $query_aux = "SELECT estado,fecha_registro FROM unibienes.datos_act_sin WHERE num_siniestro='$num_siniestro'";
                      $res = mysqli_query($con, $query_aux);
                      $estado_aux = "";
                      $fecha_registro_aux = "";
                      while ($r = mysqli_fetch_assoc($res)) {
                        $estado_aux .= $r['estado'] . "<br>";
                        $final_fecha = explode(" ", $r['fecha_registro']);
                        $fecha_registro_aux .= $final_fecha[0] . "<br>";
                      }
                    ?>
                      <td><?php echo utf8_decode($row['datalle_dano']); ?></td>
                      <td><?= $estado_aux ?></td>
                      <td><?= $fecha_registro_aux ?></td>
                    <?php
                    }
                    ?>
                    <td><?php echo $row['insperctor']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>

            </table>
          </div>
        <?php
          break;
        //MODIFICACION DEL REPORTE DE CORRESPONDENCIA para las privilegios de vistas
        //dejar los espacios vacios
        case 'constancia_documentos':
          $msj_log = "CONSTANCIA DE ENTREGA DE DOCUMENTOS";
          /* $consulta_log = "SELECT *
                        FROM correspondencia.cartas
                        WHERE fecha_recepcion BETWEEN \'$fecha_inicio\' and \'$fecha_final\'
                        GROUP BY id_cartas";
 */
          $consulta = "SELECT *
                     FROM correspondencia.cartas";
          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE fecha_recepcion like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE CONSTANCIA DE ENTREGA DE DOCUMENTOS BASE CORREPONDENCIA EL $fecha_dia";
            $titulo = "VISTA PREVIA DE CONSTANCIA DE ENTREGA DE DOCUMENTOS. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE fecha_recepcion >= '$fecha_inicio' and fecha_recepcion <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE CONSTANCIA DE ENTREGA DE DOCUMENTOS BASE CORREPONDENCIA ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE CONSTANCIA DE ENTREGA DE DOCUMENTOS. DESDE $fecha_inicio HASTA $fecha_final";
          }

          $consulta .= " and estado != 'ANULADO' GROUP BY id_cartas ORDER BY num_hoja_ruta desc ";

          //echo $consulta;

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>#</th>
                  <th># DE HOJA DE RUTA:</th>
                  <th>REMITENTE:</th>
                  <!-- <th>GERENCIA/UNIDAD/JEFATURA DEL REMITENTE:</th>
                  <th>REGIONAL DEL REMITENTE:</th>
                  <th>TIPO DE DOCUMENTO:</th>
                  <th>CITE INTERNO/EXTERNO:</th> -->
                  <th>REFERENCIA:</th>
                  <th>FECHA DE RECEPCI&Oacute;N:</th>
                  <th>FECHA DE INGRESO:</th>
                  <th>FECHA DE SALIDA:</th>
                  <th>OBSERVACI&Oacute;N:</th>
                  <!-- <th>GERENCIA/UNIDAD/JEFATURA DEL DESTINATARIO:</th>
                  <th>REGIONAL DEL DESTINATARIO:</th> -->
                  <th>CONSTANCIA DE ENTREGA:</th>
                </tr>
              </thead>
              <tbody>

                <?php
                $k = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                  $recepciona = $row['recepcionado'];
                  $remitente = $row['remitente'];
                  $tipo_doc = $row['tipo_documento'];
                  $cons = "SELECT *
                            FROM correspondencia.usuarios
                            WHERE usuario = '$recepciona'";

                  $sel_remitente = $con->query("SELECT *
                                              FROM correspondencia.usuarios
                                              WHERE usuario = '$remitente'");
                  $fila_remitente = $sel_remitente->fetch_assoc();
                  $unidad_rem = $fila_remitente['unidad'];
                  $regional_rem = $fila_remitente['sucursal'];
                  if ($tipo_doc == 'Informe' or $tipo_doc == 'Comunicacion Interna')
                    $nom_remitente = $fila_remitente['nombres'] . " " . $fila_remitente['apellidos'];
                  else
                    $nom_remitente = $remitente;

                  $sel_comer = $con->query("SELECT *
                                              FROM correspondencia.usuarios
                                              WHERE usuario = '$recepciona'");
                  $fila_recepciona = $sel_comer->fetch_assoc();
                  $nom_recepciona = $fila_recepciona['nombres'] . " " . $fila_recepciona['apellidos'];
                  $unidad_rec = $fila_recepciona['unidad'];
                  $regional_rec = $fila_recepciona['sucursal'];
                ?>
                  <tr>
                    <td><?php echo $k ?></td>
                    <td><?php echo $row['num_hoja_ruta']; ?></td>
                    <td><?php echo strtoupper($nom_remitente); ?></td>
                    <!-- <td><?php echo strtoupper($unidad_rem); ?></td>
                      <td><?php echo strtoupper($regional_rem); ?></td>
                      <td><?php echo strtoupper($row['tipo_documento']); ?></td>
                      <td><?php echo $row['cite_externo']; ?></td> -->
                    <td><?php echo strtoupper($row['referencia']); ?></td>
                    <td><?php echo $row['fecha_registro']; ?></td>
                    <td></td>
                    <td></td>
                    <td><?php echo strtoupper($nom_recepciona) ?></td>
                    <!-- <td><?php echo strtoupper($unidad_rec) ?></td>
                      <td><?php echo strtoupper($regional_rec) ?></td> -->
                    <td></td>
                  </tr>
                <?php
                  $k++;
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;

        case 'constancia_documentos_recepcion':
          $msj_log = "CONSTANCIA DE ENTREGA DE DOCUMENTOS";
          /*  $consulta_log = "SELECT *
                          FROM correspondencia.cartas
                          WHERE fecha_recepcion BETWEEN \'$fecha_inicio\' and \'$fecha_final\'
                          AND user = '$usuario'
                          AND estado != 'ANULADO'
                          GROUP BY id_cartas"; */

          $consulta = "SELECT *
                      FROM correspondencia.cartas";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE fecha_recepcion like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE CONSTANCIA DE ENTREGA DE DOCUMENTOS BASE CORREPONDENCIA EL $fecha_dia";
            $titulo = "VISTA PREVIA DE CONSTANCIA DE ENTREGA DE DOCUMENTOS. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE fecha_recepcion >= '$fecha_inicio' and fecha_recepcion <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE CONSTANCIA DE ENTREGA DE DOCUMENTOS BASE CORREPONDENCIA ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE CONSTANCIA DE ENTREGA DE DOCUMENTOS. DESDE $fecha_inicio HASTA $fecha_final";
          }

          $consulta .= " AND user = '$usuario' and estado != 'ANULADO' GROUP BY id_cartas ORDER BY num_hoja_ruta desc ";

          //echo $consulta;

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>#</th>
                  <th># DE HOJA DE RUTA:</th>
                  <th>REMITENTE:</th>
                  <!-- <th>GERENCIA/UNIDAD/JEFATURA DEL REMITENTE:</th>
                  <th>REGIONAL DEL REMITENTE:</th>
                  <th>TIPO DE DOCUMENTO:</th>
                  <th>CITE INTERNO/EXTERNO:</th> -->
                  <th>REFERENCIA:</th>
                  <th>FECHA DE RECEPCI&Oacute;N:</th>
                  <th>FECHA DE INGRESO:</th>
                  <th>FECHA DE SALIDA:</th>
                  <th>OBSERVACI&Oacute;N:</th>
                  <!-- <th>GERENCIA/UNIDAD/JEFATURA DEL DESTINATARIO:</th>
                  <th>REGIONAL DEL DESTINATARIO:</th> -->
                  <th>CONSTANCIA DE ENTREGA:</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $k = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                  $recepciona = $row['recepcionado'];
                  $remitente = $row['remitente'];
                  $tipo_doc = $row['tipo_documento'];
                  $cons = "SELECT *
                            FROM correspondencia.usuarios
                            WHERE usuario = '$recepciona'";

                  $sel_remitente = $con->query("SELECT *
                                              FROM correspondencia.usuarios
                                              WHERE usuario = '$remitente'");
                  $fila_remitente = $sel_remitente->fetch_assoc();
                  $unidad_rem = $fila_remitente['unidad'];
                  $regional_rem = $fila_remitente['sucursal'];

                  if ($tipo_doc == 'Informe' or $tipo_doc == 'Comunicacion Interna')
                    $nom_remitente = $fila_remitente['nombres'] . " " . $fila_remitente['apellidos'];
                  else
                    $nom_remitente = $remitente;

                  $sel_comer = $con->query("SELECT *
                                              FROM correspondencia.usuarios
                                              WHERE usuario = '$recepciona'");
                  $fila_recepciona = $sel_comer->fetch_assoc();
                  $nom_recepciona = $fila_recepciona['nombres'] . " " . $fila_recepciona['apellidos'];
                  $unidad_rec = $fila_recepciona['unidad'];
                  $regional_rec = $fila_recepciona['sucursal'];
                ?>
                  <tr>
                    <td><?php echo $k ?></td>
                    <td><?php echo $row['num_hoja_ruta']; ?></td>
                    <td><?php echo utf8_encode(strtoupper($nom_remitente)); ?></td>
                    <td><?php echo utf8_decode(utf8_encode(strtoupper($row['referencia']))); ?></td>
                    <td><?php echo $row['fecha_recepcion']; ?></td>
                    <td></td>
                    <td></td>
                    <td><?php echo utf8_decode(utf8_encode(strtoupper($nom_recepciona))) ?></td>
                    <td></td>
                  </tr>
                <?php
                  $k++;
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'pxclife':
          $msj_log = "CONSTANCIA DE ENTREGA DE DOCUMENTOS";
          /*  $consulta_log = "SELECT *
                        FROM unibienes.pxclife
                        WHERE f_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\'
                        GROUP BY id_pxc"; */
          $consulta = "SELECT *
                    FROM unibienes.pxclife";
          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE DE PRIMAS POR COBRAR - BASE UNIBIENES EL $fecha_dia";
            $titulo = "VISTA PREVIA DE PRIMAS POR COBRAR (Base Unibienes). DEL $fecha_dia";
          } else {
            $consulta .= " WHERE f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE DE PRIMAS POR COBRAR - BASE UNIBIENES ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE PRIMAS POR COBRAR (Base Unibienes). ENTRE $fecha_inicio HASTA $fecha_final";
          }

          $consulta .= " and estado != 'ANULADO' GROUP BY id_pxc";

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>Nro.</th>
                  <th>Nro. Poliza</th>
                  <th>Cod. Cliente</th>
                  <th>Cod. Cotizacion</th>
                  <th>Fecha de Emision</th>
                  <th>Prima Total</th>
                  <th>Tipo de Pago</th>
                  <th>Cuota</th>
                  <th>Estado</th>
                  <th>Numero de Factura</th>
                  <th>Intermediario</th>
                  <th>Canal</th>
                  <th>Fecha de Registro</th>
                  <th>Fecha de Pago</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <tr>
                    <td><?php echo $row['id_pxc']; ?></td>
                    <td><?php echo $row['nro_poliza']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['cod_cotizacion']; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $row['prima_total']; ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['cuotas']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['numero_factura']; ?></td>
                    <td><?php echo $row['intermediario']; ?></td>
                    <td><?php echo $row['canal']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['f_pago']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'primas_cobranzas':
          //$msj_log = "CONSTANCIA DE ENTREGA DE DOCUMENTOS";
          /*  $consulta_log = "SELECT *
                          FROM unibienes.pxclife
                          WHERE f_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\'
                          GROUP BY id_pxc"; */
          $consulta = "SELECT *
                      FROM comercial.primas_cobranzas";
          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE DE PRIMAS POR COBRAR - BASE UNISERSOFT EL $fecha_dia";
            $titulo = "VISTA PREVIA DE PRIMAS POR COBRAR (Base UNISERSOFT). DEL $fecha_dia";
          } else {
            $consulta .= " WHERE f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE DE PRIMAS POR COBRAR - BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE PRIMAS POR COBRAR (Base UNISERSOFT). ENTRE $fecha_inicio HASTA $fecha_final";
          }

          $consulta .= " and estado != 'ELIMINADO'";

          //echo $consulta;

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>Nro.</th>
                  <th>Nro. Póliza</th>
                  <th>Cod. Cliente</th>
                  <th>Cod. Cotización</th>
                  <th>Movimiento</th>
                  <th>Cod. Unico</th>
                  <th>Fecha de Emisión</th>
                  <th>Prima Total</th>
                  <th>Tipo de Pago</th>
                  <th>Cuotas</th>
                  <th>Estado</th>
                  <th>Numero de Factura</th>
                  <th>Intermediario</th>
                  <th>Canal</th>
                  <th>Comentario</th>
                  <th>Fecha de Registro</th>
                  <th>Fecha de Pago</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <tr>
                    <td><?php echo $row['id_pxc']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['cod_cotizacion']; ?></td>
                    <td><?php echo $row['movimiento']; ?></td>
                    <td><?php echo $row['cod_unico']; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $row['prima_total']; ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['cuotas']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['numero_factura']; ?></td>
                    <td><?php echo $row['intermediario']; ?></td>
                    <td><?php echo $row['canal']; ?></td>
                    <td><?php echo $row['estadopxc']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['f_pago']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'siniestros_generales':
          $msj_log = "SINIESTROS GENERALES";
          /* $consulta_log = "SELECT * FROM comercial.siniestros as s inner join comercial.clientes as c on c.cod_cliente = s.cod_cliente 
        WHERE s.cod_poliza <> 'CORTE' AND s.f_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\'"; */

          $consulta = "SELECT s.*, c.telefono_fijo, c.email, s.estado as estado_siniestro 
          FROM (comercial.siniestros as s inner join comercial.clientes as c on c.cod_cliente = s.cod_cliente)";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE s.f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE DE SINIESTROS GENERALES - BASE UNISERSOFT EL $fecha_dia";
            $titulo = "VISTA PREVIA DE SINIESTROS GENERALES (Base Unisersoft). DEL $fecha_dia";
          } else {
            $consulta .= " WHERE s.f_registro >= '$fecha_inicio' and s.f_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE DE SINIESTROS GENERALES - BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE SINIESTROS GENERALES (Base Unisersoft). ENTRE $fecha_inicio Y $fecha_final";
          }

          $consulta .= " and s.cod_poliza <> 'CORTE'";

          $result = mysqli_query($con, $consulta);
          //echo $consulta;
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>Nro.</th>
                  <th>Cod. Siniestro</th>
                  <th>Cod. Póliza</th>
                  <th>Cod. Cliente</th>
                  <th>Sucursal</th>
                  <th>Departamento Siniestro</th>
                  <th>Tipo Póliza</th>
                  <th>Ramo</th>
                  <th>Cobertura</th>
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
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                  $estado = $row['estado_siniestro'];
                  $cod_poliza = $row['cod_poliza'];
                  $id_item = $row['id_item'];
                  $query_1 = "SELECT subtipo_cartera,tipo_cartera FROM comercial.pol_reporte_comercial WHERE cod_poliza='$cod_poliza'";
                  $sql1 = $con->query($query_1);
                  $f1 = $sql1->fetch_assoc();
                  $sector = $f1['tipo_cartera'];
                  $subtipo_cartera = $f1['subtipo_cartera'];

                  $query_1 = "SELECT `14`,`15`,`13`,`21` FROM comercial.items WHERE id_registro='$id_item' AND `0` LIKE 'CE%'";
                  $sql1 = $con->query($query_1);
                  $f1 = $sql1->fetch_assoc();
                  $clase = $f1['14'];
                  $marca = $f1['15'];
                  $placa = $f1['13'];
                  $uso = $f1['21'];

                  $detalle_siniestros = $row['detalle_siniestro'];
                  $cod_siniestro = $row['cod_siniestro'];

                  if ($detalle_siniestros == '') {
                    $query_narracion = "SELECT narracion FROM comercial.siniestro_detalles WHERE cod_siniestro='$cod_siniestro'";
                    $sql_narracion = $con->query($query_narracion);
                    $f2 = $sql_narracion->fetch_assoc();
                    $detalle_siniestros = $f2['narracion'];
                  }

                ?>
                  <tr>
                    <td><?php echo $row['id_sin']; ?></td>
                    <td><?php echo $row['cod_siniestro']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['sucursal']; ?></td>
                    <td><?php echo $row['dep_siniestro']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['ramo_general']; ?></td>
                    <td><?php echo $row['cobertura']; ?></td>
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
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'estadistica':
          $msj_log = "DATOS DE PRODUCCION";
          /* $consulta_log = "SELECT * FROM comercial.siniestros as s inner join comercial.clientes as c on c.cod_cliente = s.cod_cliente 
          WHERE s.cod_poliza <> 'CORTE' AND s.f_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\'"; */

          $consulta = "SELECT cp.id_calculo_prima,  rc.cod_cliente, rc.cod_poliza, rc.regional, rc.tomador, cp.asegurado, rc.tipo_poliza, rc.ramo,
          cp.movimiento, rc.nro_anexo, ROUND(cp.valor_asegurado,2), ROUND(cp.prima_credito,2), ROUND(cp.prima_contado,2), ROUND(cp.prima_neta,2), rc.moneda,
          cp.fecha_emision, cp.fecha_inicio, cp.fecha_fin, cp.dias_transcurridos, rc.tipo_cartera, rc.subtipo_cartera,
          cp.tipo_pago, rc.intermediario, cp.observaciones, ROUND(cp.itf,2), ROUND(cp.it,2), ROUND(cp.iva,2), ROUND(cp.aba,2), ROUND(cp.fpa,2), 
          ROUND(cp.aps,2), ROUND(cp.com_compania,2), ROUND(cp.prima_riesgo,2), ROUND(cp.itf_remesa,2), ROUND(cp.iue_remesa,2), ROUND(cp.com_banca,2),
          ROUND(cp.prima_adicional,2), ROUND(cp.derechos_emision,2), ROUND(cp.com_intermediario_gnv,2),
          ROUND(cp.com_intermediario,2), ROUND(cp.prima_riesgo_tecnica,2),cp.id_calculo_prima,cp.cod_control,cp.cod_unico,rc.asi_vial,cp.hora_ini,cp.hora_fin
          FROM comercial.pol_reporte_comercial AS rc INNER JOIN comercial.pol_calculo_prima AS cp                            
          ON  cp.cod_poliza = rc.cod_poliza";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE cp.f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE DE DATOS DE PRODUCCION - BASE UNISERSOFT EL $fecha_dia";
            $titulo = "VISTA PREVIA DE DATOS DE PRODUCCION (Base Unisersoft). DEL $fecha_dia";
          } else {
            $consulta .= " WHERE cp.f_registro >= '$fecha_inicio' and cp.f_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE DE DATOS DE PRODUCCION - BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE DATOS DE PRODUCCION (Base Unisersoft). ENTRE $fecha_inicio Y $fecha_final";
          }

          $consulta .= " ORDER BY cp.f_registro ASC";
          $msj_log = "REPORTE ESTADISTICA";

          //  echo $consulta;
          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>ID REPORTE </th>
                  <th>COD UNICO </th>
                  <th>COD. CLIENTE </th>
                  <th>COD. PÓLIZA </th>
                  <th>REGIONAL </th>
                  <th>TOMADRO </th>
                  <th>ASEGURADO </th>
                  <th>ASISTENCIA VIAL </th>
                  <th>TIPO POLIZA </th>
                  <th>RAMO </th>
                  <th>MOVIMIENTO </th>
                  <th>NRO. ANEXO</th>
                  <th>VALOR ASEGURADO </th>
                  <th>PRIMA CREDITO</th>
                  <th>PRIMA CONTADO</th>
                  <th>PRIMA NETA</th>
                  <th>MONEDA </th>
                  <th>FECHA EMISION </th>
                  <th>FECHA INICIO</th>
                  <th>HORA INICIO</th>
                  <th>FECHA FIN</th>
                  <th>HORA FIN</th>
                  <th>DIAS TRANSCURRIDOS </th>
                  <th>SECTOR</th>
                  <th>TIPO CARTERA</th>
                  <th>SUBTIPO CARTERA</th>
                  <th>INTERMEDIARIO</th>
                  <th>TIPO PAGO</th>
                  <th>OBSERVACIONES </th>
                  <th>ITF </th>
                  <th>IT</th>
                  <th>IVA </th>
                  <th>ABA</th>
                  <th>FPA</th>
                  <th>APS</th>
                  <th>COMISIÓN COMPAÑIA </th>
                  <th>PRIMA RIESGO </th>
                  <th>ITF REMESA</th>
                  <th>IUE REMSA</th>
                  <th>COMISIÓN BANCA </th>
                  <th>PRIMA ADICIONAL</th>
                  <th>DERECHOS EMISION</th>
                  <th>COMISIÓN INTERMEDIARIO GNV</th>
                  <th>COMISIÓN INTERMEDIARIO</th>
                  <th>PRIMA RIESGO TECNICA</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                  $cod_control = $row['cod_control'] - 1;
                  if ($row['tipo_cartera'] == 'ESTATAL') {
                    $inprime = $row['intermediario'];
                    $inter = "CARTERA DIRECTA";
                  } else {
                    $inprime = "";
                    $inter = $row['intermediario'];
                  }
                  if ($row['prima_neta'] < 0) {
                    $asistencia_vial = 'NO';
                  } else {
                    $asistencia_vial = $row['asi_vial'];
                  }

                ?>
                  <tr>
                    <td><?php echo $row['id_calculo_prima']; ?></td>
                    <td><?php echo $row['cod_unico']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['regional']; ?></td>
                    <td><?php echo $row['tomador']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $asistencia_vial; ?></td>
                    <td><?php echo $row['tipo_poliza']; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['movimiento']; ?></td>
                    <td><?php echo $cod_control; ?></td>
                    <td><?php echo $row['ROUND(cp.valor_asegurado,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.prima_credito,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.prima_contado,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.prima_neta,2)']; ?></td>
                    <td><?php echo $row['moneda']; ?></td>
                    <td><?php echo $row['fecha_emision']; ?></td>
                    <td><?php echo $row['fecha_inicio']; ?></td>
                    <td><?php echo $row['hora_ini']; ?></td>
                    <td><?php echo $row['fecha_fin']; ?></td>
                    <td><?php echo $row['hora_fin']; ?></td>
                    <td><?php echo $row['dias_transcurridos']; ?></td>
                    <td><?php echo $row['tipo_cartera']; ?></td>
                    <td><?php echo $row['subtipo_cartera']; ?></td>
                    <td><?php echo $inprime ?></td>
                    <td><?php echo $inter ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                    <td><?php echo $row['ROUND(cp.itf,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.it,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.iva,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.aba,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.fpa,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.aps,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.com_compania,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.prima_riesgo,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.itf_remesa,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.iue_remesa,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.com_banca,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.prima_adicional,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.derechos_emision,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.com_intermediario_gnv,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.com_intermediario,2)']; ?></td>
                    <td><?php echo $row['ROUND(cp.prima_riesgo_tecnica,2)']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'comercial_clientes':
          $msj_log = "REPORTE CLIENTES UNIBIENES";
          /* $consulta_log = "SELECT * FROM comercial.clientes WHERE fecha_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\'"; */
          $consulta = "SELECT * FROM comercial.clientes";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE fecha_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE CLIENTES UNIBIENES EL $fecha_dia";
            $titulo = "VISTA PREVIA DE REPORTE DE CLIENTES. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE fecha_registro >= '$fecha_inicio' and fecha_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE CLIENTES UNIBIENES ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE REPORTE DE CLIENTES. ENTRE $fecha_inicio Y $fecha_final";
          }
          $consulta .= "and estado <> 'ELIMINADO'";
          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>ID CLIENTE</th>
                  <th>NRO. POLIZA</th>
                  <th>PRIMA CONTADO</th>
                  <th>FECHA VENCIMIENTO PAGO</th>
                  <th>COD. DE CLIENTE</th>
                  <th>ID NUMERO</th>
                  <th>ID NUM</th>
                  <th>PRODUCTO</th>
                  <th>NOMBRES</th>
                  <th>PATERNO</th>
                  <th>MATERNO</th>
                  <th>PERSONA</th>
                  <th>DIRECCIÓN</th>
                  <th>NIT/CI</th>
                  <th>TELÉFONO FIJO</th>
                  <th>CELULAR</th>
                  <th>EMAIL</th>
                  <th>NIT/CI (FACTURA)</th>
                  <th>NOMBRE PAGADOR</th>
                  <th>FECHA REGISTRO</th>
                  <th>DISTRITO</th>
                  <th>ZONA</th>
                  <th>APELLIDO CASADA</th>
                  <th>FECHA NACIMIENTO</th>
                  <th>NACIONALIDAD</th>
                  <th>PAIS DE RESIDENCIA</th>
                  <th>EXTENCIÓN DE CI</th>
                  <th>TIPO DOCUMENTO</th>
                  <th>NIT (NATURAL) </th>
                  <th>ESTADO CIVIL</th>
                  <th>NOMBRE CONYUGE</th>
                  <th>CIUDAD</th>
                  <th>DIRECCION COMERCIAL</th>
                  <th>PROFESION</th>
                  <th>ACTIVIDAD ECONÓMICA</th>
                  <th>CAEDEC</th>
                  <th>LUGAR DE TRABAJO</th>
                  <th>TELÉFONO TRABAJO</th>
                  <th>CARGO</th>
                  <th>INGRESOS</th>
                  <th>FECHA INGRESO LABORAL</th>
                  <th>TIPO DE REFERENCIA</th>
                  <th>NOMBRE REFERENCIA</th>
                  <th>TELEFONO REFERENCIA</th>
                  <th>PEP</th>
                  <th>GENERO</th>
                  <th>CARGO PEP</th>
                  <th>RESIDENCA USA</th>
                  <th>TIPO TRABAJO</th>
                  <th>ABREVIADO</th>
                  <th>MATRICULA</th>
                  <th>CONSTITUCIÓN</th>
                  <th>NOMBRE DE REPRESENTANTE LEGAL</th>
                  <th>APELLIDO DE REPRESENTANTE LEGAL</th>
                  <th>NOMBRE DEL REPONSABLE DE SEGUROS</th>
                  <th>CARGO SEGUROS</th>
                  <th>TIPO PERSONA</th>
                  <th>TIPO PRODUCCIÓN</th>
                  <th>CAPITAL</th>
                  <th>PAGINA</th>
                  <th>LISTA</th>
                  <th>FECHA ESTADO FINANCIERO</th>
                  <th>ESTADO</th>
                  <th>TIPO AGRO</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <tr>
                    <td><?php echo $row['id_cliente']; ?></td>
                    <td><?php echo $row['nro_poliza']; ?></td>
                    <td><?php echo $row['prima_contado']; ?></td>
                    <td><?php echo $row['fecha_vencimiento_pago']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['idnumero']; ?></td>
                    <td><?php echo $row['idnum']; ?></td>
                    <td><?php echo $row['producto']; ?></td>
                    <td><?php echo $row['nombres']; ?></td>
                    <td><?php echo $row['paterno']; ?></td>
                    <td><?php echo $row['materno']; ?></td>
                    <td><?php echo $row['persona']; ?></td>
                    <td><?php echo $row['direccion'] . ' ' . $row['num_domicilio']; ?></td>
                    <td><?php echo $row['nit_ci']; ?></td>
                    <td><?php echo $row['telefono_fijo']; ?></td>
                    <td><?php echo $row['celular']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['nitf_cif']; ?></td>
                    <td><?php echo $row['pagador']; ?></td>
                    <td><?php echo $row['fecha_registro']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>
                    <td><?php echo $row['zona']; ?></td>
                    <td><?php echo $row['ap_casada']; ?></td>
                    <td><?php echo $row['fecha_nacimiento']; ?></td>
                    <td><?php echo $row['nacionalidad']; ?></td>
                    <td><?php echo $row['pais_residencia']; ?></td>
                    <td><?php echo $row['ext_ci']; ?></td>
                    <td><?php echo $row['tipo_documento']; ?></td>
                    <td><?php echo $row['nit_natural']; ?></td>
                    <td><?php echo $row['estado_civil']; ?></td>
                    <td><?php echo $row['nombre_conyuge']; ?></td>
                    <td><?php echo $row['ciudad']; ?></td>
                    <td><?php echo $row['direccion_comercial']; ?></td>
                    <td><?php echo $row['profesion']; ?></td>
                    <td><?php echo $row['act_economica']; ?></td>
                    <td><?php echo $row['caedec']; ?></td>
                    <td><?php echo $row['descripcion_laboral']; ?></td>
                    <td><?php echo $row['telefono_trabajo']; ?></td>
                    <td><?php echo $row['cargo']; ?></td>
                    <td><?php echo $row['ingresos']; ?></td>
                    <td><?php echo $row['fecha_ingreso_laboral']; ?></td>
                    <td><?php echo $row['referencia_cat']; ?></td>
                    <td><?php echo $row['referencia_nombre']; ?></td>
                    <td><?php echo $row['referencia_telefono']; ?></td>
                    <td><?php echo $row['pep']; ?></td>
                    <td><?php echo $row['genero']; ?></td>
                    <td><?php echo $row['cargo_pep']; ?></td>
                    <td><?php echo $row['res_usa']; ?></td>
                    <td><?php echo $row['tipo_trabajo']; ?></td>
                    <td><?php echo $row['abreviado']; ?></td>
                    <td><?php echo $row['matricula']; ?></td>
                    <td><?php echo $row['constitucion']; ?></td>
                    <td><?php echo $row['nom_representante']; ?></td>
                    <td><?php echo $row['ap_representante']; ?></td>
                    <td><?php echo $row['nom_seguros']; ?></td>
                    <td><?php echo $row['cargo_seguros']; ?></td>
                    <td><?php echo $row['tipo_persona']; ?></td>
                    <td><?php echo $row['tipo_produccion']; ?></td>
                    <td><?php echo $row['capital']; ?></td>
                    <td><?php echo $row['pagina']; ?></td>
                    <td><?php echo $row['lista']; ?></td>
                    <td><?php echo $row['f_estado_financiero']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['tipo_agro']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'libro_ventas':
          $msj_log = "REPORTE LIBRO VENTAS UNIBIENES";
          /* $consulta_log = "SELECT * FROM unibienes.libro_ventas WHERE f_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\'"; */
          $consulta = "SELECT * FROM unibienes.libro_ventas";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE LIBRO VENTAS UNIBIENES - BASE UNIBIENES EL $fecha_dia";
            $titulo = "VISTA PREVIA DE LIBRO VENTAS UNIBIENES (Base Unibienes). DEL $fecha_dia";
          } else {
            $consulta .= " WHERE f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE LIBRO VENTAS UNIBIENES - BASE UNIBIENES ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE LIBRO VENTAS UNIBIENES (Base Unibienes). ENTRE $fecha_inicio HASTA EL $fecha_final";
          }
          //echo $consulta;


          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>ID LV</th>
                  <th>FECHA FACTURA</th>
                  <th>FECHA</th>
                  <th>NUMERO FACTURA</th>
                  <th>ID NUM</th>
                  <th>AUTORIZACIÓN</th>
                  <th>ESTADO</th>
                  <th>CI NIT</th>
                  <th>CI NIT IMPORTE</th>
                  <th>NOMBRE RAZON</th>
                  <th>IMPOTE VENTA</th>
                  <th>IMPORTE TASAS</th>
                  <th>EXP. OPER. EXT.</th>
                  <th>VENTAS GRAV.</th>
                  <th>SUB TOTAL</th>
                  <th>DESC. BON.</th>
                  <th>IMP. DEBITI FISCAL</th>
                  <th>DEBITO FISCAL</th>
                  <th>CODIGO CONTROL</th>
                  <th>CONCEPTO</th>
                  <th>NUMERO POLIZA</th>
                  <th>CODIGO CLIENTE</th>
                  <th>FECHA REGISTRO</th>
                  <th>DISTRITO</th>

                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <tr>
                    <td><?php echo $row['id_lv']; ?></td>
                    <td><?php echo $row['fecha_factura']; ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td><?php echo $row['num_factura']; ?></td>
                    <td><?php echo $row['idnum']; ?></td>
                    <td><?php echo $row['autorizacion']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['ci_nit']; ?></td>
                    <td><?php echo $row['ci_nit_imp']; ?></td>
                    <td><?php echo $row['nombre_razon']; ?></td>
                    <td><?php echo $row['importe_venta']; ?></td>
                    <td><?php echo $row['importe_tasas']; ?></td>
                    <td><?php echo $row['exp_oper_ext']; ?></td>
                    <td><?php echo $row['ventas_grav']; ?></td>
                    <td><?php echo $row['sub_total']; ?></td>
                    <td><?php echo $row['desc_bon']; ?></td>
                    <td><?php echo $row['imp_deb_fiscal']; ?></td>
                    <td><?php echo $row['debito_fiscal']; ?></td>
                    <td><?php echo $row['codigo_control']; ?></td>
                    <td><?php echo $row['concepto']; ?></td>
                    <td><?php echo $row['num_poliza']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>

                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'accionistas':
          $msj_log = "REPORTE ACCIONISTAS UNIBIENES";
          /* $consulta_log = "SELECT * FROM unibienes.accionistas WHERE f_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\'"; */
          $consulta = "SELECT * FROM unibienes.accionistas ";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE ACCIONISTAS UNIBIENES - BASE UNIBIENES EL $fecha_dia";
            $titulo = "VISTA PREVIA DE ACCIONISTAS UNIBIENES (Base Unibienes). DEL $fecha_dia";
          } else {
            $consulta .= " WHERE f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE ACCIONISTAS UNIBIENES - BASE UNIBIENES ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE ACCIONISTAS UNIBIENES (Base Unibienes). ENTRE $fecha_inicio HASTA EL $fecha_final";
          }

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>ID ACCIONISTA</th>
                  <th>NOMBRES</th>
                  <th>APELLIDOS</th>
                  <th>CARNET</th>
                  <th>PORCENTAJE</th>
                  <th>NACIONALIDAD</th>
                  <th>TIPO PERSONA</th>
                  <th>ACTIVIDAD</th>
                  <th>DIRECCION</th>
                  <th>FECHA DE REGISTRO</th>
                  <th>CODIGO DE CLIENTE</th>
                  <th>INGRESO</th>

                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <tr>
                    <td><?php echo $row['id_accion']; ?></td>
                    <td><?php echo $row['nombres']; ?></td>
                    <td><?php echo $row['apellidos']; ?></td>
                    <td><?php echo $row['carnet']; ?></td>
                    <td><?php echo $row['porcentaje']; ?></td>
                    <td><?php echo $row['nacionalidad']; ?></td>
                    <td><?php echo $row['tipo_persona']; ?></td>
                    <td><?php echo $row['actividad']; ?></td>
                    <td><?php echo $row['direccion']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['ingresos']; ?></td>

                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'accionistas_unisersoft':
          //$msj_log = "REPORTE ACCIONISTAS UNIBIENES";
          /* $consulta_log = "SELECT * FROM unibienes.accionistas WHERE f_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\'"; */
          $consulta = "SELECT * FROM comercial.clientes_accionistas ";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE ACCIONISTAS UNISERSOFT - BASE UNISERSOFT EL $fecha_dia";
            $titulo = "VISTA PREVIA DE ACCIONISTAS UNISERSOFT (Base UNISERSOFT). DEL $fecha_dia";
          } else {
            $consulta .= " WHERE f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE ACCIONISTAS UNISERSOFT - BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE ACCIONISTAS UNISERSOFT (Base UNISERSOFT). ENTRE $fecha_inicio HASTA EL $fecha_final";
          }

          $consulta .= " and estado='ACTIVO' AND ac_participacion > 0 ";

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>ID ACCIONISTA</th>
                  <th>NOMBRES COMPLETO</th>
                  <th>CARNET</th>
                  <th>PORCENTAJE</th>
                  <th>NACIONALIDAD O NRO. REGISTRO DE COMERCIO</th>
                  <th>DIRECCION LEGAL</th>
                  <th>ACTIVIDAD ECONOMICA</th>
                  <th>FECHA DE REGISTRO</th>
                  <th>CODIGO DE CLIENTE</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <tr>
                    <td><?php echo $row['id_accionista']; ?></td>
                    <td><?php echo $row['ac_nombre']; ?></td>
                    <td><?php echo $row['ac_ci_nit']; ?></td>
                    <td><?php echo $row['ac_participacion']; ?></td>
                    <td><?php echo $row['ac_dato']; ?></td>
                    <td><?php echo $row['ac_direccion']; ?></td>
                    <td><?php echo $row['ac_actividad_eco']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['cod_cliente']; ?></td>

                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'clientes_pep':
          //SEGUN REUNION DEL 2022-09-15 Se toma como dato referencia para reporte de produccion la fecha de inicio de vigencia de poliza
          $msj_log = "REPORTE CLIENTES PEP";
          /* $consulta_log = "SELECT c.cod_cliente, concat_ws(' ',c.nombres,c.paterno,c.materno) AS completo, c.pep, c.fecha_registro, m.observaciones,m.comentario,
            m.f_registro FROM clientes as c inner join clientes_movimientos as m on c.cod_cliente=m.cod_cliente             
            WHERE (c.pep = 'SI' and c.fecha_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\') 
            or (m.comentario like '%pep%' and m.f_registro BETWEEN \'$fecha_inicio\' and \'$fecha_final\')"; */
          $consulta = "SELECT c.cod_cliente, concat_ws(' ',c.nombres,c.paterno,c.materno) AS completo, c.pep, c.fecha_registro, m.observaciones,m.comentario,
            m.f_registro FROM comercial.clientes as c inner join comercial.clientes_movimientos as m on c.cod_cliente=m.cod_cliente             
            ";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE (c.pep = 'SI' and c.fecha_registro like '%$fecha_dia%') 
                              or (m.comentario like '%pep%' and m.f_registro like '%$fecha_dia%')";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE CLIENTES PEP - BASE UNISERSOFT EL $fecha_dia";
            $titulo = "VISTA PREVIA DE AUTORIZACIONES PEP - UNISERSOFT. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE (c.pep = 'SI' and c.fecha_registro >= '$fecha_inicio' and c.fecha_registro <= '$fecha_final') 
            or (m.comentario like '%pep%' and m.f_registro >= '$fecha_inicio' and m.f_registro <= '$fecha_final')";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE CLIENTES PEP - BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE AUTORIZACIONES PEP - UNISERSOFT. ENTRE $fecha_inicio HASTA EL $fecha_final";
          }

          //echo $consulta;
          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>CODIGO CLIENTE </th>
                  <th>NOMBRE COMPLETO </th>
                  <th>PEP </th>
                  <th>FECHA REGISTRO </th>
                  <th>OBSERVACIONES </th>
                  <th>COMENTARIO </th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <tr>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['completo']; ?></td>
                    <td><?php echo $row['pep']; ?></td>
                    <td><?php echo $row['fecha_registro']; ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                    <td><?php echo $row['comentario']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'automovil_unisersoft':
          //SEGUN REUNION DEL 2022-09-15 Se toma como dato referencia para reporte de produccion la fecha de inicio de vigencia de poliza

          $consulta = "SELECT i.*, pcc.tipo_pago, pcc.intermediario FROM comercial.items as i INNER JOIN comercial.pol_calculo_prima as pcc ON i.cod_cotizacion=pcc.cod_cotizacion";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE `7` like '%$fecha_dia%' AND `0` LIKE 'CE%'  ";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE DE AUTOMOVIL - BASE UNISERSOFT EL $fecha_dia";
            $titulo = "VISTA PREVIA DE AUTOMOVILES - UNISERSOFT. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE `7` >= '$fecha_inicio' and `7` <= '$fecha_final' AND `0` LIKE 'CE%'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE DE AUTOMOVIL - BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE AUTOMOVILES - UNISERSOFT. ENTRE $fecha_inicio HASTA EL $fecha_final";
          }

          //echo $consulta;
          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>COD. CLIENTE </th>
                  <th>NOMBRE COMPLETO ASEGURADO </th>
                  <th>CERTIFICADO </th>
                  <th>PÓLIZA </th>
                  <th>CLASE </th>
                  <th>PLACA </th>
                  <th>MARCA </th>
                  <th>AÑO </th>
                  <th>COLOR </th>
                  <th>MODELO </th>
                  <th>TRACCION </th>
                  <th>USO </th>
                  <th>DISTRITO </th>
                  <th>PLAZA </th>
                  <th>PLAZAS </th>
                  <th>PRIMA TOTAL </th>
                  <th>CHASIS </th>
                  <th>MOTOR </th>
                  <th>VALOR ASEGURADO </th>
                  <th>INTERMEDIADIOR </th>
                  <th>TASA PRIMA </th>
                  <th>TIPO DE PAGO </th>
                  <th>INICIO DE VIGENCIA </th>
                  <th>FIN DE VIGENCIA </th>
                  <th>ESTADO </th>
                  <th>FECHA DE REGISTRO </th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                  $tipo_pago = $row['tipo_pago'];
                  if ($tipo_pago == 'CONTADO') {
                    $prima_total = $row['4'];
                  } else {
                    $prima_total = $row['5'];
                  }
                ?>
                  <tr>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['9']; ?></td>
                    <td><?php echo $row['0']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['14']; ?></td>
                    <td><?php echo $row['13']; ?></td>
                    <td><?php echo $row['15']; ?></td>
                    <td><?php echo $row['18']; ?></td>
                    <td><?php echo $row['17']; ?></td>
                    <td><?php echo $row['16']; ?></td>
                    <td><?php echo $row['19']; ?></td>
                    <td><?php echo $row['21']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>
                    <td><?php echo $row['25']; ?></td>
                    <td><?php echo $row['22']; ?></td>
                    <td><?php echo number_format($row['prima_total'], 2); ?></td>
                    <td><?php echo $row['23']; ?></td>
                    <td><?php echo $row['24']; ?></td>
                    <td><?php echo number_format($row['3'], 2); ?></td>
                    <td><?php echo $row['intermediario']; ?></td>
                    <td><?php echo number_format($row['6'], 2); ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['7']; ?></td>
                    <td><?php echo $row['8']; ?></td>
                    <td><?php echo $row['2']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'sinistros_pendientes_unisersoft_automovil':
          //SEGUN REUNION DEL 2022-09-15 Se toma como dato referencia para reporte de produccion la fecha de inicio de vigencia de poliza

          $consulta = "SELECT s.* , sd.*
           FROM comercial.siniestros as s INNER JOIN comercial.siniestro_detalles as sd on s.cod_siniestro=sd.cod_siniestro 
                LEFT JOIN comercial.items as i ON s.id_item=i.id_registro";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE s.f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE SINIESTROS PENDIENTES AUTOMOTORES BASE UNISERSOFT EL $fecha_dia";
            $titulo = "VISTA PREVIA DE SINISTROS PENDIENTES AUTOMOTORES - UNISERSOFT. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE s.f_registro >= '$fecha_inicio' and s.f_registro <= '$fecha_final' AND `0` LIKE 'CE%'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE SINIESTROS PENDIENTES AUTOMOTORES BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE SINISTROS PENDIENTES AUTOMOTORES - UNISERSOFT. DESDE $fecha_inicio HASTA $fecha_final";
          }

          $consulta .= " GROUP BY s.cod_siniestro";
          //echo $consulta;

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>Nro. Sinistro</th>
                  <th>Fecha Sinistros</th>
                  <th>Fecha Registro Siniestro</th>
                  <th>Fecha Denuncia</th>
                  <th>Departamento Ocurrencia Siniestro</th>
                  <th>Sucursal</th>
                  <th>Asegurado</th>
                  <th>Telefono</th>
                  <th>Correo</th>
                  <th>Clase</th>
                  <th>Marca</th>
                  <th>Placa</th>
                  <th>Uso Vehiculo</th>
                  <th>Detalle Siniestro</th>
                  <th>Nro. Poliza</th>
                  <th>Inicio Vigencia</th>
                  <th>Fin Vigencia</th>
                  <th>Cobertura</th>
                  <th>Monto Reserva (USD)</th>
                  <th>Estado</th>
                  <th>Inspector</th>
                  <th>Tipo de Cliente</th>
                  <th>Tipo de Cartera</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                  $cod_poliza = $row['cod_poliza'];
                  $cod_cliente = $row['cod_cliente'];
                  $num_siniestro = $row['num_siniestro'];
                  $id_item = $row['id_item'];
                  $base = $row['base'];
                  $sele2 = $con->query("SELECT telefono_fijo,email from comercial.clientes where cod_cliente = '$cod_cliente' limit 1");
                  $filas2 = $sele2->fetch_assoc();
                  $telefono_fijo = $filas2['telefono_fijo'];
                  $email = $filas2['email'];

                  if ($base == 'automovil') {
                    $sele3 = $con->query("SELECT clase,marca from unibienes.automovil where id_automovil = '$item' limit 1");
                    $filas3 = $sele3->fetch_assoc();
                    $clase = $filas3['clase'];
                    $marca = $filas3['marca'];
                    $uso = $filas3['uso'];
                  } else {
                    $sele3 = $con->query("SELECT  `14` AS clase,`15` as marca,`21` as uso from comercial.items where id_registro = '$item' AND `0` LIKE 'CE%' limit 1");
                    $filas3 = $sele3->fetch_assoc();
                    $clase = $filas3['clase'];
                    $marca = $filas3['marca'];
                    $uso = $filas3['uso'];
                  }

                  $query_1 = "SELECT tipo_cartera, subtipo_cartera FROM comercial.pol_reporte_comercial WHERE cod_poliza='$cod_polizas'";
                  $sql1 = $con->query($query_1);
                  $f1 = $sql1->fetch_assoc();
                  $sector = $f1['tipo_cartera'];
                  $subtipo_cartera = $f1['subtipo_cartera'];
                ?>
                  <tr>
                    <td><?php echo $row['cod_siniestro']; ?></td>
                    <td><?php echo $row['f_siniestro']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['f_denuncia']; ?></td>
                    <td><?php echo $row['lugar_siniestro']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $telefono_fijo; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $clase; ?></td>
                    <td><?php echo $marca; ?></td>
                    <td><?php echo $row['placa']; ?></td>
                    <td><?php echo $uso; ?></td>
                    <td><?php echo $row['narracion']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['inicio_v']; ?></td>
                    <td><?php echo $row['fin_v']; ?></td>
                    <td><?php echo $row['cobertura']; ?></td>
                    <td><?php echo $row['monto_reserva']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['inspector']; ?></td>
                    <td><?php echo $sector; ?></td>
                    <td><?php echo $subtipo_cartera; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'sinistros_pendientes_unisersoft_otros':
          //SEGUN REUNION DEL 2022-09-15 Se toma como dato referencia para reporte de produccion la fecha de inicio de vigencia de poliza

          $consulta = "SELECT * FROM comercial.siniestros";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE f_registro like '%$fecha_dia%'";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE SINIESTROS PENDIENTES OTROS RAMOS BASE UNISERSOFT EL $fecha_dia";
            $titulo = "VISTA PREVIA DE SINISTROS PENDIENTES OTROS RAMOS - UNISERSOFT. DEL $fecha_dia";
          } else {
            $consulta .= " WHERE f_registro >= '$fecha_inicio' and f_registro <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE SINIESTROS PENDIENTES OTROS RAMOS BASE UNISERSOFT ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE SINISTROS PENDIENTES OTROS RAMOS - UNISERSOFT. DESDE $fecha_inicio HASTA $fecha_final";
          }

          $consulta .= " AND ramo_general <> 'AUTOMOTORES' and estado='PENDIENTE'";
          //echo $consulta;

          $result = mysqli_query($con, $consulta);
        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>Nro. Sinistro</th>
                  <th>Fecha Sinistros</th>
                  <th>Fecha Registro Siniestro</th>
                  <th>Fecha Denuncia</th>
                  <th>Departamento Ocurrencia Siniestro</th>
                  <th>Sucursal</th>
                  <th>Asegurado</th>
                  <th>Telefono</th>
                  <th>Correo</th>
                  <th>Ramo</th>
                  <th>Detalle Siniestro</th>
                  <th>Nro. Poliza</th>
                  <th>Inicio Vigencia</th>
                  <th>Fin Vigencia</th>
                  <th>Cobertura</th>
                  <th>Monto Reserva (USD)</th>
                  <th>Franquicia %</th>
                  <th>Franquicia (USD)</th>
                  <th>Coserguro %</th>
                  <th>Recupero</th>
                  <th>Estado</th>
                  <th>Observaciones</th>
                  <th>Inspector</th>
                  <th>Tipo de Cliente</th>
                  <th>Tipo de Cartera</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                  $cod_cliente = $row['cod_cliente'];
                  $cod_poliza = $row['cod_poliza'];
                  $sele2 = $con->query("SELECT telefono_fijo,email from comercial.clientes where cod_cliente = '$cod_cliente' limit 1");
                  $filas2 = $sele2->fetch_assoc();
                  $telefono_fijo = $filas2['telefono_fijo'];
                  $email = $filas2['email'];

                  $query_1 = "SELECT tipo_cartera, subtipo_cartera FROM comercial.pol_reporte_comercial WHERE cod_poliza='$cod_poliza'";
                  $sql1 = $con->query($query_1);
                  $f1 = $sql1->fetch_assoc();
                  $sector = $f1['tipo_cartera'];
                  $subtipo_cartera = $f1['subtipo_cartera'];
                ?>
                  <tr>
                    <td><?php echo $row['cod_siniestro']; ?></td>
                    <td><?php echo $row['f_siniestro']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                    <td><?php echo $row['f_denuncia']; ?></td>
                    <td><?php echo $row['dep_siniestro']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>
                    <td><?php echo $row['asegurado']; ?></td>
                    <td><?php echo $telefono_fijo; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $row['ramo']; ?></td>
                    <td><?php echo $row['detalle_siniestro']; ?></td>
                    <td><?php echo $row['cod_poliza']; ?></td>
                    <td><?php echo $row['inicio_v']; ?></td>
                    <td><?php echo $row['fin_v']; ?></td>
                    <td><?php echo utf8_decode($row['cobertura']); ?></td>
                    <td><?php echo round($row['monto_reserva'], 2); ?></td>
                    <td><?php echo round($row['franquicia_p'], 2); ?></td>
                    <td><?php echo round($row['franquicia_n'], 2); ?></td>
                    <td><?php echo round($row['coseguro'], 2); ?></td>
                    <td><?php echo round($row['recupero'], 2); ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['observaciones']; ?></td>
                    <td><?php echo $row['inspector']; ?></td>
                    <td><?php echo $sector; ?></td>
                    <td><?php echo $subtipo_cartera; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
        <?php
          break;
        case 'automovil':
          //Creacion de reportes automovil
          //$msj_log = "REPORTE AUTOMOVILES REGISTRADOS DESDE INICIO DE VIGENCIA";
          /* $consulta_log = "SELECT c.cod_cliente,concat_ws(' ',c.nombres,  c.paterno,  c.materno, c.ap_casada) AS completo,  a.`certificado`,
            a.`nro_poliza`, a.`clase`, a.`placa`, a.`marca`,  a.`ano`, a.`color`, a.`modelo`,
            a.`traccion`, a.`uso`, a.`distrito`, a.`plaza`, a.`plazas`, a.`prima_total`, a.`chasis`, a.`motor`, a.`auto_cap_aseg` AS valor_asegurado, a.`intermediario`,
            a.`tasa_prima`, a.`tipo_pago`, a.`inicio_vigencia`, a.`final_vigencia`, a.`fechavencimiento`, a.`com_inter`, a.`estado`, a.`f_registro` 
            FROM  `automovil` AS a INNER JOIN unibienes.clientes AS c ON  a.cod_cliente = c.cod_cliente WHERE
            a.`inicio_vigencia` BETWEEN \'$fecha_inicio\' and \'$fecha_final\ AND( a.estado IN ('NUEVO','RENOVACION','AMPLIACION','INCLUCION','INCLUSIÓN','INCLUSION','NUEVO INCLUSION','NO PAGADO',' '))";
           */
          $consulta = "SELECT c.cod_cliente,concat_ws(' ',c.nombres,  c.paterno,  c.materno,  c.ap_casada) AS completo,  a.`certificado`, a.`nro_poliza`, a.`clase`, a.`placa`, a.`marca`,  a.`ano`, a.`color`, a.`modelo`,
            a.`traccion`, a.`uso`, a.`distrito`, a.`plaza`, a.`plazas`, a.`prima_total`, a.`chasis`, a.`motor`, a.`auto_cap_aseg` AS valor_asegurado, a.`intermediario`,
            a.`tasa_prima`, a.`tipo_pago`, a.`inicio_vigencia`, a.`final_vigencia`, a.`fechavencimiento`, a.`com_inter`, a.`estado`, a.`f_registro` 
            FROM  unibienes.`automovil` AS a INNER JOIN unibienes.clientes AS c 
            ON  a.cod_cliente = c.cod_cliente ";

          if (!isset($_POST['cb_lapso'])) {
            $consulta .= " WHERE a.`inicio_vigencia` like '%$fecha_dia%' ";
            $fecha_aux = $fecha_dia;
            $msj_log = "REPORTE AUTOMOVILES REGISTRADOS DESDE INICIO DE VIGENCIA - BASE UNIBIENES EL $fecha_dia";
            $titulo = "VISTA PREVIA DE REPORTE AUTOMOVIL (Inicio de vigencia) - UNIBIENES - DEL $fecha_dia";
          } else {
            $consulta .= " WHERE a.`inicio_vigencia`>= '$fecha_inicio' and a.`inicio_vigencia` <= '$fecha_final'";
            $fecha_aux = $fecha_final;
            $msj_log = "REPORTE AUTOMOVILES REGISTRADOS DESDE INICIO DE VIGENCIA - BASE UNIBIENES ENTRE $fecha_inicio Y $fecha_final";
            $titulo = "VISTA PREVIA DE REPORTE AUTOMOVIL (Inicio de vigencia) - UNIBIENES - ENTRE $fecha_inicio HASTA EL $fecha_final";
          }

          $consulta .= "AND(a.estado IN ('NUEVO','RENOVACION','AMPLIACION','INCLUCION','INCLUSIÓN','INCLUSION','NUEVO INCLUSION','NO PAGADO',' '))";

          //echo $consulta;
          $result = mysqli_query($con, $consulta);

        ?>
          <h2 align="center"><?= $titulo ?> </h2>
          <br>
          <div id="datos_reportes" class="table-responsive table">
            <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
              <thead>
                <tr class='text-center'>
                  <th>COD. CLIENTE </th>
                  <th>NOMBRE COMPLETO CLIENTE </th>
                  <th>CERTIFICADO </th>
                  <th>PÓLIZA </th>
                  <th>CLASE </th>
                  <th>PLACA </th>
                  <th>MARCA </th>
                  <th>AÑO </th>
                  <th>COLOR </th>
                  <th>MODELO </th>
                  <th>TRACCION </th>
                  <th>USO </th>
                  <th>DISTRITO </th>
                  <th>PLAZA </th>
                  <th>PLAZAS </th>
                  <th>PRIMA TOTAL </th>
                  <th>CHASIS </th>
                  <th>MOTOR </th>
                  <th>VALOR ASEGURADO </th>
                  <th>INTERMEDIADIOR </th>
                  <th>TASA PRIMA </th>
                  <th>TIPO DE PAGO </th>
                  <th>INICIO DE VIGENCIA </th>
                  <th>FIN DE VIGENCIA </th>
                  <th>FECHA VENCIMIENTO </th>
                  <th>ESTADO </th>
                  <th>FECHA DE REGISTRO </th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                  <tr>
                    <td><?php echo $row['cod_cliente']; ?></td>
                    <td><?php echo $row['completo']; ?></td>
                    <td><?php echo $row['certificado']; ?></td>
                    <td><?php echo $row['nro_poliza']; ?></td>
                    <td><?php echo $row['clase']; ?></td>
                    <td><?php echo $row['placa']; ?></td>
                    <td><?php echo $row['marca']; ?></td>
                    <td><?php echo $row['ano']; ?></td>
                    <td><?php echo $row['color']; ?></td>
                    <td><?php echo $row['modelo']; ?></td>
                    <td><?php echo $row['traccion']; ?></td>
                    <td><?php echo $row['uso']; ?></td>
                    <td><?php echo $row['distrito']; ?></td>
                    <td><?php echo $row['plaza']; ?></td>
                    <td><?php echo $row['plazas']; ?></td>
                    <td><?php echo number_format($row['prima_total'], 2); ?></td>
                    <td><?php echo $row['chasis']; ?></td>
                    <td><?php echo $row['motor']; ?></td>
                    <td><?php echo number_format($row['valor_asegurado'], 2); ?></td>
                    <td><?php echo $row['intermediario']; ?></td>
                    <td><?php echo number_format($row['tasa_prima'], 2); ?></td>
                    <td><?php echo $row['tipo_pago']; ?></td>
                    <td><?php echo $row['inicio_vigencia']; ?></td>
                    <td><?php echo $row['final_vigencia']; ?></td>
                    <td><?php echo $row['fechavencimiento']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                    <td><?php echo $row['f_registro']; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <?php echo $script_tabla; ?>
            </table>
          </div>
      <?php
          break;
        case 'emision_produccion':
          include 'reports/emision/produccion_emision.php';
          break;

        case 'sipof':
          include "reports/cauciones/sipof.php";
          break;
        case 'produccion_item':
          include "reports/comercial/items.php";
          break;
        case 'comercial_clientes_usuario':
          include "reports/cumplimiento/comercial_clientes_usuario.php";
          break;
        case 'siniestro_tc_log':
          include "reports/auditoria/siniestros_trabajo_compra_log.php";
          break;
        case 'log_primas_cobranzas':
          include "reports/auditoria/log_primas_cobranzas.php";
          break;
        case 'cumulo_proveedor':
          include "reports/cumplimiento/cproveedor.php";
          break;
        case 'cumulo_poliza':
          include "reports/cumplimiento/cpolizas.php";
          break;
        case 'cumulo_ramo':
          include "reports/cumplimiento/cramo.php";
          break;
        case 'sinistros_liquidados_au_unisersoft_completo':
          include "reports/siniestros/sinistros_liquidados_au_unisersoft_completo.php";
          break;
        case 'sinistros_liquidados_otros_completo':
          include "reports/siniestros/sinistros_liquidados_otros_completo.php";
          break;
        case 'comercial_clientes_actualizado':
          include "reports/comercial/clientes_actualizado.php";
          break;
        case 'comercial_clientes_actualizado_usuario':
          include "reports/comercial/clientes_actualizado_usuario.php";
          break;
        case 'log_monto_reserva':
          include "reports/auditoria/log_monto_reserva.php";
          break;
        case 'log_monto_reserva_ac':
          include "reports/auditoria/log_monto_reserva_ac.php";
          break;
        case 'log_monto_reserva_ac_completo':
          include "reports/auditoria/log_monto_reserva_ac_completo.php";
          break;
        case 'correspodencia_cite_hoja_log':
          include "reports/auditoria/hoja_ruta_log.php";
          break;
        case 'correspodencia_cites':
          include "reports/auditoria/cites.php";
          break;
        case 'log_clientes':
          include "reports/cumplimiento/clientes_logs.php";
          break;
        case 'pagos_anticipados':
          include "reports/siniestros/pagos_anticipados.php";
          break;
        case 'pagos_anticipados_ot':
          include "reports/siniestros/pagos_anticipados_ot.php";
          break;
        case 'reporte_comercial_usd_t':
          include "reports/emision/reporte_comercial_usd.php";
          break;
        case 'primas_cobranza_origen':
          include "reports/cobranzas/primas_cobranza_origen.php";
          break;
        case 'producion_item':
          include "reports/emision/produccion_items.php";
          break;
        case 'siniestros_liquidados_au':
          include "reports/siniestros/siniestros_liquidados_au.php";
          break;
        case 'siniestros_liquidados_au_unisersoft_detalle':
          include "reports/siniestros/siniestros_liquidados_au_unisersoft_detalle.php";
          break;
        case 'siniestros_liquidados_otros_unisersoft_detalle':
          include "reports/siniestros/siniestros_liquidados_otros_unisersoft_detalle.php";
          break;
        case 'siniestros_liquidados_otros':
          include "reports/siniestros/siniestros_liguidados_otros.php";
          break;
        case 'siniestro_ot_op':
          include "reports/siniestros/siniestro_ot_op.php";
          break;
      }

      $inserta_log = "INSERT INTO reportes.log_reportes (`usuario`, `distrito`, `f_registro`,`movimiento`, `host`, `sentencia`) 
  VALUES ('$usuario', '$distrito', '$hoy', '$msj_log', '$host','$consulta_log')";
      $result2 = mysqli_query($con, $inserta_log);

      ?>
    </div>
  </div>
  <!-- /page content -->
</div>



<!-- datatables con bootstrap -->

<?php include "footer.php";
$con->close();
?>