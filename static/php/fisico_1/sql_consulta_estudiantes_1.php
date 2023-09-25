<?php

// Conectar a la base de datos
$conexion_dinero = mysqli_connect('localhost', 'u936632816_David_1', 'David_Ramirez91', 'u936632816_fisico_1');
$conexion_estudiantes = mysqli_connect('localhost', 'u936632816_David', 'David_Ramirez91', 'u936632816_Pruebas');

// Datos
$resultado_select = mysqli_query($conexion_estudiantes, "SELECT * FROM `estudiantes`");
$datos = mysqli_fetch_all($resultado_select, MYSQLI_ASSOC); // Datos a enviar

if (!empty($datos)) {
    echo json_encode($datos); // Datos a enviar
}else{
    echo json_encode([]);
}
?>


