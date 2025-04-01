document.addEventListener('DOMContentLoaded', function() {
    // Elementos DOM
    const patientHistory = document.getElementById('patientHistory');

    // Cargar historial de pacientes
    function loadPatientHistory() {
        // TODO: Implementar carga de historial desde el backend
        // Por ahora, mostraremos datos de ejemplo
        const appointments = [
            {
                id: 1,
                patient: 'Juan Pérez',
                date: '2025-04-01',
                startTime: '10:00',
                endTime: '10:30',
                status: 'Completada'
            },
            {
                id: 2,
                patient: 'María García',
                date: '2025-04-01',
                startTime: '11:00',
                endTime: '11:30',
                status: 'Pendiente'
            },
            {
                id: 3,
                patient: 'Carlos López',
                date: '2025-04-02',
                startTime: '09:00',
                endTime: '09:30',
                status: 'Cancelada'
            }
        ];

        appointments.forEach(appointment => {
            const row = createAppointmentRow(appointment);
            patientHistory.appendChild(row);
        });
    }

    function createAppointmentRow(appointment) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${appointment.id}</td>
            <td>${appointment.patient}</td>
            <td>${appointment.date}</td>
            <td>${appointment.startTime}</td>
            <td>${appointment.endTime}</td>
            <td><span class="status ${appointment.status.toLowerCase()}">${appointment.status}</span></td>
        `;
        return tr;
    }

    // Inicializar la página
    loadPatientHistory();
});
