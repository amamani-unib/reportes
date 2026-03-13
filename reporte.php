<?php
$title = "Tickets | ";
include "head.php";
include "sidebar.php";

include "config/config.php";
$con->query("SET NAMES 'utf8'");
$usuario = $_SESSION["usuario"];
$tipo_usuario = $_SESSION["usuario_cargo"];
$distrito = $_SESSION["distrito"];
$nombre = $_SESSION["nombre"];
//$intermediario=$inter['intermediario'];
?>

<div class="container-fluit" style="height: 100% ;">
  <div class="right_col" role="main">
    <div class="page-title">
      <div class="clearfix"></div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Reportes</h2>
            <div class="clearfix"></div>
          </div>
          <div class="container">
            <?php
            if (
              $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'GERENTE COMERCIAL' or $tipo_usuario == 'COBRANZA'
              or $tipo_usuario == 'JEFE COBRANZA' or $tipo_usuario == 'JEFE EMISION' or $tipo_usuario == 'ESTADISTICA' or $tipo_usuario == 'ESTATAL'
              or $tipo_usuario == 'UIF' or $tipo_usuario == 'LIQUIDADOR' or $tipo_usuario == 'SECRETARIA' or $tipo_usuario == 'RECEPCIONISTA'
              or $tipo_usuario == 'INSPECTOR' or $tipo_usuario == 'JEFE COMERCIAL' or $tipo_usuario == 'GNAF' or $tipo_usuario == 'AUDITORIA'
              or $tipo_usuario == 'COMERCIAL' or $tipo_usuario == 'EMISION' or $tipo_usuario == 'GERENTE RECLAMOS' or $tipo_usuario == 'JEFE RECLAMOS'
              or $tipo_usuario == 'COTIZADOR RECLAMOS'  or $tipo_usuario == 'REASEGURO'
            ) {
            ?>
              <!-- REPORTES--->
              <form class="form-horizontal" action="generar_reporte.php" method="POST" id="formulario">
                <div class="form-group">
                  <div class="col-lg-6">
                    <div class="input-group">
                      <label for='repo' class='form-label'>REPORTES</label>
                      <select name="repo" id="repo" class="form-control" required onchange="ver_div();">
                        <option value="" hidden>SELECIONAR REPORTE</option>
                        <?php
                        if ($usuario == 'saliaga' or $usuario == 'ajacobs' or $usuario == 'cninachoque' or $usuario == 'mperez') {
                        ?>
                          <option value="sipof">Reporte de Cauciones</option>
                          <option value="reporte_comercial2">Producción del Sistema Unisersoft</option>
                          <option value="reporte_comercial">Reporte Comercial - Sistema Unibienes</option>
                          <?php
                        } else {
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL') {
                          ?>
                            <option value="pxclife">Primas por cobrar - Unibienes</option>
                            <option value="sinistros_liquidados_au_unisersoft_completo">Siniestros liquidados automovil - Sistema Unisersoft Completo</option>
                            <option value="sinistros_liquidados_otros_completo">Siniestros liquidados otros - Sistema Unisersoft Completo</option>
                            <option value="comercial_clientes_usuario">Clientes - antiguo</option>
                            <option value="log_monto_reserva">Registro siniestros - Monto de reserva - Logs</option>
                            <option value="reporte_comercial2">Producción del Sistema Unisersoft</option>
                            <option value="primas_cobranzas">Primas por cobrar - Unisersoft</option>
                            <option value="producion_item">Produccion - Items</option>
                            <option value="log_monto_reserva_ac_completo">Monto de reserva - Logs Completo</option>
                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'ESTATAL' or $tipo_usuario == 'ESTADISTICA' or
                            $tipo_usuario == 'COMERCIAL' or $tipo_usuario == 'JEFE COMERCIAL' or $tipo_usuario == 'UIF' or
                            $tipo_usuario == 'COBRANZA'  or $tipo_usuario == 'AUDITORIA' or   $tipo_usuario == 'GNAF'
                            or $tipo_usuario == 'EMISION' or $tipo_usuario == 'JEFE EMISION' or $tipo_usuario == 'GERENTE COMERCIAL' or $tipo_usuario == 'AUDITORIA'
                          ) {
                          ?>
                            <option value="reporte_comercial2">Producción del Sistema Unisersoft</option>
                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'EMISION' or $tipo_usuario == 'JEFE EMISION') {
                          ?>
                            <option value="emision_produccion">Emisor - Producción del Sistema Unisersoft (Moneda origen)</option>
                            <option value="reporte_comercial_usd_t">Producción del Sistema Unisersoft (DOLARIZADA)</option>

                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'ESTADISTICA' or $tipo_usuario == 'UIF' or $tipo_usuario == 'AUDITORIA' or $tipo_usuario == 'COTIZADOR RECLAMOS' or $tipo_usuario == 'LIQUIDADOR') {
                          ?>
                            <option value="reporte_trabajo_compra">Ordenes de Trabajo - Compra</option>
                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'ESTADISTICA' or $tipo_usuario == 'CAUCIONES' or $tipo_usuario == 'COMERCIAL'
                            or $tipo_usuario == 'JEFE COMERCIAL' or $tipo_usuario == 'GERENTE COMERCIAL'
                          ) {
                          ?>
                            <option value="sipof">Reporte de Cauciones</option>
                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'AUDITORIA') {
                          ?>
                            <option value="libro_ventas">Reporte Libro Ventas</option>
                            <option value="siniestro_tc_log">Trabajo - Compara - Logs</option>
                            <option value="log_primas_cobranzas">Primas por cobrar - Logs</option>
                            <option value="log_monto_reserva_ac">Monto de reserva - Logs</option>
                            <option value="correspodencia_cite_hoja_log">Correspondencia - Hojas de ruta - derivaciones</option>
                            <option value="correspodencia_cites">Correspondencia - cites</option>
                            <option value="siniestro_ot_op">Siniestros - Ordenes de Trabajo-Compra - Ordenes de Pago</option>
                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'COBRANZA') {
                          ?>
                            <option value="primas_cobranza_origen">Primas por cobrar moneda origen - Unisersoft</option>
                            <option value="primas_cobranzas">Primas por cobrar - Unisersoft</option>
                            <option value="comercial_clientes">Clientes - antiguo</option>
                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'ESTADISTICA') {
                          ?>
                            <option value="log_monto_reserva_ac">Monto de reserva - Logs</option>
                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'SECRETARIA' or
                            $tipo_usuario == 'RECEPCIONISTA'
                          ) {
                          ?>
                            <option value="constancia_documentos">Constancia de entrega de documentos</option>

                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'RECEPCIONISTA') {
                          ?>
                            <option value="constancia_documentos_recepcion">Constancia de entrega de documentos por recepción</option>
                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'COMERCIAL' or $tipo_usuario == 'JEFE COMERCIAL' or
                            $tipo_usuario == 'AUDITORIA' or $tipo_usuario == 'COBRANZA' or $tipo_usuario == 'GNAF'  or $tipo_usuario == 'UIF' or
                            $tipo_usuario == 'GERENTE COMERCIAL' or $tipo_usuario == 'AUDITORIA'
                          ) {
                          ?>

                            <option value="comercial_clientes_actualizado">Clientes</option>

                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'UIF' or $tipo_usuario == 'AUDITORIA') {
                          ?>
                            <option value="comercial_clientes_actualizado_usuario">Clientes - USUARIO</option>
                            <option value="cumulo_proveedor">Proveedores - Cumulo</option>
                            <option value="cumulo_poliza">Polizas - Cumulo</option>
                            <option value="cumulo_ramo">Ramo - Cumulo</option>

                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'ESTADISTICA' or $tipo_usuario == 'JEFE EMISION') {
                          ?>
                            <option value="estadistica">Reporte estadistica (Producción Unisersoft)</option>
                            <option value="produccion_item">Producción del Sistema Unisersoft - Items</option>
                          <?php
                          }
                          if ($tipo_usuario == 'GERENTE COMERCIAL' or $tipo_usuario == 'AUDITORIA') {
                          ?>
                            <option value="produccion_item">Producción del Sistema Unisersoft - Items</option>
                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'ESTADISTICA' or $tipo_usuario == 'AUDITORIA') {
                          ?>
                            <option value="comercial_especifico">Reporte Comercial Diario / Semanal (Sistema Unibienes)</option>
                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'ESTATAL' or $tipo_usuario == 'COMERCIAL' or $tipo_usuario == 'ESTADISTICA' or
                            $tipo_usuario == 'JEFE COMERCIAL' or $tipo_usuario == 'AUDITORIA' or $tipo_usuario == 'GNAF' or $tipo_usuario == 'GERENTE COMERCIAL'
                          ) {
                          ?>
                            <option value="reporte_comercial">Reporte Comercial - Sistema Unibienes</option>
                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'COMERCIAL' or $tipo_usuario == 'JEFE COMERCIAL' or $tipo_usuario == 'AUDITORIA' or $tipo_usuario == 'UIF' or
                            $tipo_usuario == 'GNAF' or $tipo_usuario == 'GERENTE COMERCIAL' or $tipo_usuario == 'AUDITORIA'
                          ) {
                          ?>
                            <option value="produccion">Reporte producción detallado - Sistema Unibienes</option>
                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'LIQUIDADOR' or $tipo_usuario == 'ESTADISTICA' or $tipo_usuario == 'INSPECTOR' or $tipo_usuario == 'REASEGURO' or
                            $tipo_usuario == 'UIF' or $tipo_usuario == 'GNAF' or $tipo_usuario == 'GERENTE RECLAMOS' or $tipo_usuario == 'JEFE RECLAMOS' or $tipo_usuario == 'AUDITORIA'
                          ) {
                          ?>
                            <option value="siniestros_liquidados_au">Siniestros liquidados automovil - Sistema Unibienes</option>
                            <option value="siniestros_liquidados_au_unisersoft">Siniestros liquidados automovil - Sistema Unisersoft</option>
                            <option value="siniestros_liquidados_au_unisersoft_detalle">Siniestros liquidados automovil Detalle - Sistema Unisersoft</option>

                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'LIQUIDADOR' or $tipo_usuario == 'ESTADISTICA' or $tipo_usuario == 'UIF' or $tipo_usuario == 'REASEGURO' or
                            $tipo_usuario == 'INSPECTOR' or $tipo_usuario == 'GNAF' or $tipo_usuario == 'GERENTE RECLAMOS' or $tipo_usuario == 'JEFE RECLAMOS' or $tipo_usuario == 'AUDITORIA'
                          ) {
                          ?>
                            <option value="siniestros_liquidados_otros">Siniestros liquidados otros - Sistema Unisersoft</option>
                            <option value="siniestros_liquidados_otros_unisersoft_detalle">Siniestros liquidados otros Detalle- Sistema Unisersoft</option>

                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'LIQUIDADOR' or $tipo_usuario == 'INSPECTOR'  or $tipo_usuario == 'ESTADISTICA' or $tipo_usuario == 'REASEGURO' or
                            $tipo_usuario == 'UIF'  or $tipo_usuario == 'GNAF' or $tipo_usuario == 'GERENTE RECLAMOS' or $tipo_usuario == 'JEFE RECLAMOS' or $tipo_usuario == 'AUDITORIA' or $tipo_usuario == 'JEFE COMERCIAL'
                          ) {
                          ?>
                            <option value="sinistros_pendientes">Siniestros pendientes - Sistema Unibienes</option>
                            <option value="sinistros_pendientes_unisersoft_automovil">Siniestros pendientes (Automotor)- Sistema Unisersoft</option>
                            <option value="sinistros_pendientes_unisersoft_otros">Siniestros pendientes (Otros ramos)- Sistema Unisersoft</option>
                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'LIQUIDADOR' or $tipo_usuario == 'ESTADISTICA' or $tipo_usuario == 'INSPECTOR' or $tipo_usuario == 'REASEGURO' or
                            $tipo_usuario == 'AUDITORIA' or $tipo_usuario == 'UIF' or $tipo_usuario == 'GERENTE RECLAMOS' or $tipo_usuario == 'JEFE RECLAMOS' or $tipo_usuario == 'AUDITORIA' or $tipo_usuario == 'JEFE COMERCIAL'
                          ) {
                          ?>
                            <option value="siniestros_generales">Siniestros generales - Sistema Unisersoft</option>
                          <?php
                          }
                          if (
                            $tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'GERENTE RECLAMOS' or $tipo_usuario == 'JEFE RECLAMOS' or $tipo_usuario == 'COTIZADOR RECLAMOS' or
                            $tipo_usuario == 'LIQUIDADOR' or $tipo_usuario == 'AUDITORIA'  or $tipo_usuario == 'ESTADISTICA' or $tipo_usuario == 'REASEGURO'
                          ) {
                          ?>

                            <option value="pagos_anticipados">Siniestros - Pago Anticipos - Sistema Unisersoft</option>
                            <option value="pagos_anticipados_ot">Siniestros - Pago Anticipos - Ordenes Trabajo/Compra Sistema Unisersoft</option>
                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'UIF' or $tipo_usuario == 'AUDITORIA') {
                          ?>
                            <option value="accionistas">Accionistas - Unibienes</option>
                            <option value="accionistas_unisersoft">Accionistas - Unisersoft</option>
                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'UIF') {
                          ?>
                            <option value="log_clientes">Clientes - Movimientos</option>
                          <?php
                          }
                          if ($tipo_usuario == 'admin' or $tipo_usuario == 'GERENTE GENERAL' or $tipo_usuario == 'COMERCIAL' or $tipo_usuario == 'JEFE COMERCIAL' or $tipo_usuario == 'GERENTE COMERCIAL') {
                          ?>
                            <option value="clientes_pep">Clientes PEP</option>
                            <option value="automovil">Automóvil (Inicio de vigencia) - Unibenes</option>
                            <option value="automovil_unisersoft">Automóvil (Inicio de vigencia) - Unisersoft</option>
                        <?php
                          }
                        }
                        ?>

                      </select>
                    </div>
                  </div>

                  <div id="div_combo" style="display: none;">

                  </div>

                  <div id="div_fechas" style="display: none;">
                    <div class='form-check col-md-12 col-xs-12'>
                      <input class='form-check-input' type='checkbox' name='cb_lapso' id='cb_lapso' onchange="fechas_selec(this);">
                      <label class='form-check-label' for='cb_lapso'>Ingresar lapso de tiempo</label>
                    </div>
                    <div id="div_dia">
                      <div class="col-lg-3">
                        <div class="input-group">
                          <span class="input-group-addon">DIA</span>
                          <input type="date" name="f_dia" id="f_dia">
                        </div>
                      </div>
                    </div>
                    <div id="div_lapso" style="display: none;">
                      <div class="col-lg-3">
                        <div class="input-group">
                          <span class="input-group-addon">INICIO</span>
                          <input type="datetime-local" name="f_inicio_r" id="f_inicio_r">
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="input-group">
                          <span class="input-group-addon">FIN</span>
                          <input type="datetime-local" name="f_final_r" id="f_final_r">
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="col-lg-6">
                  <button class="btn btn-primary btn-block" id="limpiar">Procesar</button>
                </div>
              </form>

              <!---vista del administragod-->

            <?php
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>



  <?php include "footer.php";
  $intermedi = '';
  $con->close();
  ?>

  <script>
    if (repo == 'permisos') {
      document.getElementById('div_combo').style.display = "";
      document.getElementById('div_fechas').style.display = "none";
      cadena = "repo=" + repo;
      $.ajax({
          type: 'POST',
          url: 'config_admin/llena_combo_usuarios.php',
          data: cadena
        })
        .done(function(respuesta) {
          $("#div_combo").html(respuesta);
        })
        .fail(function() {
          console.log("error");
        });
    } else {
      document.getElementById('div_combo').style.display = "none";
      document.getElementById('div_fechas').style.display = "";
    }

    function fechas_selec(obj) {
      if (obj.checked) {
        document.getElementById('div_lapso').style.display = "";
        document.getElementById('div_dia').style.display = "none";
      } else {

        document.getElementById('div_dia').style.display = "";
        document.getElementById('div_lapso').style.display = "none";
      }
    }
  </script>