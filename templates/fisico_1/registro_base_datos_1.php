<?php

// Conectar a la base de datos
$conexion_dinero = mysqli_connect('localhost', 'u936632816_David_1', 'David_Ramirez91', 'u936632816_fisico_1');
$conexion_estudiantes = mysqli_connect('localhost', 'u936632816_David', 'David_Ramirez91', 'u936632816_Pruebas');

// Datos
$accion = $_POST['accion_input'];
date_default_timezone_set('America/Guayaquil');
$Object = new DateTime();  
$DateAndTime = $Object->format("Y-m-d H:i:s");  
$dplan = array(
    "10h" => 55,
    "20h" => 100,
    "30h" => 150,
    "12h" => 65,
    "10b" => 45,
    "20b" => 85,
    "30b" => 120,
    "12b" => 50,
);


if ($accion == "asistencia") {
    $id = $_POST['ID_input'];
    
    if ($id == '') {
        echo json_encode('Llena el ID');
    } else{
        $resultado_select = mysqli_query($conexion_estudiantes, "SELECT * FROM `estudiantes` WHERE `id`= $id");
        $datos = mysqli_fetch_all($resultado_select, MYSQLI_ASSOC); // Datos a enviar 
        $datos = $datos[0];
        $plan = $datos["Plan"];
        $correo = $datos["Correo"];
        $representante = $datos["Representante"];
        $alumno = $datos["Nombre"];
        
        $hora = $datos["Horas"]+1;

        $actualizar_datos = mysqli_query($conexion_estudiantes, "UPDATE `estudiantes` SET `Horas`='$hora',`Fecha`='$DateAndTime' WHERE `id`= $id ");

        $resultado_select = mysqli_query($conexion_estudiantes, "SELECT * FROM `estudiantes` WHERE `id`= $id");
        $datos = mysqli_fetch_all($resultado_select, MYSQLI_ASSOC); // Datos a enviar       
        
        if (!empty($datos)) {
            echo json_encode($datos); // Datos a enviar
        }else{
            echo json_encode([]);
        }

        $hora_plan = (int) str_replace(['h', 'b'], '', $plan);

        if (intval($hora) >= $hora_plan) {
            // correo avisando el fin de las horas
            if (!empty($Correo) && filter_var($Correo, FILTER_VALIDATE_EMAIL)) {
                // Configura el correo a enviar
                $asunto = "NOTIFICACIÓN FIN DE HORAS de $alumno";
                $contenido = "<div style='font-family: Arial, sans-serif;'>";
                $contenido .= "<h2><strong>FIN DEL PLAN DE $plan</strong></h2>";
                $contenido .= "Riobamba, $DateAndTime<br><br>";
                $contenido .= "Estimad@ <strong>$representante</strong>,<br><br>";
                $contenido .= "El <strong>FISICO MATEMÁTICO</strong> informa a Ud. $representante que ha finalizado el plan de $plan con un saldo pendiente de <strong>$saldo USD</strong> <br><br>";

                $contenido .= "¿Mas información de EL FISICO MATEMÁTICO? Revisa nuestro cursos / planes / locales.  Visita: <br>";
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
    }
    
} 
if ($accion == "abono") {
    $id = $_POST['ID_input'];

    if ($id == '') {
        echo json_encode('Llena el ID');
    } else {
        $Fiat = floatval($_POST['Abono_Fiat_input']);
        $Pichincha = floatval($_POST['Abono_Pichi_input']);
        $Cry = floatval($_POST['Abono_Cry_input']);

        $abono = $Fiat + $Pichincha + $Cry;

        $Abono_select = mysqli_query($conexion_estudiantes, "SELECT * FROM `estudiantes` WHERE `id`= $id");
        $datos_compl = mysqli_fetch_all($Abono_select, MYSQLI_ASSOC);
        $datos_compl = $datos_compl[0];
        $Abono = $datos_compl["Abono"]+$abono;
        $representante = $datos_compl["Representante"];
        $plan = $datos_compl["Plan"];
        $Correo = $datos_compl["Correo"];
        $saldo = $dplan[$plan]-$Abono;

        $Motivo = "Abono de " . $datos_compl["Nombre"] . " del plan de " . $datos_compl["Plan"];

        $actualizar_datos = mysqli_query($conexion_estudiantes, "UPDATE `estudiantes` SET `Abono`='$Abono',`Fecha`='$DateAndTime' WHERE `id`= $id");

        $resultado_select = mysqli_query($conexion_estudiantes, "SELECT * FROM `estudiantes` WHERE `id`= $id");
        $datos = mysqli_fetch_all($resultado_select, MYSQLI_ASSOC); // Datos a enviar

        $consulta_dinero = mysqli_query($conexion_dinero, "SELECT * FROM `dinero`");
        $consulta_com = mysqli_fetch_all($consulta_dinero, MYSQLI_ASSOC);
        $item = count($consulta_com)+1;
        $insert = "INSERT INTO `dinero`(`Item`, `Fecha`, `Fiat`, `Pichincha`, `Cry`, `Motivo`) VALUES ('$item','$DateAndTime','$Fiat','$Pichincha','$Cry','$Motivo')";
        $resultado_insert = mysqli_query($conexion_dinero, $insert);

        if (!empty($Correo) && filter_var($Correo, FILTER_VALIDATE_EMAIL)) {
            // Configura el correo a enviar
            $asunto = "RECIBO de $representante";
            $contenido = "<div style='font-family: Arial, sans-serif;'>";
            $contenido .= "<h2><strong>RECIBO</strong></h2>";
            $contenido .= "Riobamba, $DateAndTime<br><br>";
            $contenido .= "Estimad@ <strong>$representante</strong>,<br><br>";
            $contenido .= "El <strong>FISICO MATEMÁTICO</strong> recibe de Ud. $representante la cantidad de $abono USD con un saldo pendiente de <strong>$saldo USD</strong> por el concepto del plan de $plan.<br><br>";

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

        if (!empty($datos)) {
            echo json_encode($datos); // Datos a enviar
        } else{
            echo json_encode([]);
        } 
    }
}
if ($accion == "nuevo_estudiante") {
    $representante = $_POST['Representante_input'];
    $nombre = $_POST['Nombre_input'];
    $contacto = $_POST['Contacto_input'];
    $plan = $_POST['Plan_input'];
    $Fiat = floatval($_POST['Abono_Fiat_input']);
    $Pichincha = floatval($_POST['Abono_Pichi_input']);
    $Cry = floatval($_POST['Abono_Cry_input']);
    $Correo = $_POST['Correo_input'];

    $Motivo= "Plan " . $plan . " de " . $nombre;
    $abono = $Fiat + $Pichincha + $Cry;
    $saldo = $dplan[$plan]-$abono;

    if ($nombre == '' || $plan == '') {
        echo json_encode('Los campos necesario no estan llenos');
    } else{        
        if ( ($Fiat === '') || ($Pichincha === '') || ($Cry === '')) {
            echo json_encode("Ingresa uno de los datos requeridos FIAT o PICHINCHA");
        } else {

            $consulta_est = "INSERT INTO `estudiantes`(`Nombre`, `Representante`, `Contacto`, `Plan`, `Abono`,`Horas`, `Fecha_inicio`, `Fecha`, `Correo`) VALUES ('$nombre','$representante','$contacto','$plan','$abono','0','$DateAndTime','$DateAndTime', '$Correo')";
            $resultado_insert = mysqli_query($conexion_estudiantes, $consulta_est);

            $consulta_dinero = mysqli_query($conexion_dinero, "SELECT * FROM `dinero`");
            $consulta_com = mysqli_fetch_all($consulta_dinero, MYSQLI_ASSOC);
            $item = count($consulta_com)+1;

            $insert = "INSERT INTO `dinero`(`Item`, `Fecha`, `Fiat`, `Pichincha`, `Cry`, `Motivo`) VALUES ('$item','$DateAndTime','$Fiat','$Pichincha','$Cry','$Motivo')";
            $resultado_insert = mysqli_query($conexion_dinero, $insert);
            echo json_encode("Bien ingresado");
        }        
    }

    if (!empty($Correo) && filter_var($Correo, FILTER_VALIDATE_EMAIL)) {
        // Configura el correo a enviar
        $asunto = "RECIBO de $representante";
        $contenido = "<div style='font-family: Arial, sans-serif;'>";
        $contenido .= "<h2><strong>RECIBO</strong></h2>";
        $contenido .= "Riobamba, $DateAndTime<br><br>";
        $contenido .= "Estimad@ <strong>$representante</strong>,<br><br>";
        $contenido .= "El <strong>FISICO MATEMÁTICO</strong> recibe de Ud. $representante la cantidad de $abono USD con un saldo pendiente de <strong>$saldo USD</strong> por el concepto del plan de $plan.<br><br>";

        $contenido .= "¿Mas información de EL FISICO MATEMÁTICO? Visita: <br>";
        $contenido .= "<a href='https://einsphi.com/ElFisicoMatematico'> El Fisico Matematico </a><br>";
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