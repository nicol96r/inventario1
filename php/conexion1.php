<?php

    $conexion = mysqli_connect("localhost", "root", "", "usuarios_db");

    if($conexion){
        echo 'Conectado exitosamente a la base de datos';
    }else{
        echo 'No se pudo conectar a la base de datos Intente mas tarde';
    }
?>