// Funciones para manejar los seguimientos en la tabla
document.addEventListener('DOMContentLoaded', function() {
    // Asegurarse de que estamos en la página correcta
    if (document.querySelector('.patients-container')) {
        // Inicializar listeners para editar/borrar seguimientos
        initializeFollowUpButtons();
    }
});

/**
 * Inicializa los botones y eventos para la gestión de seguimientos
 */
function initializeFollowUpButtons() {
    // Modal actual de agregar paciente
    const modal = document.getElementById('followUpModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.getElementById('cancelForm');

    if (openModalBtn) {
        openModalBtn.addEventListener('click', function() {
            modal.style.display = 'block';
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    // Cerrar modal si se hace clic fuera de él
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

/**
 * Muestra u oculta los detalles (formulario de edición) de un seguimiento
 */
function toggleFollowUpDetails(followUpGroupId) {
    const detailsElement = document.getElementById(`details-${followUpGroupId}`);
    
    // Si el elemento existe, alternar su visibilidad
    if (detailsElement) {
        // Si ya está visible, ocultarlo
        if (detailsElement.style.display === 'block' || detailsElement.style.display === '') {
            detailsElement.style.display = 'none';
        } else {
            // Ocultar otros detalles abiertos
            const allDetails = document.querySelectorAll('.followup-details');
            allDetails.forEach(detail => {
                detail.style.display = 'none';
            });
            
            // Mostrar el detalle seleccionado
            detailsElement.style.display = 'block';
        }
    }
}

/**
 * Actualiza un seguimiento existente
 */
function updateFollowUp(followUpGroupId) {
    const form = document.getElementById(`form-${followUpGroupId}`);
    const formData = new FormData(form);
    
    // Obtener el token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Enviar la solicitud AJAX para actualizar el seguimiento
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            alert('Seguimiento actualizado correctamente');
            
            // Ocultar el formulario
            toggleFollowUpDetails(followUpGroupId);
            
            // Recargar la página para ver los cambios
            window.location.reload();
        } else {
            // Mostrar mensaje de error
            alert('Error al actualizar el seguimiento: ' + JSON.stringify(data.errors || data.error));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud: ' + error);
    });
}

/**
 * Elimina un seguimiento
 */
function deleteFollowUp(followUpGroupId) {
    if (!confirm('¿Estás seguro de que deseas eliminar este seguimiento? Esta acción no se puede deshacer.')) {
        return;
    }
    
    // Obtener el token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Construir la URL de eliminación
    const deleteUrl = `/admin/seguimiento/${followUpGroupId}`;
    
    // Enviar la solicitud de eliminación
    fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            alert('Seguimiento eliminado correctamente');
            
            // Recargar la página para ver los cambios
            window.location.reload();
        } else {
            // Mostrar mensaje de error
            alert('Error al eliminar el seguimiento: ' + JSON.stringify(data.errors || data.error));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud: ' + error);
    });
}
