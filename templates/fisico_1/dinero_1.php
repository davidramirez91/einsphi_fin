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
    function btn_recibo() {
        document.querySelector(".class_datos_recibo").classList.toggle("class_datos_recibo-show");
        
        document.querySelector(".class_datos_dinero").classList.toggle("class_datos_dinero-hidden");
        document.querySelector(".class_btn_recibo").classList.toggle("class_btn_recibo-hidden");
    }
</script>

<!-- ----------------------------------------------------------------------------------------- -->
<!-- ------------------------------------ html ------------------------------------------ -->
<!-- ----------------------------------------------------------------------------------------- -->


<form method="post" id="id_form_fm_registro">
    <label>DATOS FISICO:</label>

    <div id="id_datos_recibo" class="class_datos_recibo">
        <input type="text" id="id_DNI" name="DNI_input" placeholder="DNI">
        <button type="submit" class="class_revisar_button" id="id_revisar_button">Revisar</button>        
    </div>

    <input type="text" id="id_Fiat" name="Fiat_input" placeholder="FIAT">
        <input type="text" id="id_Pichincha" name="Pichincha_input" placeholder="Pichincha">
        <input type="text" id="id_Cry" name="Cry_input" placeholder="Cry">
        <input type="text" id="id_Motivo" name="Motivo_input" placeholder="Motivo">
    
    <div class="class_datos_dinero">                
        <button type="submit" class="login-button" id="id-login-button">Ingresar</button>
        <button type="submit" class="class_consulta-button" id="id-consulta-button">Consulta</button>
        <button type="submit" class="logout-button" id="id-logout-button">Retirar</button>
        <button type="submit" class="class_limpiar-button" id="id-limpiar-button">Limpiar</button>
    </div>

    <div class="class_btn_datos_recibo">
        <input type="text" id="id_Saldo" name="Saldo_input" placeholder="Saldo Pendiente">
        <button type="submit" class="class_send_recibo-button" id="id-send_recibo-button">Enviar Recibo</button>     
    </div>
    
    
</form> 

<div class="class_btn_recibo">                
    <button type="submit" class="class_recibo-button" id="id-recibo-button" onclick="btn_recibo()">RECIBO</button>
</div>




<input type="password" id="id_password" name="password_input" autocomplete="off">

<div class="contenido" id="id_mensaje"></div>
<div class="contenido" id="id_resumen"></div>

<table class="table table-dark table-hover">
    <thead>
        <tr>
            <th scope="col">Item</th>
            <th scope="col">Fecha</th>
            <th scope="col">Fiat</th>
            <th scope="col">Pichincha</th>
            <th scope="col">Cry</th>
            <th scope="col">Motivo</th>
        </tr>
    </thead>
    <tbody id="id_tabla_fm_registro"></tbody>
</table>

<!-- ----------------------------------------------------------------------------------------- -->
<!-- ------------------------------- javascritps final --------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------- -->

