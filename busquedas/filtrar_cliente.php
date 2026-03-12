<?php
session_start();
include "../config/config.php";
$tipo_usuario=$_SESSION["cargo"];


$salida="";
$query="SELECT u.id_cliente,u.cod_cliente AS cli,u.nombres,u.nit_ci,u.telefono_fijo,u.celular,u.nom_representante,u.distrito AS dis 
FROM unibienes.clientes as u WHERE cod_cliente not in (SELECT cod_cliente from cauciones.registra_cauciones as c) ORDER BY id_cliente DESC limit 30";
if (isset($_POST["consulta"])){
    $q = mysqli_real_escape_string($con, $_POST["consulta"]);
    $query="SELECT u.id_cliente,u.cod_cliente AS cli,u.nombres,u.nit_ci,u.telefono_fijo,u.celular,u.nom_representante,u.distrito AS dis FROM unibienes.clientes as u WHERE cod_cliente not in (
        SELECT cod_cliente from cauciones.registra_cauciones as c)and u.cod_cliente like '%".$q."%' or u.nombres like '%".$q."%' or u.nit_ci like '%".$q."%' or u.telefono_fijo like '%".$q."%' or u.celular like '%".$q."%' or u.nom_representante like '%".$q."%' or u.distrito like '%".$q."%' ORDER BY id_cliente DESC limit 30";
    //$queryfd ="SELECT * FROM clientes WHERE 'cod_cliente' like '%".$q."%' or 'nombre' like '%".$q."%' or 'paterno' like '%".$q."%' or 'materno' like '%".$q."%' or 'nit_ci' like '%".$q."%' or 'celular' like '%".$q."%' or 'distrito' like '%".$q."%' or 'zona' like '%".$q."%' DESC limit 30";
}
$resultado = mysqli_query($con, $query);
$num_rows = mysqli_num_rows($resultado);
if($num_rows > 0){
    $salida.="<table id='datos_clientes' class='table table-striped table-hover' width='100%' cellspacing='0' style='font-size:12px;'>
    <thead>
    <tr>
        <th>Cod. Cliente</th>
        <th>Nombre/Razon Social</th>
        <th>Ci/NIT</th>
        <th>Telefono Fijo</th>
        <th>Celular</th>
        <th>Representante Legal</th>
        <th>Distrito</th>";
        if($tipo_usuario != "COBRANZAS"){
            $salida.="<th>Registrar</th>";
        }
        
        $salida.="
    </tr>
</thead>
<tfoot>
    <tr>
        <th>Cod. Cliente</th>
        <th>Nombre/Razon Social</th>
        <th>Ci/NIT</th>
        <th>Telefono Fijo</th>
        <th>Celular</th>
        <th>Representante Legal</th>
        <th>Distrito</th>";
        if($tipo_usuario != "COBRANZAS"){
            $salida.="<th>Registrar</th>";
        }
        
        $salida.="
    </tr>
</tfoot>
<tbody>";
while($fila = mysqli_fetch_assoc($resultado)){
    $id =$fila['cli'];
    //$id_registro=$fila['id_registro'];
    $salida.="<tr>
                <td>".$fila['cli']."</td>
                <td>".$fila['nombres']."</td>
                <td>".$fila['nit_ci']."</td>
                <td>".$fila['telefono_fijo']."</td>
                <td>".$fila['celular']."</td>
                <td>".$fila['nom_representante']."</td>
                <td>".$fila['dis']."</td>
                <td>";
                if($tipo_usuario != "COBRANZAS") {
                    $salida.="<div class='col-md-3'>
                                <a href='clientes_unib.php?cod_cliente=$id'>
                                <button type='submit' class='btn btn-success'>
                                <i class='fas fa-plus-circle'></i></button>                
                                </a>
                            </div>";
                }
                $salida.="</td>
            </tr>";
}
$salida.="</tbody>
        </table>";

}else{
    $salida.="No se encontro resultados de la busqueda o ya se encuentra registrado en el Sistema de Cauciones";
}
echo $salida;
$con -> close();

    
/*

                <td>";
                if($tipo_usuario == "admin") {
                    $salida.="
                    <a href='config/borrar.php?id=$id_registro'>
                       <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm('¿Estas seguro?');'>
                        <i class='fas fa-trash'></i>
                       </button>
                     </a>";
                }
                $salida.="</td>
*/

?>
