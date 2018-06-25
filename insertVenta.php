<?php

include "Conexion.php";

$conexion = new Conexion();
$accesoBD = $conexion->getConexion();

if($accesoBD != null){

    if(isset($_GET['ID_EXP'])){

        $explotacion = $_GET['ID_EXP'];
        //Plantaciones no terminadas o terminan hoy o despues de hoy
        $consulta = "SELECT * FROM PLANTACION WHERE ID_EXPLOTACION = '" . $explotacion . "'"
                . " AND (F_FIN IS NULL OR F_FIN>=SYSDATE())";
        $resultado = $accesoBD->query($consulta);
        if($resultado instanceof mysqli_result){
            $id_plant = $resultado->fetch_array()['ID_PLANT'];
        }
        if($id_plant == NULL){
            $json = array( "COD_SALIDA" => "FALSE" ); //FALSE
            echo json_encode($json,JSON_PRETTY_PRINT);
            exit();
        }
        
        $cantidad = $_GET['CANTIDAD'];
        $precio = $_GET['PRECIO'];
        $tamanio = $_GET['TAMANIO'];
        $color = $_GET['COLOR'];
        $fecha = $_GET['FECHA'];
        
        $id = generarIdVenta($id_plant,$fecha,$accesoBD);
        

        $consulta = "INSERT INTO VENTA(ID_VENTA,KG,PRECIO,TAMANIO,COLOR,FECHA,ID_PLANT)"
                . " VALUES('". $id. "'," . $cantidad . "," . $precio . ",'" . $tamanio . 
                "','" . $color . "','" . $fecha . "','" . $id_plant . "')";
        $resultado = $accesoBD->query($consulta);        
        /* Output header */
        header('Content-type: application/json');
        
        if($resultado){    
            $json = array( "COD_SALIDA" => "TRUE" ); //TRUE

        }else{
            $json = array( "COD_SALIDA" => "FALSE" ); //FALSE
            //echo 'error al insertar';
        }
        echo json_encode($json,JSON_PRETTY_PRINT);
        //echo json_last_error_msg();
    }
    
}

function generarIdVenta($id_plant,$fecha,$accesoBD){
    if($accesoBD instanceof mysqli){
        $i = 1;
        $idVenta = $fecha . "-" . $i;

        $consulta = "SELECT ID_VENTA FROM VENTA WHERE ID_VENTA = '" . $idVenta ."'"
                . " AND ID_PLANT = '" . $id_plant . "'";

        $resultado = $accesoBD->query($consulta);
        $idBuscado = $resultado->fetch_array();//S iesta vacio devuelve nulo
        //Y es que el id no existe en la BD. Si no es que existe y hay que  generar otro
        while($idBuscado != null){
            if($resultado instanceof mysqli_result){
                $i++;
                $idVenta = $fecha . "-" . $i;
                $consulta = "SELECT ID_VENTA FROM VENTA WHERE ID_VENTA = '" . $idVenta ."'"
                . " AND ID_PLANT = '" . $id_plant . "'";
                $resultado = $accesoBD->query($consulta);
                $idBuscado = $resultado->fetch_array();
            }
        }

        return $idVenta;
    }
}


