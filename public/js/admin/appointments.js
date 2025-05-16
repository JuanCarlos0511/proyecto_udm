document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');
    
    // Manejo de los menús desplegables
    const dropdowns = document.querySelectorAll('.dropdown');
    console.log('Dropdowns encontrados:', dropdowns.length);
    
    // Cerrar todos los dropdowns cuando se hace clic fuera
    document.addEventListener('click', function(event) {
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    });

    // Toggle dropdown al hacer clic en el botón
    dropdowns.forEach(dropdown => {
        const toggleBtn = dropdown.querySelector('.dropdown-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Cerrar otros dropdowns
                dropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.classList.remove('active');
                    }
                });
                
                dropdown.classList.toggle('active');
            });
        }
    });

    // Manejar el clic en el botón de aceptar cita
    const acceptButtons = document.querySelectorAll('.accept-appointment');
    console.log('Botones de aceptar encontrados:', acceptButtons.length);
    
    acceptButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const appointmentId = this.dataset.id;
            acceptAppointment(appointmentId);
        });
    });

    // Manejar el clic en el botón de cancelar cita
    document.querySelectorAll('.cancel-appointment').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const appointmentId = this.dataset.id;
            cancelAppointment(appointmentId);
        });
    });

    // Ya no necesitamos este código porque ahora usamos onclick directamente en el HTML

    // Ya no necesitamos este código porque ahora usamos onclick directamente en el HTML

    // Ya no necesitamos este código porque ahora usamos onsubmit directamente en el HTML
});

function acceptAppointment(appointmentId) {
    if (confirm('¿Estás seguro de que deseas aceptar esta cita?')) {
        fetch(`/admin/citas/${appointmentId}/accept`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                if (data.appointment) {
                    location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al aceptar la cita');
        });
    }
}

function cancelAppointment(appointmentId) {
    if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
        fetch(`/admin/citas/${appointmentId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                if (data.appointment) {
                    location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cancelar la cita');
        });
    }
}

// Función global para alternar la visibilidad de los detalles de la cita
function toggleAppointmentDetails(appointmentId) {
    console.log('Toggle details for appointment:', appointmentId);
    
    // Buscar la fila de detalles
    const detailsRow = document.getElementById(`details-${appointmentId}`);
    console.log('Details row:', detailsRow);
    
    if (!detailsRow) {
        console.error(`No se encontró la fila de detalles para la cita ${appointmentId}`);
        alert('Error: No se pudo encontrar la sección de detalles para esta cita.');
        return;
    }
    
    const appointmentRow = document.querySelector(`.appointment-row[data-id="${appointmentId}"]`);
    console.log('Appointment row:', appointmentRow);

    // Ocultar todos los detalles abiertos
    document.querySelectorAll('.appointment-details').forEach(row => {
        if (row.id !== `details-${appointmentId}`) {
            row.style.display = 'none';
        }
    });

    // Remover la clase editing de todas las filas
    document.querySelectorAll('.appointment-row').forEach(row => {
        row.classList.remove('editing');
    });

    // Alternar la visibilidad de los detalles seleccionados
    console.log('Current display style:', detailsRow.style.display);
    
    if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
        console.log('Showing details row');
        detailsRow.style.display = 'table-row';
        if (appointmentRow) appointmentRow.classList.add('editing');
        
        // Scroll a la fila de detalles
        detailsRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        console.log('Hiding details row');
        detailsRow.style.display = 'none';
        if (appointmentRow) appointmentRow.classList.remove('editing');
    }
}

function updateAppointment(appointmentId, form) {
    const formData = new FormData(form);
    const date = formData.get('date');
    const time = formData.get('time');
    const doctorId = formData.get('doctor_id');

    fetch(`/admin/citas/${appointmentId}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            date: `${date} ${time}`,
            doctor_id: doctorId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            if (data.appointment) {
                location.reload();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar la cita');
    });
}
