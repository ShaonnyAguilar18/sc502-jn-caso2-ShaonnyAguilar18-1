$(function () {
    // 1. Función para cargar las solicitudes pendientes al entrar
    const cargarSolicitudes = () => {
        $.ajax({
            url: 'index.php?option=solicitudes_json',
            type: 'GET',
            dataType: 'json',
            success: function (solicitudes) {
                let html = '';
                solicitudes.forEach(s => {
                    html += `
                        <tr>
                            <td>${s.id}</td>
                            <td>${s.taller}</td>
                            <td>${s.usuario}</td>
                            <td>${s.fecha}</td>
                            <td>
                                <button class="btn btn-success btn-sm btn-aprobar" data-id="${s.id}">Aprobar</button>
                                <button class="btn btn-danger btn-sm btn-rechazar" data-id="${s.id}">Rechazar</button>
                            </td>
                        </tr>`;
                });

                if (solicitudes.length === 0) {
                    html = '<tr><td colspan="5" class="text-center">No hay solicitudes pendientes.</td></tr>';
                }

                $('#solicitudes-body').html(html);
            }
        });
    };

    cargarSolicitudes();

    // 2. Manejar clic en botón Aprobar
    $(document).on('click', '.btn-aprobar', function () {
        const idSolicitud = $(this).data('id');
        const fila = $(this).closest('tr');

        $.ajax({
            url: 'index.php', // Se envía a la raíz
            type: 'POST',
            data: { 
                option: 'aprobar', 
                id_solicitud: idSolicitud 
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert("Solicitud aprobada con éxito");
                    fila.fadeOut(); 
                } else {
                    alert("Error: " + response.error);
                }
            }
        });
    });

    // 3. Manejar clic en botón Rechazar
    $(document).on('click', '.btn-rechazar', function () {
        const idSolicitud = $(this).data('id');
        const fila = $(this).closest('tr');

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: { 
                option: 'rechazar', 
                id_solicitud: idSolicitud 
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert("Solicitud rechazada");
                    fila.fadeOut();
                } else {
                    alert("Error al procesar el rechazo");
                }
            }
        });
    });
});