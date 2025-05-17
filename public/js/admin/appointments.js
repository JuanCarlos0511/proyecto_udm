document.addEventListener('DOMContentLoaded', function() {
    // Inicializar los elementos interactivos
    initializeElements();
});

/**
 * Inicializa todos los elementos interactivos de la página
 */
function initializeElements() {
    // Inicializar botones de filtro por estado
    initializeFilterButtons();
    
    // Inicializar el modal (si Bootstrap está disponible)
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        // Si usamos Bootstrap para los modales
        const appointmentModal = document.getElementById('startAppointmentModal');
        if (appointmentModal) {
            startAppointmentModalInstance = new bootstrap.Modal(appointmentModal);
        }
    }
}

/**
 * Inicializa los botones de filtro por estado
 */
function initializeFilterButtons() {
    const filterButtons = document.querySelectorAll('.btn-filter');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Añadir clase active al botón clickeado
            this.classList.add('active');
            
            // Aplicar filtro
            const status = this.getAttribute('data-status');
            filterAppointmentsByStatus(status);
        });
    });
}

/**
 * Filtra las citas por estado
 */
function filterAppointmentsByStatus(status) {
    const rows = document.querySelectorAll('.appointment-row');
    
    rows.forEach(row => {
        const statusElement = row.querySelector('.appointment-status');
        if (!statusElement) return;
        
        const rowStatus = statusElement.textContent.trim();
        
        if (status === 'all' || rowStatus.includes(status)) {
            row.style.display = '';
            
            // Si la fila tiene detalles expandidos, también mostrarlos
            const rowId = row.getAttribute('data-id');
            if (rowId) {
                const detailsRow = document.getElementById('details-' + rowId);
                if (detailsRow && detailsRow.style.display !== 'none') {
                    detailsRow.style.display = '';
                }
            }
        } else {
            row.style.display = 'none';
            
            // Ocultar también los detalles expandidos
            const rowId = row.getAttribute('data-id');
            if (rowId) {
                const detailsRow = document.getElementById('details-' + rowId);
                if (detailsRow) {
                    detailsRow.style.display = 'none';
                }
            }
        }
    });
}

/**
 * Alternar la visibilidad de los detalles de la cita
 */
function toggleAppointmentDetails(appointmentId) {
    const detailsRow = document.getElementById('details-' + appointmentId);
    if (detailsRow) {
        detailsRow.style.display = detailsRow.style.display === 'none' ? '' : 'none';
    }
}

/**
 * Aceptar una cita (cambiar su estado a "Agendado")
 */
function acceptAppointment(appointmentId) {
    if (!confirm('¿Desea aceptar esta cita?')) return;
    
    // Obtener el token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch(`/admin/citas/${appointmentId}/accept`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al aceptar la cita');
        }
        return response.json();
    })
    .then(data => {
        // Mostrar mensaje de éxito
        alert('Cita aceptada correctamente');
        
        // Recargar la página para reflejar los cambios
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al aceptar la cita: ' + error.message);
    });
}

/**
 * Mostrar el modal para iniciar una cita
 */