<script>
    const formula = document.getElementById('id_form_fm_registro');    

    const btn_ingreso = document.getElementById("id-login-button");
    const btn_consulta = document.getElementById("id-consulta-button");
    const btn_retiro = document.getElementById("id-logout-button");
    const btn_limpiar = document.getElementById("id-limpiar-button");
    const btn_revisar= document.getElementById("id_revisar_button");
    const btn_enviar_recibo = document.getElementById("id-send_recibo-button");

    var passw = document.getElementById("id_password").value;

    const imp_datos_recibo = document.getElementById("id_datos_recibo");
    const mensaje = document.getElementById("id_mensaje");
    const resumen = document.getElementById("id_resumen");
    const imp_tabla = document.getElementById("id_tabla_fm_registro");

    // Funciones
    function obtenerColores(fiat, pichincha, cry) {
        let col_f, col_p, col_c;
        col_f = fiat < 0 
                ? `style="color: red;"`
                : fiat > 0 
                ? `style="color: green;"`
                : ``;
        col_p = pichincha < 0 
                ? `style="color: red;"`
                : pichincha > 0 
                ? `style="color: green;"`
                : ``;
        col_c = cry < 0 
                ? `style="color: red;"`
                : cry > 0 
                ? `style="color: green;"`
                : ``;
        return {
            col_f: col_f,
            col_p: col_p,
            col_c: col_c
        };
    }

    function consultar_fm(datos_enviar, resumen, imp_tabla, passw) {
        fetch('static/php/fisico_1/sql_consulta_dinero_1.php', {
            method: 'POST',
            body: datos_enviar
        })
        .then(res => res.json())
        .then(data => {
            subt_fiat = 0;  subt_pichincha = 0;
            subt_cry = 0;   imp_tabla.innerHTML = "";

            if (passw == "micro") {
                console.log("contraseña aceptada")
                for (i of data) {
                    fiat = parseFloat(i.Fiat);
                    pichincha = parseFloat(i.Pichincha);
                    cry = parseFloat(i.Cry);

                    subt_fiat += fiat;
                    subt_pichincha += pichincha;
                    subt_cry += cry;

                    let color = obtenerColores(fiat, pichincha, cry);

                    imp_tabla.innerHTML += `
                        <tr scope="row">
                            <td>${i.Item}</td>
                            <td>${i.Fecha}</td>
                            <td ${color.col_f}>$${fiat.toFixed(2)}</td>
                            <td ${color.col_p}>$${pichincha.toFixed(2)}</td>
                            <td ${color.col_c}>$${cry.toFixed(2)}</td>
                            <td>${i.Motivo}</td>
                        </tr>    
                    ` 
                }

                total = subt_fiat + subt_pichincha + subt_cry;

                resumen.innerHTML = `
                    <p> FIAT = $${subt_fiat.toFixed(2)} </p>
                    <p> PICHINCHA = $${subt_pichincha.toFixed(2)} </p>
                    <p> CRY = $${subt_cry.toFixed(2)} </p>
                    <p> ---- TOTAL --- = USD ${total.toFixed(2)} </p>
                `;
            } else {
                console.log("contraseña NO aceptada")
                if (data.length == 1) {
                    for (let i = 0; i < data.length; i++) {
                        fiat = parseFloat(data[i].Fiat);
                        pichincha = parseFloat(data[i].Pichincha);
                        cry = parseFloat(data[i].Cry);    
                        
                        let color = obtenerColores(fiat, pichincha, cry);

                        imp_tabla.innerHTML += `
                            <tr scope="row">
                                <td>${data[i].Item}</td>
                                <td>${data[i].Fecha}</td>
                                <td ${color.col_f}>$${fiat.toFixed(2)}</td>
                                <td ${color.col_p}>$${pichincha.toFixed(2)}</td>
                                <td ${color.col_c}>$${cry.toFixed(2)}</td>
                                <td>${data[i].Motivo}</td>
                            </tr>    
                        ` 
                    }    
                } else {
                    for (let i = data.length-2; i < data.length; i++) {
                        fiat = parseFloat(data[i].Fiat);
                        pichincha = parseFloat(data[i].Pichincha);
                        cry = parseFloat(data[i].Cry);   

                        let color = obtenerColores(fiat, pichincha, cry);

                        imp_tabla.innerHTML += `
                            <tr scope="row">
                                <td>${data[i].Item}</td>
                                <td>${data[i].Fecha}</td>
                                <td ${color.col_f}>$${fiat.toFixed(2)}</td>
                                <td ${color.col_p}>$${pichincha.toFixed(2)}</td>
                                <td ${color.col_c}>$${cry.toFixed(2)}</td>
                                <td>${data[i].Motivo}</td>
                            </tr>    
                        `                                  
                    }
                }                
            }                
        })
    }

    // BOTONES FM
    btn_consulta.addEventListener("click", (e) => {
        e.preventDefault(); // Evita que el formulario se envíe de forma predeterminada
        var datos_enviar = new FormData(formula);        
        datos_enviar.append("accion_input", "consultar");// Agregar la accion_input al FormData
        consultar_fm(datos_enviar, resumen, imp_tabla, document.getElementById("id_password").value);
    })
    btn_limpiar.addEventListener("click", (e) => {
        e.preventDefault();
        var datos_enviar = new FormData(formula);        
        datos_enviar.append("accion_input", "limpiar");// Agregar la accion_input al FormData
        var password = document.getElementById("id_password").value;
        if (password === "") {
            alert("Por favor, ingresa una contraseña.");
        } else if (password === "micro") {
            consultar_fm(datos_enviar, resumen, imp_tabla, password);
        } else {
            alert("Contraseña incorrecta");
        } 
    })
    btn_retiro.addEventListener("click", (e) => {
        e.preventDefault();
        var datos_enviar = new FormData(formula);        
        datos_enviar.append("accion_input", "retirar");// Agregar la accion_input al FormData
        if (datos_enviar.get('Motivo_input')=="" ) {
            alert("Llena el Motivo")
        } else {
            consultar_fm(datos_enviar, resumen, imp_tabla, document.getElementById("id_password").value);
        }   
    })
    btn_ingreso.addEventListener("click", (e) => {
        e.preventDefault();
        var datos_enviar = new FormData(formula);        
        datos_enviar.append("accion_input", "ingresar");// Agregar la accion_input al FormData
        if (datos_enviar.get('Motivo_input')=="" ) {
            alert("Llena el Motivo")
        } else {
            consultar_fm(datos_enviar, resumen, imp_tabla, document.getElementById("id_password").value);
        }   
    })  
    btn_revisar.addEventListener("click", (e) => {
        e.preventDefault();
        var datos_enviar = new FormData(formula);        
        datos_enviar.append("accion_input", "revisar");// Agregar la accion_input al FormData
        fetch('static/php/fisico_1/sql_consulta_dinero_1.php', {
            method: 'POST',
            body: datos_enviar
        })
        .then(res => res.json())
        .then(data => {
            if (data == datos_enviar.get('DNI_input')) {
                imp_datos_recibo.innerHTML = `
                    <input type="hidden" name="clientes_input" value="agendar">
                    <input type="text" name="DNI_input" id="id_DNI" value="${data}" readonly>						
                    <input type="text" id="id_Representante" name="Representante_input" placeholder="Nombre [Evita las tildes]">
                    <input type="email" id="id_Correo_input" name="Correo_input" placeholder="ejemplo@einsphi.com">
                    <input type="text" id="id_Contacto" name="Contacto_input" placeholder="Contacto">
                `
                document.querySelector(".class_btn_datos_recibo").classList.toggle("class_btn_datos_recibo-show");
            } else if (data == "mal") {
                alert("Llena el campo DNI")
            } else {
                imp_datos_recibo.innerHTML = `
                    <input type="hidden" name="clientes_input" value="agendado">
                    <input type="text" name="DNI_input" id="id_DNI" value="${data.DNI}" readonly>						
                    <input type="text" id="id_Representante" name="Representante_input" value="${data.Nombre}" readonly>
                    <input type="email" id="id_Correo_input" name="Correo_input" value="${data.Correo}" readonly>
                    <input type="text" id="id_Contacto" name="Contacto_input" value="${data.Contacto}" readonly>
                `
                document.querySelector(".class_btn_datos_recibo").classList.toggle("class_btn_datos_recibo-show");
            }
            

        })
    }) 
    btn_enviar_recibo.addEventListener("click", (e) => {
        e.preventDefault();
        var datos_enviar = new FormData(formula);        
        datos_enviar.append("accion_input", "enviar_recibo");// Agregar la accion_input al FormData
        if (datos_enviar.get('Motivo_input')=="" ) {
            alert("Llena el Motivo")
        } else {
            consultar_fm(datos_enviar, resumen, imp_tabla, document.getElementById("id_password").value);
        }        
    }) 
