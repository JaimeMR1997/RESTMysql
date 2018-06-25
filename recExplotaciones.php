<?php

include "Conexion.php";

$conexion = new Conexion();
$accesoBD = $conexion->getConexion();

if($accesoBD != null && isset($_GET['ID_FINCA'])){
    $finca = $_GET['ID_FINCA'];
    
    ///SELECT * FROM EXPLOTACION WHERE ID_FINCA = 'MAZARULES' 
    ////AND ID_EXPLOTACION IN (SELECT ID_EXPLOTACION FROM PLANTACION WHERE F_FIN IS NULL OR F_FIN>=SYSDATE())
    $consulta = "SELECT * FROM EXPLOTACION WHERE ID_FINCA = '" . $finca ."'"
            . " AND ID_EXPLOTACION IN (SELECT ID_EXPLOTACION FROM PLANTACION WHERE F_FIN IS NULL OR F_FIN>=SYSDATE())";
    
    $resultado = $accesoBD->query($consulta);
    
    if($resultado instanceof mysqli_result){
        header('Content-type: application/json');
        $json = array();
        //$json["FINCAS"] = array();
        while($array = $resultado->fetch_array()){
//Hay que forzar el encode a UTF8 porque sin o al pasar a JSON da error con
            //caracteres utf8 mal formados
            $fila = array(
                "ID_EXPLOTACION" => $array['ID_EXPLOTACION'],
                "SUPERFICIE" => utf8_encode($array['SUPERFICIE']),
                "TIPO" => utf8_encode($array['TIPO']),
                "F_CREACION" => utf8_encode($array['F_CREACION']),
                "F_FIN" => utf8_encode($array['F_FIN']),
                "ID_FINCA" => utf8_encode($array['ID_FINCA'])
            );
            
            //array_push($json["FINCAS"], $fila);
            array_push($json, $fila);            
            
        }
        /* Output header */
        //header('Content-type: application/json');
        echo json_encode($json,JSON_PRETTY_PRINT);
        //echo json_last_error_msg();
    }
}