function showStartAppointmentModal(appointmentId) {
    // Recuperar datos de la cita
    fetch(`/admin/citas/${appointmentId}`)
        .then(response => response.json())
        .then(data => {
            // Rellenar los datos del paciente en el modal
            document.getElementById('patientName').textContent = data.appointment.user.name;
            document.getElementById('patientEmail').textContent = data.appointment.user.email;
            document.getElementById('patientPhone').textContent = data.appointment.user.phoneNumber || 'No disponible';
            document.getElementById('patientAge').textContent = data.appointment.user.age + ' años';
            document.getElementById('patientAddress').textContent = data.appointment.user.adress || 'No disponible';
            
            if (data.appointment.user.photo_path) {
                document.getElementById('patientAvatar').src = data.appointment.user.photo_path;
            }
            
            // Establecer el ID de la cita en el formulario
            document.getElementById('appointmentId').value = appointmentId;
            
            // Establecer el precio si existe
            if (data.appointment.price) {
                document.getElementById('appointmentTotal').value = data.appointment.price;
            }
            
            // Mostrar el modal
            const modal = document.getElementById('startAppointmentModal');
            modal.style.display = 'block';
            // Ajustar estilo para que parezca un modal
            modal.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Agregar backdrop si no existe
            if (!document.querySelector('.modal-backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos de la cita');
        });
}

/**
 * Cerrar el modal de iniciar cita
 */
function closeStartAppointmentModal() {
    const modal = document.getElementById('startAppointmentModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.body.classList.remove('modal-open');
    
    // Eliminar backdrop
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.remove();
    }
}

/**
 * Completar una cita con los datos del formulario
 */
function completeAppointment() {
    const appointmentId = document.getElementById('appointmentId').value;
    const notes = document.getElementById('appointmentNotes').value;
    const price = document.getElementById('appointmentTotal').value;
    
    if (!price || price <= 0) {
        alert('Por favor, ingrese un precio válido');
        return;
    }
    
    // Obtener el token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Enviar la solicitud para completar la cita
    fetch(`/admin/citas/${appointmentId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            notes: notes,
            price: price
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al completar la cita');
        }
        return response.json();
    })
    .then(data => {
        // Mostrar mensaje de éxito
        alert('Cita completada correctamente');
        
        // Cerrar el modal
        closeStartAppointmentModal();
        
        // Recargar la página para reflejar los cambios
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al completar la cita: ' + error.message);
    });
}

/**
 * Ver detalles de una cita
 */
function viewAppointmentDetails(appointmentId) {
    fetch(`/admin/citas/${appointmentId}`)
        .then(response => response.json())
        .then(data => {
            // Aquí podrías mostrar un modal con los detalles completos
            // o redirigir a una página de detalles
            window.location.href = `/admin/citas/${appointmentId}`;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los detalles de la cita');
        });
}

// Delegación de eventos para manejar los clicks en toda la tabla
document.addEventListener('click', function(e) {
    // Manejo del botón de dropdown (tres puntos)
    if (e.target.closest('.dropdown-toggle')) {
        e.preventDefault();
        e.stopPropagation();
        
        const currentDropdown = e.target.closest('.dropdown');
        console.log('Click en dropdown-toggle detectado', currentDropdown);
        
        // Cerrar todos los otros dropdowns
        document.querySelectorAll('.dropdown.active').forEach(dropdown => {
            if (dropdown !== currentDropdown) {
                dropdown.classList.remove('active');
                // Asegurarse de que los menús se oculten completamente
                const menu = dropdown.querySelector('.dropdown-menu');
                if (menu) menu.style.display = 'none';
            }
        });
        
        // Toggle del dropdown actual
        const isActive = currentDropdown.classList.toggle('active');
        
        // Manejar explícitamente la visibilidad del menú
        const menu = currentDropdown.querySelector('.dropdown-menu');
        if (menu) {
            if (isActive) {
                // Al abrir el menú, aseguramos que esté por encima de todo
                menu.style.display = 'block';
                menu.style.zIndex = '9999';
                // Mover el menú al final del body para evitar problemas de z-index
                document.body.appendChild(menu);
                
                // Posicionar el menú correctamente junto al botón
                const buttonRect = currentDropdown.querySelector('.dropdown-toggle').getBoundingClientRect();
                menu.style.position = 'absolute';
                menu.style.top = (buttonRect.bottom + window.scrollY) + 'px';
                menu.style.right = (window.innerWidth - buttonRect.right) + 'px';
                
                // Agregar un ID al menú para poder identificarlo después
                menu.dataset.dropdownId = currentDropdown.dataset.id || '';
            } else {
                // Al cerrar, volver a ubicar el menú dentro del dropdown
                menu.style.display = 'none';
                currentDropdown.appendChild(menu);
            }
        }
        
        return;
    }
    
    // 2. Cerrar dropdowns cuando se hace click fuera
    if (!e.target.closest('.dropdown') && !e.target.closest('.dropdown-menu')) {
        document.querySelectorAll('.dropdown.active').forEach(dropdown => {
            dropdown.classList.remove('active');
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) menu.style.display = 'none';
        });
        
        // Verificar si hay menús en el body y devolverlos a sus dropdowns
        document.querySelectorAll('body > .dropdown-menu').forEach(menu => {
            const dropdownId = menu.dataset.dropdownId;
            const dropdown = document.querySelector(`.dropdown[data-id="${dropdownId}"]`);
            if (dropdown) {
                menu.style.display = 'none';
                dropdown.appendChild(menu);
            }
        });
    }
});

// Inicializar los eventos específicos
function initializeAppointmentEvents() {
    console.log('Inicializando eventos de citas');
    
    // Manejar eventos de aceptar cita
    document.querySelectorAll('.accept-appointment').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const appointmentId = this.dataset.id;
            acceptAppointment(appointmentId);
        });
    });
    
    // Manejar eventos de cancelar cita
    document.querySelectorAll('.cancel-appointment').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const appointmentId = this.dataset.id;
            cancelAppointment(appointmentId);
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');
    
    // Inicializar los eventos específicos
    initializeAppointmentEvents();

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

// Función para actualizar una fila de cita sin recargar la página completa
function updateAppointmentRow(appointment) {
    console.log('Actualizando fila de cita:', appointment);
    
    // Encontrar la fila correspondiente a la cita
    const row = document.querySelector(`.appointment-row[data-id="${appointment.id}"]`);
    if (!row) {
        console.error('No se pudo encontrar la fila para la cita:', appointment.id);
        return;
    }
    
    // Obtener los elementos TD de la fila
    const cells = row.querySelectorAll('td');
    if (cells.length < 6) {
        console.error('La fila no tiene suficientes celdas');
        return;
    }
    
    // Actualizar la fecha y hora
    const date = new Date(appointment.date);
    const dateOptions = { day: '2-digit', month: '2-digit', year: 'numeric' };
    const timeOptions = { hour: '2-digit', minute: '2-digit' };
    
    // La fecha está en la celda 2
    cells[2].textContent = date.toLocaleDateString('es-ES', dateOptions);
    // La hora está en la celda 3
    cells[3].textContent = date.toLocaleTimeString('es-ES', timeOptions);
    
    // Actualizamos el estado en la celda 4
    const statusSpan = cells[4].querySelector('.appointment-status');
    if (statusSpan) {
        statusSpan.textContent = appointment.status;
        // Actualizar clases de estado
        statusSpan.className = `appointment-status status-${appointment.status.toLowerCase()}`;
    }
    
    console.log('Fila actualizada correctamente');
}

function updateAppointment(appointmentId, form) {
    console.log('Inicio de updateAppointment');
    
    // Detener cualquier comportamiento por defecto
    event.preventDefault();
    event.stopPropagation();
    
    const formData = new FormData(form);
    const date = formData.get('date');
    const time = formData.get('time');
    const doctorId = formData.get('doctor_id');
    
    // Como estamos teniendo problemas con la URL, usamos la URL actual donde estamos
    // en lugar de intentar construir una URL específica para cada cita
    const baseURL = window.location.origin;
    const url = `${baseURL}/admin/tablero/citas-todas`; // Usar la ruta donde estamos actualmente
    console.log(`Usando URL de la vista actual: ${url}`);
    
    // Usar XMLHttpRequest en lugar de fetch para mayor control
    const xhr = new XMLHttpRequest();
    xhr.open('PUT', url, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('Accept', 'application/json');
    
    xhr.onload = function() {
        console.log('Respuesta recibida:', xhr.status, xhr.statusText);
        console.log('Respuesta completa:', xhr.responseText);
        
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                const data = JSON.parse(xhr.responseText);
                console.log('Datos recibidos:', data);
                if (data.message) {
                    alert(data.message);
                    if (data.appointment) {
                        // En lugar de recargar toda la página, actualizar solo los datos necesarios
                        updateAppointmentRow(data.appointment);
                        
                        // Cerrar el formulario de edición
                        const editForm = document.querySelector('.edit-form-container');
                        if (editForm) {
                            editForm.remove();
                        }
                        
                        // Re-inicializar los eventos de citas
                        initializeAppointmentEvents();
                    }
                }
            } catch (e) {
                console.error('Error al procesar la respuesta JSON:', e);
                alert('Respuesta recibida pero no es JSON válido');
            }
        } else {
            console.error('Error en la respuesta:', xhr.status, xhr.statusText);
            alert(`Error al actualizar la cita: ${xhr.status} ${xhr.statusText}`);
        }
    };
    
    xhr.onerror = function() {
        console.error('Error de red en la solicitud');
        alert('Error de red al intentar actualizar la cita');
    };
    
    const data = JSON.stringify({
        appointment_id: appointmentId,
        date: `${date} ${time}`,
        doctor_id: doctorId
    });
    
    console.log('Enviando datos:', data);
    xhr.send(data);
}
