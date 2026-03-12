<?php
	session_start();

	if (isset($_POST['token']) && $_POST['token']!=='') {
			
	//Contiene las variables de configuracion para conectar a la base de datos
	include "../config/config.php";

	$usu=mysqli_real_escape_string($con,(strip_tags($_POST["usuario"],ENT_QUOTES)));
	$password=md5(mysqli_real_escape_string($con,(strip_tags($_POST["password"],ENT_QUOTES))));

    $query = mysqli_query($con,"SELECT * FROM usuario WHERE usu_agricola =\"$usu\" AND contraseña = \"$password\";");

		if ($row = mysqli_fetch_array($query)) {
			

				$_SESSION['usuario'] = $row['usu_agricola'];
				header("location: ../dashboard.php");
				

		}else{
			$invalid=md5("contrasena y usuario invalido");
			header("location: ../index.php?invalid=$invalid");
		}
	}else{
		header("location: ../");
	}

?>