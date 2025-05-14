// Seguimiento Controller
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    // Initialize components
    initTabs();
    initCalendar();
    loadAppointmentsHistory();
    loadUpcomingAppointments();
    loadDoctors();
    initCharts();
    loadDoctorNotes();
    
    // Tab functionality
    function initTabs() {
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                
                // Add active class to clicked button and corresponding pane
                button.classList.add('active');
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
    }
    
    // Initialize calendar with Flatpickr
    function initCalendar() {
        const calendarElement = document.getElementById('appointmentsCalendar');
        if (!calendarElement) return;
        
        // Sample appointments data for calendar
        const appointments = [
            { date: '2025-05-10', time: '10:00', doctor: 'Dr. Rosa Elba Martínez', specialty: 'Electroterapia' },
            { date: '2025-05-15', time: '14:30', doctor: 'Dr. Isaac Solís Martínez', specialty: 'Hidroterapia' },
            { date: '2025-05-22', time: '11:00', doctor: 'Dr. Karla Lorena Martínez Ávila', specialty: 'Mecanoterapia' }
        ];
        
        // Get appointment dates for highlighting in calendar
        const appointmentDates = appointments.map(appointment => appointment.date);
        
        // Initialize Flatpickr
        const calendar = flatpickr(calendarElement, {
            inline: true,
            locale: 'es',
            dateFormat: 'Y-m-d',
            onReady: function() {
                highlightAppointmentDates(this, appointmentDates);
            },
            onChange: function(selectedDates, dateStr) {
                showAppointmentsForDate(dateStr);
            }
        });
    }
    
    // Highlight dates with appointments in the calendar
    function highlightAppointmentDates(calendar, dates) {
        const calendarDays = calendar.days.childNodes;
        
        calendarDays.forEach(day => {
            if (!day.dateObj) return;
            
            const dateStr = formatDate(day.dateObj);
            if (dates.includes(dateStr)) {
                day.classList.add('has-appointment');
            }
        });
    }
    
    // Format date as YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // Show appointments for selected date
    function showAppointmentsForDate(dateStr) {
        // This would typically fetch appointments for the selected date from the server
        console.log(`Showing appointments for ${dateStr}`);
    }
    
    // Load appointments history
    function loadAppointmentsHistory() {
        const appointmentsHistoryList = document.getElementById('appointmentsHistoryList');
        if (!appointmentsHistoryList) return;
        
        // Datos de ejemplo para pruebas
        const demoData = [
            {
                id: 1,
                doctor: "Dr. Juan Pérez",
                date: "2025-05-13",
                time: "09:00",
                status: "Completado",
                timeToHuman: "Hoy",
                service: "Consulta General"
            },
            {
                id: 2,
                doctor: "Dra. María López",
                date: "2025-05-12",
                time: "10:30",
                status: "Completado",
                timeToHuman: "Ayer",
                service: "Limpieza Dental"
            },
            {
                id: 3,
                doctor: "Dr. Carlos Rodríguez",
                date: "2025-05-11",
                time: "11:00",
                status: "Completado",
                timeToHuman: "Antier",
                service: "Extracción"
            }
        ];
        
        renderAppointmentsHistory(demoData);
    }
    
    // Render appointments history
    function renderAppointmentsHistory(appointments) {
        const appointmentsHistoryList = document.getElementById('appointmentsHistoryList');
        if (!appointmentsHistoryList) return;
        
        // Clear existing content
        appointmentsHistoryList.innerHTML = '';
        
        if (appointments.length === 0) {
            appointmentsHistoryList.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-message">No hay citas registradas</td>
                </tr>
            `;
            return;
        }
        
        appointments.forEach(appointment => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${appointment.timeToHuman}</td>
                <td>${appointment.doctor}</td>
                <td>${appointment.specialty}</td>
                <td>${appointment.modality}</td>
                <td><span class="appointment-status ${getStatusClass(appointment.status)}">${appointment.status}</span></td>
                <td>
                    <button class="action-btn" onclick="viewAppointmentDetails(${appointment.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            `;
            
            appointmentsHistoryList.appendChild(row);
        });
        
        // Add event listeners to view buttons
        const viewButtons = appointmentsHistoryList.querySelectorAll('.btn-view');
        viewButtons.forEach(button => {
            button.addEventListener('click', () => {
                const appointmentId = button.getAttribute('data-id');
                viewAppointmentDetails(appointmentId);
            });
        });
    }
    
    // View appointment details
    function viewAppointmentDetails(appointmentId) {
        // This would typically open a modal with appointment details
        console.log(`Viewing appointment ${appointmentId}`);
    }
    
    // Load upcoming appointments
    function loadUpcomingAppointments() {
        const upcomingAppointmentsList = document.getElementById('upcomingAppointmentsList');
        if (!upcomingAppointmentsList) return;
        
        // Fetch upcoming appointments from API
        // For now, we'll use sample data
        const upcomingAppointments = [
            { id: 4, date: '2025-05-10', time: '10:00', doctor: 'Dr. Rosa Elba Martínez', subject: 'Electroterapia', modality: 'Consultorio', status: 'Agendado' },
            { id: 5, date: '2025-05-15', time: '14:30', doctor: 'Dr. Isaac Solís Martínez', subject: 'Hidroterapia', modality: 'Domicilio', status: 'Agendado' },
            { id: 6, date: '2025-05-22', time: '11:00', doctor: 'Dr. Karla Lorena Martínez Ávila', subject: 'Mecanoterapia', modality: 'Consultorio', status: 'Agendado' }
        ];
        
        renderUpcomingAppointments(upcomingAppointments);
    }
    
    // Render upcoming appointments
    function renderUpcomingAppointments(appointments) {
        const upcomingAppointmentsList = document.getElementById('upcomingAppointmentsList');
        if (!upcomingAppointmentsList) return;
        
        upcomingAppointmentsList.innerHTML = '';
        
        if (appointments.length === 0) {
            upcomingAppointmentsList.innerHTML = '<div class="empty-message">No hay citas programadas</div>';
            return;
        }
        
        appointments.forEach(appointment => {
            const appointmentCard = document.createElement('div');
            appointmentCard.className = 'appointment-card';
            
            // Format date and time
            const appointmentDate = new Date(appointment.date);
            const formattedDate = appointmentDate.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            appointmentCard.innerHTML = `
                <div class="appointment-header">
                    <div class="appointment-datetime">
                        <i class="fas fa-calendar-alt"></i>
                        <span>${formattedDate}</span>
                        <i class="fas fa-clock"></i>
                        <span>${appointment.time}</span>
                    </div>
                </div>
                <div class="appointment-details">
                    <div class="detail-row">
                        <div class="detail-label">Doctor:</div>
                        <div class="detail-value">${appointment.doctor}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Especialidad:</div>
                        <div class="detail-value">${appointment.specialty}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Modalidad:</div>
                        <div class="detail-value">${appointment.modality}</div>
                    </div>
                </div>
                <div class="appointment-actions">
                    <button class="btn-action btn-view" onclick="viewAppointment(${appointment.id})">Ver</button>
                    ${appointment.status === 'Agendado' ? `
                        <button class="btn-action btn-cancel" onclick="cancelAppointment(${appointment.id})">Cancelar</button>
                        <button class="btn-action btn-reschedule" onclick="rescheduleAppointment(${appointment.id})">Reagendar</button>
                    ` : ''}
                </div>
            `;
            
            upcomingAppointmentsList.appendChild(appointmentCard);
        });
    }
    
    // Cancel appointment
    function cancelAppointment(appointmentId) {
        // This would typically show a confirmation dialog and then cancel the appointment
        console.log(`Canceling appointment ${appointmentId}`);
    }
    
    // Reschedule appointment
    function rescheduleAppointment(appointmentId) {
        // This would typically show a dialog to select new date/time
        console.log(`Rescheduling appointment ${appointmentId}`);
    }
    
    // View appointment
    function viewAppointment(appointmentId) {
        // This would typically show appointment details in a modal
        console.log(`Viewing appointment ${appointmentId}`);
    }
    
    // Load doctors
    function loadDoctors() {
        const doctorsListElement = document.getElementById('doctorsList');
        if (!doctorsListElement) return;
        
        // Sample data for doctors
        const doctors = [
            { 
                id: 1, 
                name: 'Dr. Rosa Elba', 
                lastName: 'Martínez', 
                specialty: 'Electroterapia',
                nextAppointment: '2025-05-20'
            },
            { 
                id: 2, 
                name: 'Dr. Isaac', 
                lastName: 'Solís Martínez', 
                specialty: 'Hidroterapia',
                nextAppointment: '2025-05-25'
            },
            { 
                id: 3, 
                name: 'Dr. Karla Lorena', 
                lastName: 'Martínez Ávila', 
                specialty: 'Mecanoterapia',
                nextAppointment: '2025-06-02'
            }
        ];
        
        renderDoctors(doctors);
    }
    
    // Render doctors
    function renderDoctors(doctors) {
        const doctorsListElement = document.getElementById('doctorsList');
        if (!doctorsListElement) return;
        
        doctorsListElement.innerHTML = '';
        
        if (doctors.length === 0) {
            doctorsListElement.innerHTML = '<div class="empty-message">No hay doctores asignados</div>';
            return;
        }
        
        doctors.forEach(doctor => {
            const doctorCard = document.createElement('div');
            doctorCard.className = 'doctor-card';
            
            // Crear las iniciales para el avatar si no hay imagen
            const initials = `${doctor.name[0]}${doctor.lastName ? doctor.lastName[0] : ''}`;
            
            // Formatear la fecha de la próxima cita
            let nextAppointmentDisplay = 'No hay citas programadas';
            if (doctor.nextAppointment) {
                const appointmentDate = new Date(doctor.nextAppointment);
                nextAppointmentDisplay = appointmentDate.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
            
            doctorCard.innerHTML = `
                <div class="doctor-avatar">
                    <div class="avatar-circle">${initials}</div>
                </div>
                <h3 class="doctor-name">${doctor.name} ${doctor.lastName || ''}</h3>
                <p class="doctor-specialty">${doctor.specialty}</p>
                <div class="next-appointment">
                    <i class="fas fa-calendar-alt"></i>
                    <span>${nextAppointmentDisplay}</span>
                </div>`;
            
            doctorsListElement.appendChild(doctorCard);
        });
    }
    
    // Initialize attendance chart
    function initAttendanceChart() {
        const attendanceChartElement = document.getElementById('attendanceChart');
        if (!attendanceChartElement) return;
        
        // Sample data for attendance chart
        const attendanceData = {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May'],
            datasets: [{
                label: 'Citas asistidas',
                data: [2, 3, 2, 4, 3],
                backgroundColor: 'rgba(33, 36, 103, 0.7)',
                borderColor: 'rgba(33, 36, 103, 1)',
                borderWidth: 1
            }]
        };
        
        // Create chart
        
        // Sample data for treatment chart
        const treatmentData = {
            labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5'],
            datasets: [{
                label: 'Progreso',
                data: [20, 35, 45, 60, 75],
                backgroundColor: 'rgba(76, 175, 80, 0.2)',
                borderColor: 'rgba(76, 175, 80, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        };
        
        // Create chart
        new Chart(treatmentChartElement, {
            type: 'line',
            data: treatmentData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Load doctor notes
    function loadDoctorNotes() {
        const doctorNotesElement = document.getElementById('doctorNotes');
        if (!doctorNotesElement) return;
        
        // Sample data for doctor notes
        const notes = [
            {
                doctor: 'Dr. Rosa Elba Martínez',
                date: '2025-04-20',
                content: 'El paciente muestra una mejora significativa en su movilidad después de 3 sesiones de electroterapia. Se recomienda continuar con el tratamiento actual.'
            },
            {
                doctor: 'Dr. Isaac Solís Martínez',
                date: '2025-04-10',
                content: 'Se observa una reducción del dolor en la zona lumbar. El paciente reporta una mejoría del 60% respecto a la primera sesión. Se ajusta el tratamiento para enfocarse en ejercicios de fortalecimiento.'
            },
            {
                doctor: 'Dr. Karla Lorena Martínez Ávila',
                date: '2025-03-25',
                content: 'Primera evaluación completa. El paciente presenta tensión muscular en la zona cervical y dolor moderado al movimiento. Se inicia tratamiento con mecanoterapia y se programan 5 sesiones iniciales.'
            }
        ];
        
        renderDoctorNotes(notes);
    }
    
    // Render doctor notes
    function renderDoctorNotes(notes) {
        const doctorNotesElement = document.getElementById('doctorNotes');
        if (!doctorNotesElement) return;
        
        doctorNotesElement.innerHTML = '';
        
        if (notes.length === 0) {
            doctorNotesElement.innerHTML = '<div class="empty-message">No hay notas disponibles</div>';
            return;
        }
        
        notes.forEach(note => {
            // Format date
            const noteDate = new Date(note.date);
            const formattedDate = noteDate.toLocaleDateString('es-ES');
            
            const noteCard = document.createElement('div');
            noteCard.className = 'note-card';
            noteCard.innerHTML = `
                <div class="note-header">
                    <div class="note-doctor">${note.doctor}</div>
                    <div class="note-date">${formattedDate}</div>
                </div>
                <div class="note-content">${note.content}</div>
            `;
            
            doctorNotesElement.appendChild(noteCard);
        });
    }
    
    // Helper function to get status class
    function getStatusClass(status) {
        status = status.toLowerCase();
        if (status === 'solicitado') {
            return 'status-solicitado';
        } else if (status === 'agendado') {
            return 'status-agendado';
        } else if (status === 'completado') {
            return 'status-completado';
        } else if (status === 'cancelado') {
            return 'status-cancelado';
        }
        return '';
    }
});
