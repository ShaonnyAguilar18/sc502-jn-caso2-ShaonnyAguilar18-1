$(function () {
    // Función para cargar los talleres al iniciar
    const cargarTalleres = () => {
        $.ajax({
            
            url: 'index.php?option=talleres_json', 
            type: 'GET',
            dataType: 'json',
            success: function (talleres) {
                let html = '';
                talleres.forEach(taller => {
                    html += `
                        <tr>
                            <td>${taller.nombre}</td>
                            <td>${taller.descripcion}</td>
                            <td><span class="badge bg-info text-dark">${taller.cupo_disponible}</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm btn-solicitar" data-id="${taller.id}">
                                    Solicitar Inscripción
                                </button>
                            </td>
                        </tr>`;
                });
                
                if (talleres.length === 0) {
                    html = '<tr><td colspan="4" class="text-center">No hay talleres disponibles con cupos.</td></tr>';
                }

                $('#contenedor-talleres').html(html);
            },
            error: function() {
                console.error("Error al cargar los talleres");
            }
        });
    };

    cargarTalleres();

    $(document).on('click', '.btn-solicitar', function () {
        const tallerId = $(this).data('id');
        const boton = $(this);

        boton.prop('disabled', true);
        
        $.ajax({
            // SE ENVÍA A index.php Y SE AGREGA LA 'option' EN DATA
            url: 'index.php', 
            type: 'POST',
            data: { 
                option: 'solicitar', 
                taller_id: tallerId 
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert(response.message); 
                    cargarTalleres(); 
                } else {
                    alert("Error: " + response.error);
                }
            },
            complete: function() {
                boton.prop('disabled', false);
            }
        });
    });
});