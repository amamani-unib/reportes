<?php

	/*Datos de conexion a la base de datos*/
    $con = mysqli_connect("localhost","root","");
     mysqli_select_db($con,"unibienes");
     mysqli_select_db($con,"reportes");
     mysqli_select_db($con,"correspondencia");
     mysqli_select_db($con,"comercial");
    if(!$con){
        @die("<h2 style='text-align:center'>Imposible conectarse a la base de datos! </h2>".mysqli_error($con));
    }
    if (@mysqli_connect_errno()) {
        @die("Conexión falló: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }
    if (!mysqli_set_charset($con, "utf8mb4")) {

    printf("Error loading character set utf8mb4: %s\n", mysqli_error($con));
   }

?>