<?php
if (!isset($_SESSION))
    session_start();
$tipo_usuario = $_SESSION["usuario_cargo"];
$usuario = $_SESSION["usuario"];
$distrito = $_SESSION["distrito"];
$nombre = $_SESSION["nombre"];
include "config/config.php";
$con->query("SET NAMES 'utf8'");
?>

<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <ul class="nav side-menu">
            <?php
            if ($tipo_usuario == "admin") {
            ?>
                <li class="<?php if (isset($active5)) {
                                echo $active5;
                            } ?>">
                    <a href="dashboard.php"><i class="fa fa-window-restore" aria-hidden="true"></i> Inicio</a>
                </li>
                <li class="<?php if (isset($active5)) {
                                echo $active5;
                            } ?>">
                    <a href="reporte.php"><i class="fas fa-book"></i> Reportes</a>
                </li>
            <?php
            }elseif (
                $tipo_usuario == "ESTADISTICA" or $tipo_usuario == "COMERCIAL" or
                $tipo_usuario == "LIQUIDADOR" or $tipo_usuario == 'INSPECTOR' or $tipo_usuario == 'AUDITORIA' or $tipo_usuario == 'UIF' or $tipo_usuario == 'ESTATAL'  or $tipo_usuario == 'GERENTE COMERCIAL'
				or $tipo_usuario == 'COBRANZA' or $tipo_usuario == 'JEFE COMERCIAL' or $tipo_usuario == 'EMISION' or $tipo_usuario == 'JEFE EMISION' or $tipo_usuario == 'GERENTE RECLAMOS' or $tipo_usuario == 'JEFE RECLAMOS' or $tipo_usuario='COTIZADOR RECLAMOS'
            ) { ?>
                <li class="<?php if (isset($active5)) {
                                echo $active5;
                            } ?>">
                    <a href="dashboard.php"><i class="fa fa-window-restore" aria-hidden="true"></i> Inicio</a>
                </li>
                <li class="<?php if (isset($active5)) {
                                echo $active5;
                            } ?>">
                    <a href="reporte.php"><i class="fas fa-book"></i> Reportes</a>
                </li>
            <?php
            }
            if ($tipo_usuario == 'SECRETARIA' or $tipo_usuario == 'RECEPCIONISTA') {
            ?>
                <li class="<?php if (isset($active5)) {
                                echo $active5;
                            } ?>">
                    <a href="reporte.php"><i class="fas fa-book"></i> Reportes</a>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
</div>
</div>
</div>

<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="images/persona.png?>" alt=""><?php echo $usuario; ?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="#"><?php echo $distrito; ?></a></li>
                        <li><a href="action/logout.php"><i class="fas fa-door-open"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>
                <li class="">
                    <a href="../unisersoft/dashboard.php">
                        <span class=" fa fa-home"></span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div><!-- /top navigation -->

<?php $con->close(); ?>