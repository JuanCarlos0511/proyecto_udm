document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const dateSelectBtn = document.getElementById('dateSelectBtn');
    const timeSelectBtn = document.getElementById('timeSelectBtn');
    const calendarPopup = document.getElementById('calendarPopup');
    const timePopup = document.getElementById('timePopup');
    const calendarGrid = document.getElementById('calendarGrid');
    const timeSlots = document.getElementById('timeSlots');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const currentMonthEl = document.getElementById('currentMonth');
    const selectedDateEl = document.getElementById('selectedDate');
    const selectedTimeEl = document.getElementById('selectedTime');
    const scheduleBtn = document.getElementById('scheduleBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmationModal = document.getElementById('confirmationModal');
    const goToHomeBtn = document.getElementById('goToHome');
    const goToHistoryBtn = document.getElementById('goToHistory');
    const padecimientoRadios = document.querySelectorAll('input[name="padecimiento"]');
    const padecimientoDetails = document.getElementById('padecimientoDetails');
    const appointmentForm = document.getElementById('modernAppointmentHomeForm');
    const backToHomeBtn = document.getElementById('backToHome');

    // State
    let currentDate = new Date();
    let selectedDate = null;
    let selectedTime = null;
    let currentView = {
        year: currentDate.getFullYear(),
        month: currentDate.getMonth()
    };

    // Initialize
    renderCalendar();
    setupEventListeners();

    // Functions
    function setupEventListeners() {
        // Back button
        if (backToHomeBtn) {
            backToHomeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = '/';
            });
        }
        
        // Date and time selection
        dateSelectBtn.addEventListener('click', toggleCalendarPopup);
        timeSelectBtn.addEventListener('click', toggleTimePopup);
        prevMonthBtn.addEventListener('click', showPreviousMonth);
        nextMonthBtn.addEventListener('click', showNextMonth);
        
        // Close popups when clicking outside
        document.addEventListener('click', function(event) {
            if (!calendarPopup.contains(event.target) && event.target !== dateSelectBtn) {
                calendarPopup.classList.remove('active');
            }
            if (!timePopup.contains(event.target) && event.target !== timeSelectBtn) {
                timePopup.classList.remove('active');
            }
        });

        // Form actions
        scheduleBtn.addEventListener('click', submitAppointment);
        cancelBtn.addEventListener('click', function() {
            window.location.href = '/';
        });

        // Modal actions
        goToHomeBtn.addEventListener('click', function() {
            window.location.href = '/';
        });
        goToHistoryBtn.addEventListener('click', function() {
            window.location.href = '/history';
        });

        // Padecimiento radio buttons
        padecimientoRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'si') {
                    padecimientoDetails.style.display = 'block';
                } else {
                    padecimientoDetails.style.display = 'none';
                }
            });
        });

        // Autofill form if user is authenticated
        const isAuthenticated = document.getElementById('isAuthenticated');
        if (isAuthenticated && isAuthenticated.value === '1') {
            const userData = JSON.parse(document.getElementById('userData').value);
            autofillForm(userData);
        }
    }

    function toggleCalendarPopup() {
        calendarPopup.classList.toggle('active');
        if (timePopup.classList.contains('active')) {
            timePopup.classList.remove('active');
        }
    }

    function toggleTimePopup() {
        if (!selectedDate) {
            alert('Por favor, seleccione una fecha primero');
            return;
        }
        
        timePopup.classList.toggle('active');
        if (calendarPopup.classList.contains('active')) {
            calendarPopup.classList.remove('active');
        }
        
        renderTimeSlots();
    }

    function showPreviousMonth() {
        currentView.month--;
        if (currentView.month < 0) {
            currentView.month = 11;
            currentView.year--;
        }
        renderCalendar();
    }

    function showNextMonth() {
        currentView.month++;
        if (currentView.month > 11) {
            currentView.month = 0;
            currentView.year++;
        }
        renderCalendar();
    }

    function renderCalendar() {
        const year = currentView.year;
        const month = currentView.month;
        
        // Update month display
        const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        currentMonthEl.textContent = `${monthNames[month]} de ${year}`;
        
        // Clear calendar grid
        calendarGrid.innerHTML = '';
        
        // Get first day of month and total days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        // Add empty cells for days before the first day of month
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('div');
            calendarGrid.appendChild(emptyCell);
        }
        
        // Add days of the month
        const today = new Date();
        
        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement('div');
            dayCell.classList.add('calendar-day');
            dayCell.textContent = day;
            
            const dateObj = new Date(year, month, day);
            
            // Disable past dates
            if (dateObj < today && !(dateObj.getDate() === today.getDate() && 
                                    dateObj.getMonth() === today.getMonth() && 
                                    dateObj.getFullYear() === today.getFullYear())) {
                dayCell.classList.add('disabled');
            } else {
                // Check if this is the selected date
                if (selectedDate && 
                    day === selectedDate.getDate() && 
                    month === selectedDate.getMonth() && 
                    year === selectedDate.getFullYear()) {
                    dayCell.classList.add('selected');
                }
                
                // Add click event to selectable dates
                dayCell.addEventListener('click', function() {
                    if (!dayCell.classList.contains('disabled')) {
                        // Remove selected class from all days
                        document.querySelectorAll('.calendar-day.selected').forEach(el => {
                            el.classList.remove('selected');
                        });
                        
                        // Add selected class to clicked day
                        dayCell.classList.add('selected');
                        
                        // Update selected date
                        selectedDate = new Date(year, month, day);
                        updateSelectedDateDisplay();
                        
                        // Close calendar popup
                        calendarPopup.classList.remove('active');
                    }
                });
            }
            
            calendarGrid.appendChild(dayCell);
        }
    }

    function renderTimeSlots() {
        // Clear time slots
        timeSlots.innerHTML = '';
        
        // Example time slots (9:00 AM to 5:00 PM)
        // Para citas a domicilio, ofrecemos horarios más limitados
        const availableTimeSlots = [
            '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'
        ];
        
        // Render time slots
        availableTimeSlots.forEach(time => {
            const timeSlot = document.createElement('div');
            timeSlot.classList.add('time-slot');
            timeSlot.textContent = time;
            
            // Check if this is the selected time
            if (selectedTime === time) {
                timeSlot.classList.add('selected');
            }
            
            // Add click event
            timeSlot.addEventListener('click', function() {
                // Remove selected class from all time slots
                document.querySelectorAll('.time-slot.selected').forEach(el => {
                    el.classList.remove('selected');
                });
                
                // Add selected class to clicked time slot
                timeSlot.classList.add('selected');
                
                // Update selected time
                selectedTime = time;
                updateSelectedTimeDisplay();
                
                // Close time popup
                timePopup.classList.remove('active');
            });
            
            timeSlots.appendChild(timeSlot);
        });
    }

    function updateSelectedDateDisplay() {
        if (selectedDate) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            selectedDateEl.textContent = selectedDate.toLocaleDateString('es-ES', options);
            selectedDateEl.parentElement.style.display = 'block';
            
            // Enable time selection button
            timeSelectBtn.disabled = false;
        }
    }

    function updateSelectedTimeDisplay() {
        if (selectedTime) {
            selectedTimeEl.textContent = selectedTime;
            selectedTimeEl.parentElement.style.display = 'block';
        }
    }

    function autofillForm(userData) {
        document.getElementById('nombre').value = userData.name;
        document.getElementById('edad').value = userData.age;
        document.getElementById('email').value = userData.email;
        document.getElementById('telefono').value = userData.phoneNumber;
    }

    function submitAppointment() {
        // Validate form
        if (!validateForm()) {
            return;
        }
        
        // Collect form data
        const formData = new FormData();
        
        // User data
        formData.append('user[name]', document.getElementById('nombre').value);
        formData.append('user[age]', document.getElementById('edad').value);
        formData.append('user[email]', document.getElementById('email').value);
        formData.append('user[phoneNumber]', document.getElementById('telefono').value);
        
        // Datos específicos de cita a domicilio
        const direccionCompleta = document.getElementById('direccion').value;
        const referencias = document.getElementById('referencias').value || '';
        const direccionConReferencias = direccionCompleta + (referencias ? ' (Referencias: ' + referencias + ')' : '');
        
        // User emergency contact (usando la dirección como contacto de emergencia para citas a domicilio)
        formData.append('user[emergency_contact_name]', 'Dirección de servicio');
        formData.append('user[emergency_contact_phone]', document.getElementById('telefono').value);
        formData.append('user[emergency_contact_relationship]', direccionConReferencias);
        
        // Appointment data
        formData.append('date', selectedDate.toISOString().split('T')[0] + ' ' + selectedTime + ':00');
        formData.append('subject', document.getElementById('especialidad').value);
        formData.append('status', 'Solicitado');
        formData.append('modality', 'Domicilio');
        formData.append('price', '450'); // Precio base para consulta a domicilio
        
        // Diagnóstico si existe
        const padecimientoSi = document.getElementById('si');
        if (padecimientoSi.checked) {
            formData.append('diagnosis', document.getElementById('detalles').value);
        }
        
        // Obtener el token CSRF
        const token = document.querySelector('input[name="_token"]').value;
        formData.append('_token', token);
        
        // Submit the form data using fetch API
        fetch('/appointments', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al agendar la cita');
            }
            return response.json();
        })
        .then(data => {
            console.log('Cita a domicilio agendada:', data);
            confirmationModal.classList.add('active');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al agendar la cita a domicilio. Por favor intente nuevamente.');
        });
    }

    function validateForm() {
        // Basic validation
        const requiredFields = [
            'nombre', 'edad', 'email', 'telefono', 'direccion', 'especialidad'
        ];
        
        let isValid = true;
        
        requiredFields.forEach(field => {
            const element = document.getElementById(field);
            if (!element.value.trim()) {
                element.classList.add('error');
                isValid = false;
            } else {
                element.classList.remove('error');
            }
        });
        
        // Check if date and time are selected
        if (!selectedDate) {
            alert('Por favor, seleccione una fecha para la cita');
            isValid = false;
        }
        
        if (!selectedTime) {
            alert('Por favor, seleccione una hora para la cita');
            isValid = false;
        }
        
        // Check if padecimiento details are provided when "Si" is selected
        const padecimientoSi = document.getElementById('si');
        const detalles = document.getElementById('detalles');
        
        if (padecimientoSi.checked && !detalles.value.trim()) {
            detalles.classList.add('error');
            isValid = false;
        } else {
            detalles.classList.remove('error');
        }
        
        return isValid;
    }
});