</script>

<!-- ----------------------------------------------------------------------------------------- -->
<!-- ------------------------------------- css style ----------------------------------------- -->
<!-- ----------------------------------------------------------------------------------------- -->

<style>
    #id_password {
        display: block;
        width: 150px;
        height: 30px;
        margin-bottom: 10px;
    }

    .class_recibo-button, .class_send_recibo-button{
        background-color: #0000FF; /* Cambiar el color de fondo */
        color: white; /* Cambiar el color del texto */
        padding: 7px 15px; /* Aumentar el espacio alrededor del texto */
        border: none; /* Eliminar el borde */
        border-radius: 5px; /* Añadir bordes redondeados */
        cursor: pointer; /* Cambiar el cursor del ratón */
    }

    .class_datos_recibo{
        background-color: #3f18c9;
        transform: translate(-700px);
        position: absolute;
    }
    .class_datos_recibo-show{
        background-color: rgba(150, 73, 22, 0.15);
        transform: translate(2px);
        position: relative;
        transition: transform .5s ease-in-out;
    }
    .class_btn_datos_recibo{
        background-color: #3f18c9;
        transform: translate(-700px);
        position: absolute;
    }
    .class_btn_datos_recibo-show{
        background-color: rgba(150, 73, 22, 0.15);
        transform: translate(2px);
        position: relative;
        transition: transform .5s ease-in-out;
    }

    .class_datos_dinero{
        background-color: rgba(150, 73, 22, 0.15);
        transform: translate(2px);
        position: relative;
        transition: transform .5s ease-in-out;
    }
    .class_datos_dinero-hidden{
        background-color: #3f18c9;
        transform: translate(-700px);
        position: absolute;
    }
    .class_btn_recibo{
        background-color: rgba(150, 73, 22, 0.15);
        transform: translate(2px);
        position: relative;
        transition: transform .5s ease-in-out;
    }
    .class_btn_recibo-hidden{
        background-color: #3f18c9;
        transform: translate(-700px);
        position: absolute;
    }

</style>