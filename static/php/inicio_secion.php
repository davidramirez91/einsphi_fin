<?php

session_start();

// Conectar a la base de datos
$conexion = mysqli_connect('localhost', 'u936632816_David', 'David_Ramirez91', 'u936632816_Pruebas');

// Datos
$nombre = $_POST['nombre'];
$contrasena = $_POST['contrasena'];

// Verificar si el usuario existe y si la contraseña es correcta
$query = "SELECT * FROM `login` WHERE `Nombre`='$nombre' AND `Contrasena`='$contrasena'";
$resultado = mysqli_query($conexion, $query);



if (mysqli_num_rows($resultado) == 1) {
    $usuario = mysqli_fetch_assoc($resultado);
    $_SESSION['nombre'] = $usuario['Nombre'];
    $_SESSION['nivel_acceso'] = $usuario['Nivel_acceso'];
    
    //$_SESSION['last_page'] = $_POST['last_page']; // Guardar la última página visitada en la sesión
    
    // Redirigir al usuario a la página correspondiente según su nivel de acceso
    if ($usuario['Nivel_acceso'] == "1") {
        $_SESSION['expire_time'] = time() + 80 * (60 * 60); // Sesión de 8 horas
    } elseif ($usuario['Nivel_acceso'] == "2") {
        $_SESSION['expire_time'] = time() + 150 * (60 * 60); // Sesión de 150 horas
    } 
    echo json_encode($usuario);
    header("Location: locales_fisicos");
} else {
    echo json_encode("esta mal");
    header("Location: login");
}

?>