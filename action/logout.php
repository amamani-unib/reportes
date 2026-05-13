<?php
session_start();

if (isset($_SESSION['usuario'])) {
	//session_destroy();
	//header("location: ../index.php"); //estemos donde estemos nos redirije al index
	header("location: http://192.168.10.88:5173/inicio"); //estemos donde estemos nos redirije al index
}
