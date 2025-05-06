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
        
        // Fetch appointments from API
        fetch('/api/appointments')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar las citas');
                }
                return response.json();
            })
            .then(data => {
                console.log('Citas cargadas:', data);
                renderAppointmentsHistory(data);
            })
            .catch(error => {
                console.error('Error:', error);
                // Show sample data if API fails
                const sampleAppointments = [
                    { id: 1, date: '2025-04-20', doctor: 'Dr. Rosa Elba Martínez', subject: 'Electroterapia', modality: 'Consultorio', status: 'Completado' },
                    { id: 2, date: '2025-04-10', doctor: 'Dr. Isaac Solís Martínez', subject: 'Hidroterapia', modality: 'Domicilio', status: 'Completado' },
                    { id: 3, date: '2025-03-25', doctor: 'Dr. Karla Lorena Martínez Ávila', subject: 'Mecanoterapia', modality: 'Consultorio', status: 'Cancelado' }
                ];
                renderAppointmentsHistory(sampleAppointments);
            });
    }
    
    // Render appointments history
    function renderAppointmentsHistory(appointments) {
        const appointmentsHistoryList = document.getElementById('appointmentsHistoryList');
        if (!appointmentsHistoryList) return;
        
        appointmentsHistoryList.innerHTML = '';
        
        if (appointments.length === 0) {
            appointmentsHistoryList.innerHTML = '<tr><td colspan="6" class="empty-message">No hay citas en el historial</td></tr>';
            return;
        }
        
        appointments.forEach(appointment => {
            // Format date
            const appointmentDate = new Date(appointment.date);
            const formattedDate = appointmentDate.toLocaleDateString('es-ES');
            
            // Get doctor name
            const doctorName = appointment.doctor || 'Dr. Asignado';
            
            // Get status class
            const statusClass = getStatusClass(appointment.status);
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${formattedDate}</td>
                <td>${doctorName}</td>
                <td>${appointment.subject}</td>
                <td>${appointment.modality}</td>
                <td><span class="${statusClass}">${appointment.status}</span></td>
                <td><button class="btn-view" data-id="${appointment.id}">Ver detalles</button></td>
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
            upcomingAppointmentsList.innerHTML = '<div class="empty-message">No hay citas próximas</div>';
            return;
        }
        
        appointments.forEach(appointment => {
            // Format date
            const appointmentDate = new Date(appointment.date);
            const formattedDate = appointmentDate.toLocaleDateString('es-ES');
            
            // Get status class
            const statusClass = getStatusClass(appointment.status);
            
            const appointmentCard = document.createElement('div');
            appointmentCard.className = 'appointment-card';
            appointmentCard.innerHTML = `
                <div class="card-header">
                    <div class="card-date">${formattedDate} - ${appointment.time}</div>
                    <div class="card-status ${statusClass}">${appointment.status}</div>
                </div>
                <div class="card-details">
                    <div class="detail-row">
                        <div class="detail-label">Doctor:</div>
                        <div class="detail-value">${appointment.doctor}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Especialidad:</div>
                        <div class="detail-value">${appointment.subject}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Modalidad:</div>
                        <div class="detail-value">${appointment.modality}</div>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-reschedule" data-id="${appointment.id}">Reprogramar</button>
                    <button class="btn-cancel" data-id="${appointment.id}">Cancelar</button>
                </div>
            `;
            
            upcomingAppointmentsList.appendChild(appointmentCard);
        });
        
        // Add event listeners to buttons
        const rescheduleButtons = upcomingAppointmentsList.querySelectorAll('.btn-reschedule');
        rescheduleButtons.forEach(button => {
            button.addEventListener('click', () => {
                const appointmentId = button.getAttribute('data-id');
                rescheduleAppointment(appointmentId);
            });
        });
        
        const cancelButtons = upcomingAppointmentsList.querySelectorAll('.btn-cancel');
        cancelButtons.forEach(button => {
            button.addEventListener('click', () => {
                const appointmentId = button.getAttribute('data-id');
                cancelAppointment(appointmentId);
            });
        });
    }
    
    // Reschedule appointment
    function rescheduleAppointment(appointmentId) {
        // This would typically open a modal for rescheduling
        console.log(`Rescheduling appointment ${appointmentId}`);
    }
    
    // Cancel appointment
    function cancelAppointment(appointmentId) {
        // This would typically show a confirmation dialog and then cancel the appointment
        console.log(`Canceling appointment ${appointmentId}`);
    }
    
    // Load doctors
    function loadDoctors() {
        const doctorsList = document.getElementById('doctorsList');
        if (!doctorsList) return;
        
        // Fetch doctors from API
        // For now, we'll use sample data
        const doctors = [
            { id: 1, name: 'Dr. Rosa Elba Martínez', specialty: 'Electroterapia', image: 'assets/doctor1.jpg', appointments: 12, rating: 4.8 },
            { id: 2, name: 'Dr. Isaac Solís Martínez', specialty: 'Hidroterapia', image: 'assets/doctor2.jpg', appointments: 8, rating: 4.5 },
            { id: 3, name: 'Dr. Karla Lorena Martínez Ávila', specialty: 'Mecanoterapia', image: 'assets/doctor3.jpg', appointments: 15, rating: 4.9 }
        ];
        
        renderDoctors(doctors);
    }
    
    // Render doctors
    function renderDoctors(doctors) {
        const doctorsList = document.getElementById('doctorsList');
        if (!doctorsList) return;
        
        doctorsList.innerHTML = '';
        
        if (doctors.length === 0) {
            doctorsList.innerHTML = '<div class="empty-message">No hay doctores disponibles</div>';
            return;
        }
        
        doctors.forEach(doctor => {
            const doctorCard = document.createElement('div');
            doctorCard.className = 'doctor-card';
            doctorCard.innerHTML = `
                <img src="${doctor.image || 'assets/doctor-placeholder.jpg'}" alt="${doctor.name}" class="doctor-image">
                <div class="doctor-info">
                    <div class="doctor-name">${doctor.name}</div>
                    <div class="doctor-specialty">${doctor.specialty}</div>
                    <div class="doctor-stats">
                        <div class="stat-item">
                            <div class="stat-value">${doctor.appointments}</div>
                            <div class="stat-label">Citas</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">${doctor.rating}</div>
                            <div class="stat-label">Calificación</div>
                        </div>
                    </div>
                    <div class="doctor-actions">
                        <a href="/appointment-clinic?doctor=${doctor.id}" class="btn-schedule">Agendar cita</a>
                    </div>
                </div>
            `;
            
            doctorsList.appendChild(doctorCard);
        });
    }
    
    // Initialize charts
    function initCharts() {
        initAttendanceChart();
        initTreatmentChart();
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
        new Chart(attendanceChartElement, {
            type: 'bar',
            data: attendanceData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
    
    // Initialize treatment chart
    function initTreatmentChart() {
        const treatmentChartElement = document.getElementById('treatmentChart');
        if (!treatmentChartElement) return;
        
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
