<?php
$consulta = "SELECT c.*, lg.usuario FROM comercial.clientes as c INNER JOIN comercial.log_comercial as lg ON lg.cod_cliente=c.cod_cliente";

if (!isset($_POST['cb_lapso'])) {
  $consulta .= " WHERE c.fecha_registro like '%$fecha_dia%'";
  $fecha_aux = $fecha_dia;
  $msj_log = "REPORTE DE CLIENTES UNIERSOFT EL $fecha_dia";
  $titulo = "VISTA PREVIA DE REPORTE CLIENTES SISTEMA UNISERSOFT - ITEMS. DEL $fecha_dia";
} else {
  $consulta .= " WHERE c.fecha_registro >= '$fecha_inicio' and c.fecha_registro <= '$fecha_final'";
  $fecha_aux = $fecha_final;
  $msj_log = "REPORTE DE CLIENTES UNIERSOFT ENTRE $fecha_inicio Y $fecha_final";
  $titulo = "VISTA PREVIA DE REPORTE CLIENTES SISTEMA UNISERSOFT - ITEMS. DESDE $fecha_inicio HASTA $fecha_final";
}

$consulta .= " and lg.movimiento LIKE 'REGISTRO DE CLIENTE%'";
//echo $consulta;
$result = mysqli_query($con, $consulta);
?>
<h2 align="center"><?= $titulo ?> </h2>
<br>
<div id="datos_reportes" class="table-responsive table">
  <table class='tabla_datos table-striped table-bordered table table-hover' cellspacing='0' width='100%' id='tabla_generar'>
    <thead>
      <tr class='text-center'>
        <th>Usuario</th>
        <th>ID CLIENTE</th>        
        <th>COD. DE CLIENTE</th>
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
        <th>Categoría Actividad Económica (CAEDEC)</th>
        <th>Sub categoría Actividad Económica</th>
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
        <th>RESIDENCA POR CIUDADANIA EXTRANJERA</th>
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
      </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <tr>
        <td><?php echo $row['usuario']; ?></td>        
        <td><?php echo $row['id_cliente']; ?></td>        
        <td><?php echo $row['cod_cliente']; ?></td>       
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
        <td><?php echo $row['cat_caedec']; ?></td>
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
        <td><?php echo $row['res_usa']. ' ' . $row['ciudadania']; ?></td>
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
      </tr>
<?php
      }
?>
    </tbody>
    <?php echo $script_tabla; ?>
  </table>
</div>