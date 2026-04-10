$(function () {
    const urlBase = "index.php";

    // 1. Lógica de Inicio de Sesión
    $("#formLogin").on("submit", function (event) {
        event.preventDefault();
        let username = $("#username").val();
        let password = $("#password").val();

        if (username === "" || password === "") {
            alert("Debe completar todos los campos");
            return;
        }

        // Enviamos 'option' dentro del objeto para que el index.php lo reconozca
        $.post(urlBase, 
            {
                option: "login", // Acción para el controlador
                username: username,
                password: password
            },
            function (data) {
                try {
                    const res = JSON.parse(data);
                    if (res.response === "00") {
                        // Redirección dinámica según el rol definido en bd.sql ('admin' o 'usuario')
                        window.location.href = res.rol === 'admin' ? "index.php?page=admin" : "index.php?page=talleres";
                    } else {
                        alert(res.message);
                    }
                } catch (e) {
                    console.error("Error al procesar JSON. El servidor respondió:", data);
                    alert("Error de autenticación. Revisa la consola.");
                }
            }
        );
    });

    // 2. Lógica de Registro
    $("#formRegistro").on("submit", function (event) {
        event.preventDefault();
        let username = $("#username").val();
        let password = $("#password").val();

        if (username === "" || password === "") {
            alert("Todos los campos son obligatorios");
            return;
        }

        $.post(urlBase, 
            {
                option: "registro", // Acción para el controlador
                username: username,
                password: password
            },
            function (data) {
                try {
                    const res = JSON.parse(data);
                    if (res.response === "00") {
                        alert("Registro exitoso, ahora puedes iniciar sesión.");
                        window.location.href = "index.php?page=login";
                    } else {
                        alert(res.message);
                    }
                } catch (e) {
                    console.error("Error en registro:", data);
                }
            }
        );
    });

    // 3. Lógica de Cerrar Sesión 
    $(document).on("click", "#btnLogout", function () {
        $.post(urlBase, { option: "logout" }, function (data) {
            try {
                const res = JSON.parse(data);
                if (res.response === "00") {
                    window.location.href = "index.php?page=login";
                }
            } catch (e) {
                // En caso de error, forzamos la salida al login
                window.location.href = "index.php?page=login";
            }
        });
    });
});
