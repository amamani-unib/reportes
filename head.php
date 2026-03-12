<?php
    if(!isset($_SESSION)){
        session_start();
      }
    

    if (!isset($_SESSION['usuario'])&& $_SESSION['password']==null) {
        header("location: index.php");
    }
    $usuario =$_SESSION['usuario'];
    $distrito = $_SESSION['distrito'];
    $cargo =$_SESSION['usuario_cargo'];
    $nombre =$_SESSION['nombre'];


?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
         <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="http://www.php.net/manual/en/function.utf8-encode.php"> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de Reportes </title>

        <!-- Bootstrap -->
        <link href="css/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome 
          <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        

        <!-- NProgress -->
        <link href="css/nprogress/nprogress.css" rel="stylesheet">
          <!-- iCheck -->
       <link href="css/iCheck/skins/flat/green.css" rel="stylesheet">
       <!-- Datatables -->
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        crossorigin="anonymous" />
       

        <!-- jQuery custom content scroller -->
        <link href="css/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>

        <!-- bootstrap-daterangepicker -->
        <link href="css/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

        <!-- Custom Theme Style -->
        <link href="css/custom.min.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">  
        <!-- MICSS button[type="file"] -->
        <link rel="stylesheet" href="css/micss.css">
        <link rel="stylesheet" href="css/autocomplete.css" >
 
        <link href="assets/sticky-footer-navbar.css" rel="stylesheet">
        <link href="assets/style.css" rel="stylesheet">
        <script src="http://code.jquery.com/jquery-2.1.1.js"></SCRIPT>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                          <a href="#" class="site_title"><i class="fas fa-laptop-house"></i> <span>Reportes</span></a>
                        </div>
                        <div class="clearfix"></div>

                            <!-- menu profile quick info -->
                                <div class="profile clearfix">
                                  
                                    <div class="profile_pic">
                                        <img src="images/logo2.png" class=" " style="width: 200px; height: 80px; margin-left: 10px">

                                    </div>
                                </div>
                                <div class="profile clearfix">
                                    <div class="profile_info">
                                        <span>Bienvenido,</span>
                                        <h2><?php echo $nombre;?></h2>
                                    </div>
                                </div>
                            <!-- /menu profile quick info -->

                        <br />