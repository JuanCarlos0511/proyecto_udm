document.addEventListener('DOMContentLoaded', function() {
    // Elementos DOM
    const activeAppointments = document.getElementById('activeAppointments');
    const pendingAppointments = document.getElementById('pendingAppointments');
    const scheduleNewAppointment = document.getElementById('scheduleNewAppointment');

    // Event Listeners
    scheduleNewAppointment.addEventListener('click', () => {
        window.location.href = 'doctor-appointment.html';
    });

    // Funciones para cargar las citas
    function loadActiveAppointments() {
        // TODO: Implementar carga de citas activas desde el backend
        // Por ahora, mostraremos datos de ejemplo
        const appointments = [
            { id: 1, patient: 'Juan Pérez', time: '10:00 AM', date: '2025-04-01' },
            { id: 2, patient: 'María García', time: '11:30 AM', date: '2025-04-01' }
        ];

        appointments.forEach(appointment => {
            const appointmentElement = createAppointmentElement(appointment);
            activeAppointments.appendChild(appointmentElement);
        });
    }

    function loadPendingAppointments() {
        // TODO: Implementar carga de citas pendientes desde el backend
        // Por ahora, mostraremos datos de ejemplo
        const appointments = [
            { id: 3, patient: 'Carlos López', time: '3:00 PM', date: '2025-04-02' },
            { id: 4, patient: 'Ana Martínez', time: '4:30 PM', date: '2025-04-02' }
        ];

        appointments.forEach(appointment => {
            const appointmentElement = createPendingAppointmentElement(appointment);
            pendingAppointments.appendChild(appointmentElement);
        });
    }

    function createAppointmentElement(appointment) {
        const div = document.createElement('div');
        div.className = 'appointment-item';
        div.innerHTML = `
            <div class="appointment-info">
                <h4>${appointment.patient}</h4>
                <p>Fecha: ${appointment.date}</p>
                <p>Hora: ${appointment.time}</p>
            </div>
            <div class="appointment-actions">
                <button class="action-btn view-btn">Ver detalles</button>
                <button class="action-btn cancel-btn">Cancelar</button>
            </div>
        `;
        return div;
    }

    function createPendingAppointmentElement(appointment) {
        const div = document.createElement('div');
        div.className = 'pending-item';
        div.innerHTML = `
            <div class="appointment-info">
                <h4>${appointment.patient}</h4>
                <p>Fecha: ${appointment.date}</p>
                <p>Hora: ${appointment.time}</p>
            </div>
            <div class="appointment-actions">
                <button class="action-btn confirm-btn">Confirmar</button>
                <button class="action-btn reject-btn">Rechazar</button>
            </div>
        `;
        return div;
    }

    // Inicializar la página
    loadActiveAppointments();
    loadPendingAppointments();
});
