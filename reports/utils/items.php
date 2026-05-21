<?php

function getFilas($cod_poliza, $cod_cotizacion)
{
    global $con;
    $query = "SELECT COUNT(*) AS filas FROM comercial.items WHERE cod_poliza = '$cod_poliza' and cod_cotizacion = '$cod_cotizacion' and `2`='VIGENTE' and `0` LIKE 'CE%';";
    $res = mysqli_query($con, $query);
    $filas = 1;
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $filas = $row['filas'];
    }
    return $filas;
}

function getComplementos($cod_poliza, $cod_cotizacion, $filas)
{
    global $con;
    $columnas = array();
    $query1 = "SELECT `21` as uso, `14` as clase, `25` as plaza, `18` AS anio, `15` AS marca, `16` AS modelo, `13` AS placa from comercial.items where cod_cotizacion='$cod_cotizacion' AND cod_poliza = '$cod_poliza' and `0` LIKE 'CE%';";
    $res = mysqli_query($con, $query1);

    $i = 0;
    while ($r = mysqli_fetch_assoc($res)) {
        $uso = utf8_decode($r['uso']);
        $clase = utf8_decode($r['clase']);
        $plaza = utf8_decode($r['plaza']);
        $anio = $r['anio'];
        $marca = utf8_decode($r['marca']);
        $modelo = utf8_decode($r['modelo']);
        $placa = $r['placa'];
        $columnas[$i] = "<td style='text-align: center; border:1px solid black'>$uso</td>
                        <td style='text-align: center; border:1px solid black'>$clase</td>
                        <td style='text-align: center; border:1px solid black'>$plaza</td>
                        <td style='text-align: center; border:1px solid black'>$anio</td>
                        <td style='text-align: center; border:1px solid black'>$marca</td>
                        <td style='text-align: center; border:1px solid black'>$modelo</td>
                        <td style='text-align: center; border:1px solid black'>$placa</td>";
        $i++;
    }
    while ($i < $filas) {
        $columnas[$i] = "<td style='text-align: center; border:1px solid black'></td>
                        <td style='text-align: center; border:1px solid black'></td>
                        <td style='text-align: center; border:1px solid black'></td>
                        <td style='text-align: center; border:1px solid black'></td>
                        <td style='text-align: center; border:1px solid black'></td>
                        <td style='text-align: center; border:1px solid black'></td>";
        $i++;
    }

    $i = 0;
    while ($i < $filas - 1) {
        $columnas[$i] .= "</tr><tr>";
        $i++;
    }
    $columnas[$filas] .= "</tr>";
    return $columnas;
}
