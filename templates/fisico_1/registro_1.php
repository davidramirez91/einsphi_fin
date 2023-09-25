<?php
// session_start();
if (!isset($_SESSION['nombre']) && (time() > $_SESSION['expire_time'])) {
    header('Location: login');
}

$usuario = $_SESSION['nombre'];
$nivel = $_SESSION['nivel_acceso'];

echo "¡Bienvenido, " . $usuario . "!<br>";
?>

<!-- ----------------------------------------------------------------------------------------- -->
<!-- --------------------------- javascritps inicio -------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------- -->

<script type="text/javascript">
    function btn_registro_dinero() {        
        window.open("dinero_1", "_blank");  // Abrir la nueva página en una nueva pestaña
    }
    function btn_registro_asistencia() {
        document.querySelector(".class_formulario_asistencia").classList.toggle("class_formulario_asistencia-show");
    }
    function btn_registro_abono() {
        document.querySelector(".class_formulario_abono").classList.toggle("class_formulario_abono-show");
    }
    function btn_registro_nuevo() {
        document.querySelector(".class_formulario_nuevo_estudiante").classList.toggle("class_formulario_nuevo_estudiante-show");
    }
</script>

<!-- ----------------------------------------------------------------------------------------- -->
<!-- ------------------------------------ html ------------------------------------------ -->
<!-- ----------------------------------------------------------------------------------------- -->

<h2>¿Que deseas hacer?</h2>
<button type="submit" onclick="btn_registro_dinero()">Registrar Dinero</button>
<button type="submit" onclick="btn_registro_nuevo()">Registrar Nuevo Estudiante</button>
<button type="submit" onclick="btn_registro_asistencia()">Asistencia</button>
<button type="submit" onclick="btn_registro_abono()">Abono</button>
<button type="submit" id="id_btn_consulta">Consultar</button>


<!-- Registro Asistencia -->
<form  method="post" id="id_formulario_asistencia" class="class_formulario_asistencia">
    <input type="hidden" name="accion_input" value="asistencia">
    <label for="id_ID_input">ID:</label>
    <input type="text" id="id_ID_input" name="ID_input" placeholder="Ingresa el ID">
    <button type="submit" id="cargar">Enviar</button>
</form>

<!-- Registro Abonos -->
<form  method="post" id="id_formulario_abono" class="class_formulario_abono">
    <input type="hidden" name="accion_input" value="abono">
    <label for="id_ID_input">ID:</label>
    <input type="text" id="id_ID_input" name="ID_input" placeholder="Ingresa el ID">
    <label for="id_Abono_input">Abono FIAT:</label>
    <input type="text" id="id_Abono_input" name="Abono_Fiat_input">
    <label for="id_Abono_Pichi_input">Abono Pichincha:</label>
    <input type="text" id="id_Abono_Pichi_input" name="Abono_Pichi_input">
    <label for="id_Abono_Cry_input">Abono Pichincha:</label>
    <input type="text" id="id_Abono_Cry_input" name="Abono_Cry_input">
    <button type="submit" id="cargar">Abonar</button>
</form>

<!-- Registro Nuevo Estudiante -->
<form method="post" id="id_formulario_nuevo_estudiante" class="class_formulario_nuevo_estudiante">
    <input type="hidden" name="accion_input" value="nuevo_estudiante">    
    <label for="id_Nombre_input">Nombre:</label>
    <input type="text" id="id_Nombre_input" name="Nombre_input" placeholder="Ingresa el Nombre">
    <label for="id_Representante_input">Representante:</label>
    <input type="text" id="id_Representante_input" name="Representante_input" placeholder="Ingresa el Representante">
    <label for="id_Contacto_input">Contacto:</label>
    <input type="text" id="id_Contacto_input" name="Contacto_input">
    <label for="id_Correo_input">Correo:</label>
    <input type="email" id="id_Correo_input" name="Correo_input" placeholder="ejemplo@einsphi.com">
    <label for="id_Plan_input">Plan:</label>
    <input type="text" id="id_Plan_input" name="Plan_input">
    <label for="id_Abono_input">Abono FIAT:</label>
    <input type="text" id="id_Abono_input" name="Abono_Fiat_input">
    <label for="id_Abono_Pichi_input">Abono Pichincha:</label>
    <input type="text" id="id_Abono_Pichi_input" name="Abono_Pichi_input">
    <label for="id_Abono_Cry_input">Abono CRY:</label>
    <input type="text" id="id_Abono_Cry_input" name="Abono_Cry_input">
    <button type="submit" id="cargar">Registrar Estudiante</button>
</form>


<!-- Impresion -->
<div class="resultado_registro" id="id_resultado_registro"></div>

<table class="table table-dark table-hover">
    <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Nombre</th>
            <th scope="col">Representante</th>
            <th scope="col">Contacto</th>            
            <th scope="col">Plan</th>
            <th scope="col">Saldo</th>
            <th scope="col">H</th>
            <th scope="col">Fecha</th>
            <th scope="col">Correo</th>
            <th scope="col">Obsr</th>
        </tr>            
    </thead>
    <tbody id="impr_form"></tbody>
</table>


<!-- ----------------------------------------------------------------------------------------- -->
<!-- ------------------------------- javascritps final --------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------- -->

