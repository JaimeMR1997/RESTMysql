<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conexion
 *
 * @author Jaime
 */
class Conexion {
    
    
    public function getConexion(){
        $usu = "jaime";
        $pass = "1234";
        $ip = "127.0.0.1";
        $baseDatos = "agricola";
        
        $conexion = new mysqli($ip, $usu, $pass, $baseDatos);
        if($conexion->connect_error){
            return null;
        } else {
           return $conexion; 
        }
        
    }
}
