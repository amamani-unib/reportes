<?php

require_once "../config/config.php";

$separador = "T";
$reporte = $_POST['repo'];
if (!isset($_POST['cb_lapso'])) {
    $fecha_dia = $_POST['f_dia'];
} else {

    $final_inicio = explode($separador, $_POST['f_inicio_r']);
    $fecha_inicio = $final_inicio[0] . " " . $final_inicio[1];

    $final_fin = explode($separador, $_POST['f_final_r']);
    $fecha_final = $final_fin[0] . " " . $final_fin[1];
}

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=reporte_{$reporte}.xls");

switch ($reporte) {
    case 'produccion_item':
        include_once "comercial/items.php";
        break;
}
