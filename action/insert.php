<?php 
 session_start();
 include "config/config.php";

 //datos por defecto

   $fecha_re=date("Y-m-d");
   $distrito=$_SESSION('distrito');
   $usuario=$_SESSION('usuario');
   $tipo='Seguro General';
   $contratante ='Banco Union';
   $intermediario ='Cartera Directa';
   $tasa=10;
   $n_poliza='AULP00000033';
   $tipo_cultivo='Soya';

// datos enviados por los usuarios

$nombre = $_POST['nombre'];
$celular = $_POST['celular'];
$email = $_POST['email'];
$direccion = $_POST['direccion'];
$tel_fijo = $_POST['tel_fijo'];
$fax = $_POST['fax'];
$ci_nit = $_POST['ci_nit'];
$extencion = $_POST['extencion'];
$estacion = $_POST['estacion'];
$dis_idh = $_POST['dis_idh'];
$periodo_mes = $_POST['periodo_mes'];
$superficie = $_POST['superficie'];
$h_propias = $_POST['h_propias'];
$h_alquiladas = $_POST['h_alquiladas'];
$c_parcelas = $_POST['c_parcelas'];
$descripcion = $_POST['descripcion'];
$municipio = $_POST['municipio'];
$comunidad = $_POST['comunidad'];
$nucleo = $_POST['nucleo'];
$colonia = $_POST['colonia'];
$n_predio = $_POST['n_predio'];
$m_desembolso = $_POST['m_desembolso'];
$n_tramite = $_POST['n_tramite'];
$banca = $_POST['banca'];
$agencia = $_POST['agencia'];
$cod_producto = $_POST['cod_producto'];
$campana = $_POST['campana'];
$g_x = $_POST['g_x'];
$g_y = $_POST['g_y'];
$inicio_v = $_POST['inicio_v'];
$fin_v = $_POST['fin_v'];
$f_movimiento = $_POST['f_movimiento'];
$file = $_POST['file'];
$riesgo=$_POST['riesgo'];

//calculos que el sistema generra

$n_hectareas = $h_propias + $h_alquiladas;
$v_asegurado = $n_hectareas*100;
$v_prima = $v_asegurado *0.1024;




 
if($d_distrito=='SANTA CRUZ'){
$se = $con ->query("SELECT MAX(id_registro) as idcertificado FROM datos_agricola WHERE distrito='$n_distrito'");
		          $fila = $se -> fetch_assoc();
                  $idcer= $fila['idcertificado']+1;

$prefijo = 'CER';
$prefijocert='IT';

if($idcer<=10){
    $prefijo.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=100){
    $prefijo.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=1000){
    $prefijo.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=10000){
    $prefijo.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=100000){
    $prefijo.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=1000000){
    $prefijo.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=10000000){
    $prefijo.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}

if($idcer<=10){
    $prefijocert.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=100){
    $prefijocert.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=1000){
    $prefijocert.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=10000){
    $prefijocert.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=100000){
    $prefijocert.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=1000000){
    $prefijocert.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}elseif($idcer<=10000000){
    $prefijocert.str_pad($idcer, 8, "0", STR_PAD_LEFT);
}}

$nro_cetifi = $prefijo.str_pad($idcer, 8, "0", STR_PAD_LEFT);
$nro_item = $prefijocert.str_pad($idcer, 8, "0", STR_PAD_LEFT);

$ins=$con -> query("INSERT INTO `datos_agricola`(`id_registro`,`fecha_registro`, `distrito`, `usuario`, `item`, `tipo`, `contratante`, `intermediario`, `riesgo`, `tipo_cultivo`, `nro_poliza`, `nro_certificado`, `campaña`, `tasa`, `georeferenciacion _x`, `georeferenciacion_y`, `inicio_vigencia`, `fin_vigencia`, `fecha_movimiento`, `imagen`, `nombre_cliente`, `celular`, `email`, `telefono`, `fax`, `direccion`, `ci_nit`, `extension`, `estacion`, `disparador_idh`, `periodo`, `superficie_siembra`, `h_propias`, `h_alquiladas`, `cantidad_parcelas`, `des_garantia`, `municipio`, `comunidad`, `nucleo`, `colonia`, `nombre_predio`, `monto_desembolsado`, `numero_tramite`, `banca`, `agencia`, `codigo_producto`, `nro_hectareas`, `valor_asegurado`, `prima_seguro`) VALUES ('','$fecha_re','$distrito','$usuario','$nro_item','$contratante','$intermediario','$riesgo','$tipo_cultivo','$n_poliza','$nro_cetifi','$campana','$tasa','$g_x','$g_y','$inicio_v','$fin_v','$f_movimiento','$file','$nombre','$celular','$email','$tel_fijo','$fax','$direccion','$ci_nit','$extension','$estacion','$dis_idh','$periodo_mes','$superficie','$h_propias','$h_alquiladas','$c_parcelas','$descripcion','$municipio','$comunidad','$nucleo','$colonia','$n_predio','$m_desembolso','$n_tramite','$banca','$agencia','$cod_producto','$n_hectareas','$v_asegurado','$v_prima')");

if ($ins) {
	header('Location: ../index.php');
}else{
	echo "Registro no almacenado";
}

 ?>