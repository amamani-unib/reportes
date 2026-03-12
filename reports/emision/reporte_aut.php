<?php
include "../../config/config.php";

$siniestro_consulta= "SELECT s.cod_siniestro,s.cod_poliza,s.cod_cliente,s.asegurado,s.`f_siniestro`,
                      s.`hora_siniestro`,s.`observaciones`,s.`f_registro`,s.`usuario`,s.`inspector`
                      FROM comercial.siniestros as s where s.f_registro <='2024-10-28'";
$result = mysqli_query($con, $siniestro_consulta);
//$row = mysqli_fetch_assoc($result);


while($row = mysqli_fetch_assoc($result)){
    $cod_s=$row['cod_siniestro'];
?>
     
<?php
    $consulta_movimiento = "SELECT movimiento,fecha,usuario FROM comercial.log_comercial WHERE  movimiento like 'ACTUALIZACION DE ESTADO DEL SINIESTRO:. $cod_s%'";
    $result_m = mysqli_query($con, $consulta_movimiento);
    while($row2 = mysqli_fetch_assoc($result_m)){
?>
          
            <?php echo $row['cod_siniestro']."╩";?> 
            <?php echo $row['cod_poliza']."╩";?>
            <?php echo $row['cod_cliente']."╩";?>
            <?php echo $row['asegurado']."╩";?>
            <?php echo $row['f_siniestro']."╩";?>
            <?php echo $row['hora_siniestro']."╩";?>
            <?php echo $row['observaciones']."╩";?>
            <?php echo $row['f_registro']."╩";?>
            <?php echo $row['usuario']."╩";?>
            <?php echo $row2['fecha']."╩";?>
            <?php echo $row2['usuario']."╩";?>
            <?php echo $row2['movimiento']."╩";?>
                     
     
        
        

        <?php
        echo '<br>';    
        }

?>

<?php
}
?>

