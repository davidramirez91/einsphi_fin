<?php

// Conectar a la base de datos
$conexion_dinero = mysqli_connect('localhost', 'u936632816_David_1', 'David_Ramirez91', 'u936632816_fisico_1');
$conexion_estudiantes = mysqli_connect('localhost', 'u936632816_David', 'David_Ramirez91', 'u936632816_Pruebas');

// Datos recibidos
$accion = $_POST['accion_input'];
date_default_timezone_set('America/Guayaquil');
$Object = new DateTime();  
$DateAndTime = $Object->format("Y-m-d H:i:s");  
$fecha_actual = date("Ymd_His");

// Obtener la fecha de hace 25 días
$fecha_ant = $Object->sub(new DateInterval('P25D'))->format('Y-m');

$Fiat = floatval($_POST['Fiat_input']);
$Pichincha= floatval($_POST['Pichincha_input']);
$Cry= floatval($_POST['Cry_input']);
$Motivo= $_POST['Motivo_input'];

// Datos a enviar
$consulta_dinero = mysqli_query($conexion_dinero, "SELECT * FROM `dinero`");
$consulta_com = mysqli_fetch_all($consulta_dinero, MYSQLI_ASSOC);   // Datos a enviar

// Proceso
if ($accion == "consultar") {
    echo json_encode($consulta_com); // Datos a enviar
}
if ($accion == "ingresar") {
    $item = count($consulta_com)+1;
    $insert = "INSERT INTO `dinero`(`Item`, `Fecha`, `Fiat`, `Pichincha`, `Cry`, `Motivo`) VALUES ('$item','$DateAndTime','$Fiat','$Pichincha','$Cry','$Motivo')";
    $resultado_insert = mysqli_query($conexion_dinero, $insert);
    $consulta_dinero = mysqli_query($conexion_dinero, "SELECT * FROM `dinero`");
    $consulta_com = mysqli_fetch_all($consulta_dinero, MYSQLI_ASSOC);   // Datos a enviar
    echo json_encode($consulta_com); // Datos a enviar
}
if ($accion == "retirar") {
    $Fiat = $Fiat*-1;
    $Pichincha= $Pichincha*-1;
    $Cry= $Cry*-1;
    $item = count($consulta_com)+1;
    $insert = "INSERT INTO `dinero`(`Item`, `Fecha`, `Fiat`, `Pichincha`, `Cry`, `Motivo`) VALUES ('$item','$DateAndTime','$Fiat','$Pichincha','$Cry','$Motivo')";
    $resultado_insert = mysqli_query($conexion_dinero, $insert);
    $consulta_dinero = mysqli_query($conexion_dinero, "SELECT * FROM `dinero`");
    $consulta_com = mysqli_fetch_all($consulta_dinero, MYSQLI_ASSOC);   // Datos a enviar
    echo json_encode($consulta_com); // Datos a enviar
}
if ($accion == "limpiar") {
    // Crear el nombre del archivo CSV
    $nombre_archivo = "registro_" . $fecha_actual . ".csv";

    // Crear el archivo CSV
    $archivo = fopen($nombre_archivo, "w");

    // Escribir los encabezados en el archivo CSV
    fputcsv($archivo, array("Item", "Fecha", "Fiat", "Pichincha", "Cry", "Motivo"), ";");

    // Escribir los datos en el archivo CSV
    $subt_fiat = 0;
    $subt_pichincha = 0;
    $subt_cry = 0;
    for ($i=0; $i < count($consulta_com); $i++) { 

        $fiat = $consulta_com[$i]["Fiat"];
        $pichincha = $consulta_com[$i]["Pichincha"];
        $cry = $consulta_com[$i]["Cry"];
        
        $subt_fiat += $fiat;
        $subt_pichincha += $pichincha;
        $subt_cry += $cry;

        fputcsv($archivo, array($consulta_com[$i]["Item"], $consulta_com[$i]["Fecha"], $fiat, $pichincha, $cry, $consulta_com[$i]["Motivo"]), ";");
    }
    fputcsv($archivo, array("--", "$DateAndTime", $subt_fiat, $subt_pichincha, $subt_cry, "Informacion correspondiente a $fecha_ant"), ";");

    mysqli_query($conexion_dinero, "DELETE FROM `dinero`");

    $insert = "INSERT INTO `dinero`(`Item`, `Fecha`, `Fiat`, `Pichincha`, `Cry`, `Motivo`) VALUES ('1','$DateAndTime','$subt_fiat','$subt_pichincha','$subt_cry','Informacion correspondiente a $fecha_ant')";
    $resultado_insert = mysqli_query($conexion_dinero, $insert);
    $consulta_dinero = mysqli_query($conexion_dinero, "SELECT * FROM `dinero`");
    $consulta_com = mysqli_fetch_all($consulta_dinero, MYSQLI_ASSOC);   // Datos a enviar
    echo json_encode($consulta_com); // Datos a enviar
    // Cerrar el archivo CSV y la conexión a la base de datos
    fclose($archivo);
    //mysqli_close($conexion);
}
if ($accion == "revisar") {
    $dni = $_POST['DNI_input'];

    $resultado_select = mysqli_query($conexion_estudiantes, "SELECT * FROM `clientes`");
    $datos = mysqli_fetch_all($resultado_select, MYSQLI_ASSOC);

    if ($dni != '') {
        $k = 0;
        while ($datos[$k]['DNI'] != $dni && $k < count($datos)-1) {
            $k++;
        }

        if ( ($datos[$k]['DNI'] != $dni) && ($k == count($datos)-1) ) {
            echo json_encode($dni);
        } else {
            echo json_encode($datos[$k]);
        }
        
    } else {
        echo json_encode("mal");
    }

} 
if ($accion == "enviar_recibo") {
    $clientes = $_POST['clientes_input'];
    $dni = $_POST['DNI_input'];
    $representante = $_POST['Representante_input'];
    $contacto = $_POST['Contacto_input'];
    $Correo = $_POST['Correo_input'];
    $saldo = $_POST['Saldo_input'];

    if ($clientes == "agendar") {
        $insert = "INSERT INTO `clientes`(`DNI`, `Nombre`, `Contacto`, `Correo`) VALUES ('$dni','$representante','$contacto','$Correo')";
        $resultado_insert = mysqli_query($conexion_estudiantes, $insert);
    }

    if (empty($saldo) || $saldo == "") {
        $saldo = 0;
    }
    

    $item = count($consulta_com)+1;
    $abono = $Fiat + $Pichincha + $Cry;
    $insert = "INSERT INTO `dinero`(`Item`, `Fecha`, `Fiat`, `Pichincha`, `Cry`, `Motivo`) VALUES ('$item','$DateAndTime','$Fiat','$Pichincha','$Cry','$Motivo')";
    $resultado_insert = mysqli_query($conexion_dinero, $insert);
    $consulta_dinero = mysqli_query($conexion_dinero, "SELECT * FROM `dinero`");
    $consulta_com = mysqli_fetch_all($consulta_dinero, MYSQLI_ASSOC);   // Datos a enviar
    echo json_encode($consulta_com); // Datos a enviar

    if (!empty($Correo) && filter_var($Correo, FILTER_VALIDATE_EMAIL)) {
        $Correo .= ", dramirez91@einsphi.com, ecardenasv@einsphi.com";
        // Configura el correo a enviar
        $asunto = "RECIBO de $representante";
        $contenido = "<div style='font-family: Arial, sans-serif;'>";
        $contenido .= "<h2><strong>RECIBO</strong></h2>";
        $contenido .= "Riobamba, $DateAndTime<br><br>";
        $contenido .= "Estimad@ <strong>$representante</strong>,<br><br>";
        $contenido .= "El <strong>FISICO MATEMÁTICO</strong> recibe de Ud. $representante la cantidad de $abono USD con un saldo pendiente de <strong>$saldo USD</strong> por el concepto de <strong>$Motivo</strong>.<br><br>";

        $contenido .= "¿Mas información de EL FISICO MATEMÁTICO? Visita: <br>";
        $contenido .= "<a href='https://einsphi.com/ElFisicoMatematico'> El Fisico Matematico </a><br><br>";
        $contenido .= "Contactos y dirección de nuestros locales:<br>";
        $contenido .= "<a href='https://einsphi.com/contactos'> Contactos y Direccion </a><br>";
        
        $contenido .= "<br></strong>Saludos.</strong><br>";
        $contenido .= "Att. David Ramírez, Enrique Cárdenas<br>";
        $contenido .= "Mg. Automatización Industrial<br>";
        $contenido .= "</div>";

        $cabeceras = "From: dramirez91@einsphi.com\r\n";
        $cabeceras .= "MIME-Version: 1.0\r\n";
        $cabeceras .= "Content-type:text/html;charset=UTF-8\r\n";
        
        // Envía el correo
        mail($Correo, $asunto, $contenido, $cabeceras);
    }
} 
?>

