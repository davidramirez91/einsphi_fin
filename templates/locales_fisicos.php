<script type="text/javascript">
    function a_empresa(parm,usuario,Nivel_acceso) {
        if ((parm == "ElFisicoMatematico_1") && (Nivel_acceso == "1")) {
            location.href = "registro_1";
        } else if ((parm == "ElFisicoMatematico_1") && (Nivel_acceso == "2")) {
            location.href = "registro_1";
        } else if ((parm == "ElFisicoMatematico_2") && (Nivel_acceso == "1")) {
            location.href = "registro_2";
        } else if ((parm == "ElFisicoMatematico_2") && (Nivel_acceso == "2")) {
            location.href = "registro_2";
        } else {
            location.href = parm;
        }        
    }
</script>

<?php
// session_start();
if (!isset($_SESSION['nombre']) && (time() > $_SESSION['expire_time'])) {
    header('Location: login');
}

$usuario = $_SESSION['nombre'];
$nivel = $_SESSION['nivel_acceso'];

echo "¡Bienvenido, " . $usuario . "!<br>";

echo "<div class='class_form_inicio'>";
echo "<h1> SELECCIONA LOCAL</h1>";
echo "<button type='submit' onclick='a_empresa(\"Inicio\",\"$usuario\", \"$nivel\")'>EINSPHI</button>";
echo "<button type='submit' onclick='a_empresa(\"ElFisicoMatematico_1\",\"$usuario\", \"$nivel\")'>EL FISICO MATEMÁTICO 1  - (ESPOCH) </button>";
echo "<button type='submit' onclick='a_empresa(\"ElFisicoMatematico_2\",\"$usuario\", \"$nivel\")'>EL FISICO MATEMÁTICO 2 - (24 DE MAYO)</button>";
echo "</div>";
?>

<style>
    /* Estilos para el formulario */
    .class_form_inicio {
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        border-radius: 10px;
        font-size: 20px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }
    /* Estilos para el botón */
    .class_form_inicio button[type="submit"] {
        display: block;
        width: 100%;
        padding: 20px;
        background-color: #4CAF50;
        color: black;
        border: black;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .class_form_inicio button:hover, .class_form_inicio button:focus {
        outline     : none;
        background  : #3f18c9;
        color       : #FFF;
    }

</style>