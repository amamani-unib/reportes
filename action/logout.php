<?php
	session_start();

	if (isset($_SESSION['usuario'])) {
		//session_destroy();
		//header("location: ../index.php"); //estemos donde estemos nos redirije al index
		header("location: ../../unisersoft/dashboard.php"); //estemos donde estemos nos redirije al index
	}
	
?>