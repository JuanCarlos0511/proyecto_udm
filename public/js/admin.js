document.addEventListener('DOMContentLoaded', function() {
    // Sample data for active appointments
    const activeAppointmentsData = [
        {
            title: 'Consulta General',
            time: '10:30 AM',
            patient: 'Carlos Rodríguez',
            doctor: 'Dra. María González',
            type: 'Presencial'
        },
        {
            title: 'Terapia Física',
            time: '11:45 AM',
            patient: 'Ana Martínez',
            doctor: 'Dr. Juan Pérez',
            type: 'Domicilio'
        }
    ];

    // Sample data for pending appointments
    const pendingAppointmentsData = [
        {
            title: 'Consulta Pediátrica',
            time: '2:00 PM',
            patient: 'Luis Hernández',
            doctor: 'Dr. Roberto Sánchez',
            type: 'Presencial'
        },
        {
            title: 'Consulta Dermatológica',
            time: '3:30 PM',
            patient: 'Elena Gómez',
            doctor: 'Dra. Patricia López',
            type: 'Presencial'
        },
        {
            title: 'Terapia Respiratoria',
            time: '5:00 PM',
            patient: 'Miguel Torres',
            doctor: 'Dr. Javier Ramírez',
            type: 'Domicilio'
        }
    ];

    // Populate active appointments
    const activeAppointmentsContainer = document.getElementById('activeAppointments');
    if (activeAppointmentsContainer) {
        activeAppointmentsData.forEach(appointment => {
            const appointmentCard = createAppointmentCard(appointment, true);
            activeAppointmentsContainer.appendChild(appointmentCard);
        });
    }

    // Populate pending appointments
    const pendingAppointmentsContainer = document.getElementById('pendingAppointments');
    if (pendingAppointmentsContainer) {
        pendingAppointmentsData.forEach(appointment => {
            const appointmentCard = createAppointmentCard(appointment, false);
            pendingAppointmentsContainer.appendChild(appointmentCard);
        });
    }

    // Function to create appointment card
    function createAppointmentCard(appointment, isActive) {
        const card = document.createElement('div');
        card.className = 'appointment-card';

        const cardHeader = document.createElement('div');
        cardHeader.className = 'appointment-card-header';

        const cardTitle = document.createElement('div');
        cardTitle.className = 'appointment-card-title';
        cardTitle.textContent = appointment.title;

        const cardTime = document.createElement('div');
        cardTime.className = 'appointment-card-time';
        cardTime.textContent = appointment.time;

        cardHeader.appendChild(cardTitle);
        cardHeader.appendChild(cardTime);

        const cardDetails = document.createElement('div');
        cardDetails.className = 'appointment-card-details';

        const patientInfo = document.createElement('p');
        patientInfo.textContent = `Paciente: ${appointment.patient}`;

        const doctorInfo = document.createElement('p');
        doctorInfo.textContent = `Doctor: ${appointment.doctor}`;

        const typeInfo = document.createElement('p');
        typeInfo.textContent = `Tipo: ${appointment.type}`;

        cardDetails.appendChild(patientInfo);
        cardDetails.appendChild(doctorInfo);
        cardDetails.appendChild(typeInfo);

        const cardActions = document.createElement('div');
        cardActions.className = 'appointment-card-actions';

        const viewBtn = document.createElement('button');
        viewBtn.className = 'view-btn';
        viewBtn.textContent = 'Ver Detalles';
        viewBtn.addEventListener('click', function() {
            alert(`Detalles de la cita: ${appointment.title} con ${appointment.doctor}`);
        });

        cardActions.appendChild(viewBtn);

        if (isActive) {
            // For active appointments
            const rescheduleBtn = document.createElement('button');
            rescheduleBtn.className = 'reschedule-admin-btn';
            rescheduleBtn.textContent = 'Reprogramar';
            rescheduleBtn.addEventListener('click', function() {
                alert(`Reprogramar cita: ${appointment.title}`);
            });

            const cancelBtn = document.createElement('button');
            cancelBtn.className = 'cancel-admin-btn';
            cancelBtn.textContent = 'Cancelar';
            cancelBtn.addEventListener('click', function() {
                alert(`Cancelar cita: ${appointment.title}`);
            });

            cardActions.appendChild(rescheduleBtn);
            cardActions.appendChild(cancelBtn);
        } else {
            // For pending appointments
            const confirmBtn = document.createElement('button');
            confirmBtn.className = 'confirm-btn';
            confirmBtn.textContent = 'Confirmar';
            confirmBtn.addEventListener('click', function() {
                alert(`Confirmar cita: ${appointment.title}`);
            });

            const rescheduleBtn = document.createElement('button');
            rescheduleBtn.className = 'reschedule-admin-btn';
            rescheduleBtn.textContent = 'Reprogramar';
            rescheduleBtn.addEventListener('click', function() {
                alert(`Reprogramar cita: ${appointment.title}`);
            });

            cardActions.appendChild(confirmBtn);
            cardActions.appendChild(rescheduleBtn);
        }

        card.appendChild(cardHeader);
        card.appendChild(cardDetails);
        card.appendChild(cardActions);

        return card;
    }

    // Schedule new appointment button functionality
    const scheduleNewAppointmentBtn = document.getElementById('scheduleNewAppointment');
    if (scheduleNewAppointmentBtn) {
        scheduleNewAppointmentBtn.addEventListener('click', function() {
            window.location.href = '/appointment';
        });
    }
});
