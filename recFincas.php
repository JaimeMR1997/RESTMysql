<?php

include "Conexion.php";

$conexion = new Conexion();
$accesoBD = $conexion->getConexion();

if($accesoBD != null){
    $consulta="SELECT * FROM FINCA";
    $resultado = $accesoBD->query($consulta);
    
    if($resultado instanceof mysqli_result){
        header('Content-type: application/json');
        $json = array();
        //$json["FINCAS"] = array();
        while($array = $resultado->fetch_array()){
//Hay que forzar el encode a UTF8 porque sin o al pasar a JSON da error con
            //caracteres utf8 mal formados
            $fila = array(
                "ID_FINCA" => $array['ID_FINCA'],
                "LOCALIDAD" => utf8_encode($array['LOCALIDAD']),
                "SUPERFICIE" => utf8_encode($array['SUPERFICIE']),
                "F_COMPRA" => utf8_encode($array['F_COMPRA']),
                "F_FIN" => utf8_encode($array['F_FIN'])
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


