document.addEventListener('DOMContentLoaded', function() {
    // Elementos DOM
    const seguimientoAppointments = document.getElementById('seguimientoAppointments');
    const appointmentDateInfo = document.getElementById('appointmentDateInfo');
    const appointmentNotes = document.getElementById('appointmentNotes');
    const scheduleNewAppointment = document.getElementById('scheduleNewAppointment');

    // Event Listeners
    scheduleNewAppointment.addEventListener('click', () => {
        window.location.href = 'doctor-appointment.html';
    });

    // Cargar citas con seguimiento
    function loadSeguimientoAppointments() {
        // TODO: Implementar carga de citas desde el backend
        // Por ahora, mostraremos datos de ejemplo
        const appointments = [
            {
                id: 1,
                patient: 'Juan Pérez',
                date: '2025-04-01',
                time: '10:00',
                notes: 'Paciente requiere seguimiento semanal por 1 mes.'
            },
            {
                id: 2,
                patient: 'María García',
                date: '2025-04-02',
                time: '11:00',
                notes: 'Seguimiento quincenal por 2 meses.'
            }
        ];

        appointments.forEach(appointment => {
            const appointmentElement = createAppointmentElement(appointment);
            seguimientoAppointments.appendChild(appointmentElement);
        });
    }

    function createAppointmentElement(appointment) {
        const div = document.createElement('div');
        div.className = 'seguimiento-item';
        div.innerHTML = `
            <div class="appointment-info">
                <h4>${appointment.patient}</h4>
                <p>Fecha: ${appointment.date}</p>
                <p>Hora: ${appointment.time}</p>
            </div>
            <div class="appointment-actions">
                <button class="action-btn view-btn" data-id="${appointment.id}">Ver detalles</button>
            </div>
        `;

        // Event listener para ver detalles
        div.querySelector('.view-btn').addEventListener('click', () => {
            showAppointmentDetails(appointment);
        });

        return div;
    }

    function showAppointmentDetails(appointment) {
        // Mostrar información de la fecha
        appointmentDateInfo.innerHTML = `
            <div class="date-row">
                <span>${appointment.patient}</span>
                <span>${appointment.date} ${appointment.time}</span>
            </div>
        `;

        // Mostrar notas
        appointmentNotes.value = appointment.notes;
    }

    // Inicializar la página
    loadSeguimientoAppointments();
});
