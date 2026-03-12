<?php
  session_start();
  include "config/config.php";
  $usuario = $_POST['usuario'];
  $password = md5($_POST["password"]);
    
  if(isset($_POST['log'])) {
    $sql = $con ->query ("SELECT * FROM reportes.usuarios WHERE usuario='$usuario' AND pass='$password'");
    $row_cnt = mysqli_num_rows($sql);
    if($row_cnt>0) {
      $fila= $sql -> fetch_assoc();
      $_SESSION["usuario"] = $fila["usuario"];
      $_SESSION["distrito"] = $fila["distrito"];
      $_SESSION["cargo"] = $fila["cargo"];
      $_SESSION["nombre"] = $fila["nombre"];
      $_SESSION["apellido"] = $fila["apellido"];
      if($_SESSION['cargo'] == 'SECRETARIA' OR $_SESSION['cargo'] == 'RECEPCIONISTA')
        header("Location:reporte.php");
      else
        header("Location:dashboard.php");
    }else
      echo "<script type='text/javascript'>alert('$msg'+'El usuario o contraseña son incorrectos');</script>";

    if (!mysqli_set_charset($con, "utf8mb4")) {
      printf("Error loading character set utf8mb4: %s\n", mysqli_error($con));
      exit();
    }
  }
  $con->close();
?>