<script>
    var formulario_registro_asit = document.getElementById('id_formulario_asistencia');
    var formulario_registro_abono = document.getElementById('id_formulario_abono');
    var formulario_asistencia_estudiante = document.getElementById('id_formulario_nuevo_estudiante');
    var consula = document.getElementById('id_btn_consulta');
    var imprimir_tabla = document.getElementById("impr_form");    
    var imprimir = document.getElementById("id_resultado_registro");

    // Funciones
    function obtenerColores(plan, Horas) {
        // Comparar con Horas y determinar el color
        if (Horas < plan) {
            return 'style="color: green;"';
        } else {
            return 'style="color: red;"';
        }
    }
    function imprimir_consulata(data) {
        imprimir_tabla.innerHTML = "";
        for (i of data) {
            var saldo = dplan[i.Plan]-i.Abono;
            let Fecha_G = new Date(i.Fecha_inicio);            
            let Fecha_A = new Date(); // Obtenemos la fecha y hora actual
            let dias_trans_for = Fecha_A - Fecha_G; // Calculamos la diferencia en milisegundos
            let dias_trans = Math.floor(dias_trans_for / (1000 * 60 * 60 * 24));// Convertimos la diferencia a días
            // Remover 'h' y 'b' y convertir a número entero
            let Plan = i.Plan;
            let plan = parseInt(Plan.replace('h', '').replace('b', ''));
            let color = obtenerColores(plan, i.Horas);

            if (plan == 12) {
                if ((dias_trans >= 27) || (i.Horas >= plan)) {
                    imprimir_tabla.innerHTML += `
                    <tr scope="row">
                        <td>${i.id}</td>
                        <td>${i.Nombre}</td>
                        <td>${i.Representante}</td>
                        <td>${i.Contacto}</td>
                        <td>${i.Plan}</td>
                        <td>${saldo}</td>
                        <td ${color} >${i.Horas}</td>
                        <td>${i.Fecha}</td>
                        <td>${i.Correo}</td>
                        <td style="color: red;" >FIN</td>
                    </tr>    
                    ` 
                } else {
                    imprimir_tabla.innerHTML += `
                    <tr scope="row">
                        <td>${i.id}</td>
                        <td>${i.Nombre}</td>
                        <td>${i.Representante}</td>
                        <td>${i.Contacto}</td>
                        <td>${i.Plan}</td>
                        <td>${saldo}</td>
                        <td ${color} >${i.Horas}</td>
                        <td>${i.Fecha}</td>
                        <td>${i.Correo}</td>
                        <td>${dias_trans}</td>
                    </tr>    
                    ` 
                }
                
            } else {
                if (i.Horas < plan) {
                    imprimir_tabla.innerHTML += `
                    <tr scope="row">
                        <td>${i.id}</td>
                        <td>${i.Nombre}</td>
                        <td>${i.Representante}</td>
                        <td>${i.Contacto}</td>
                        <td>${i.Plan}</td>
                        <td>${saldo}</td>
                        <td ${color} >${i.Horas}</td>
                        <td>${i.Fecha}</td>
                        <td>${i.Correo}</td>
                    </tr>    
                    ` 
                } else {
                    imprimir_tabla.innerHTML += `
                    <tr scope="row">
                        <td>${i.id}</td>
                        <td>${i.Nombre}</td>
                        <td>${i.Representante}</td>
                        <td>${i.Contacto}</td>
                        <td>${i.Plan}</td>
                        <td>${saldo}</td>
                        <td ${color} >${i.Horas}</td>
                        <td>${i.Fecha}</td>
                        <td>${i.Correo}</td>
                        <td style="color: red;" >FIN</td>
                    </tr>    
                    ` 
                }
            }
            
        }
    }

    formulario_registro_asit.addEventListener('submit', function(e){
        e.preventDefault();
        var datos = new FormData(formulario_registro_asit);
        fetch('templates/fisico_1/registro_base_datos_1.php',{method: 'POST', body: datos})
            .then(res => res.json())
            .then(data => {
                imprimir_consulata(data)
            })
            .catch(error => {
                console.error('Hubo un problema con la solicitud fetch:', error);
            });
    })

    formulario_registro_abono.addEventListener('submit', function(e){
        e.preventDefault();
        var datos = new FormData(formulario_registro_abono);
        fetch('templates/fisico_1/registro_base_datos_1.php',{method: 'POST', body: datos})
            .then(res => res.json())
            .then(data => {

                imprimir_consulata(data)
            })
    })

    formulario_asistencia_estudiante.addEventListener('submit', function(e){
        e.preventDefault();
        var datos = new FormData(formulario_asistencia_estudiante);
        fetch('templates/fisico_1/registro_base_datos_1.php',{method: 'POST', body: datos})
        .then(res => res.json())
        .then(data => {
            imprimir.innerHTML = data;
        })
    })

    consula.addEventListener("click", function(e){
        e.preventDefault();
        fetch('static/php/fisico_1/sql_consulta_estudiantes_1.php')
        .then(res => res.json())
        .then(data => {
            imprimir.innerHTML = "";
            imprimir_consulata(data)
        })
    })

</script>

<!-- ----------------------------------------------------------------------------------------- -->
<!-- --------------------------------------- css style --------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------- -->

<style>
    /************** FORMULARIOS ************/
    .class_formulario_asistencia{
        background-color: #3f18c9;
        transform: translate(-700px);
        position: absolute;
    }
    .class_formulario_asistencia-show{
        background-color: rgba(150, 73, 22, 0.15);
        transform: translate(2px);
        position: relative;
        transition: transform .5s ease-in-out;
    }

    .class_formulario_abono{
        background-color: #3f18c9;
        transform: translate(-700px);
        position: absolute;
    }
    .class_formulario_abono-show{
        background-color: rgba(150, 73, 22, 0.15);
        transform: translate(2px);
        position: relative;
        transition: transform .5s ease-in-out;
    }

    .class_formulario_nuevo_estudiante{
        background-color: #3f18c9;
        transform: translate(-1500px);
        position: absolute;
        
    }
    .class_formulario_nuevo_estudiante-show{
        background-color: rgba(150, 73, 22, 0.15);
        transform: translate(2px);
        position: relative;
        transition: transform .5s ease-in-out;
    }
</style>