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
    const appointmentForm = document.getElementById('modernAppointmentForm');
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
        const availableTimeSlots = [
            '9:00', '9:30', '10:00', '10:30', '11:00', '11:30', 
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', 
            '15:00', '15:30', '16:00', '16:30', '17:00'
        ];
        
        // Get current date and time
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();
        
        // Check if selected date is today
        const isToday = selectedDate && 
                        selectedDate.getDate() === now.getDate() && 
                        selectedDate.getMonth() === now.getMonth() && 
                        selectedDate.getFullYear() === now.getFullYear();
        
        // Render time slots
        availableTimeSlots.forEach(time => {
            const timeSlot = document.createElement('div');
            timeSlot.classList.add('time-slot');
            timeSlot.textContent = time;
            
            // Check if this is the selected time
            if (selectedTime === time) {
                timeSlot.classList.add('selected');
            }
            
            // Disable past time slots if selected date is today
            if (isToday) {
                const [hourStr, minuteStr] = time.split(':');
                const slotHour = parseInt(hourStr);
                const slotMinute = parseInt(minuteStr);
                
                // Disable if time has already passed
                if (slotHour < currentHour || (slotHour === currentHour && slotMinute <= currentMinute)) {
                    timeSlot.classList.add('disabled');
                    timeSlot.title = 'Esta hora ya ha pasado';
                }
            }
            
            // Add click event only if not disabled
            timeSlot.addEventListener('click', function() {
                // Skip if disabled
                if (timeSlot.classList.contains('disabled')) {
                    return;
                }
                
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
        
        if (userData.emergency_contact_name) {
            document.getElementById('contact_name').value = userData.emergency_contact_name;
        }
        
        if (userData.emergency_contact_phone) {
            document.getElementById('contact_phone').value = userData.emergency_contact_phone;
        }
        
        if (userData.emergency_contact_relationship) {
            document.getElementById('contact_relationship').value = userData.emergency_contact_relationship;
        }
    }

    function submitAppointment() {
        console.log('Iniciando proceso de envío de cita...');
        
        // Validate form
        if (!validateForm()) {
            console.log('Validación del formulario falló');
            return;
        }
        
        console.log('Validación del formulario exitosa');
        
        // Verificar que la fecha y hora estén seleccionadas
        if (!selectedDate) {
            console.error('Error: No hay fecha seleccionada');
            alert('Por favor seleccione una fecha para la cita');
            return;
        }
        
        if (!selectedTime) {
            console.error('Error: No hay hora seleccionada');
            alert('Por favor seleccione una hora para la cita');
            return;
        }
        
        // Prepare data in JSON format
        const userData = {
            user: {
                name: document.getElementById('nombre').value,
                age: document.getElementById('edad').value,
                email: document.getElementById('email').value,
                phoneNumber: document.getElementById('telefono').value,
                emergency_contact_name: document.getElementById('contact_name').value,
                emergency_contact_phone: document.getElementById('contact_phone').value,
                emergency_contact_relationship: document.getElementById('contact_relationship').value
            },
            date: selectedDate.toISOString().split('T')[0] + ' ' + selectedTime + ':00',
            subject: document.getElementById('especialidad').value,
            status: 'Solicitado',
            modality: 'Consultorio',
            price: 350, // Precio base para consulta en clínica
            doctor_id: document.getElementById('doctor').value
        };
        
        // Diagnóstico si existe
        const padecimientoSi = document.getElementById('si');
        if (padecimientoSi && padecimientoSi.checked) {
            userData.diagnosis = document.getElementById('detalles').value;
        }
        
        // Referido por (si existe)
        const referidoPor = document.getElementById('referred_by');
        if (referidoPor && referidoPor.value) {
            userData.referred_by = referidoPor.value;
        }
        
        console.log('Datos de cita a enviar:', userData);
        
        // Obtener el token CSRF
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!token) {
            console.error('Error: No se encontró el token CSRF');
            alert('Error de seguridad: No se encontró el token CSRF. Por favor, recargue la página.');
            return;
        }
        
        console.log('Token CSRF encontrado:', token);
        
        // Submit the form data using fetch API
        fetch('/api/appointments', {
            method: 'POST',
            body: JSON.stringify(userData),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta del servidor:', response);
            
            // Mostrar todos los detalles de la respuesta
            return response.text().then(text => {
                try {
                    const data = text ? JSON.parse(text) : {};
                    console.log('Respuesta completa:', data);
                    
                    if (!response.ok) {
                        throw new Error(JSON.stringify(data));
                    }
                    
                    return data;
                } catch (e) {
                    console.error('Error al parsear la respuesta:', e);
                    console.log('Texto de respuesta crudo:', text);
                    throw new Error('Error al procesar la respuesta del servidor');
                }
            });
        })
        .then(data => {
            console.log('Cita en clínica agendada exitosamente:', data);
            confirmationModal.classList.add('active');
        })
        .catch(error => {
            console.error('Error completo:', error);
            alert('Hubo un error al agendar la cita en clínica. Por favor intente nuevamente. Revise la consola para más detalles.');
        });
    }

    function validateForm() {
        // Basic validation
        const requiredFields = [
            'nombre', 'edad', 'email', 'telefono', 'contact_name', 
            'contact_relationship', 'contact_phone', 'especialidad'
        ];
        
        let isValid = true;
        let firstErrorField = null;
        
        // Clear all previous errors first
        document.querySelectorAll('input, select, textarea').forEach(el => {
            el.classList.remove('error');
        });
        
        // Validate each required field
        requiredFields.forEach(field => {
            const element = document.getElementById(field);
            if (!element.value.trim()) {
                element.classList.add('error');
                if (!firstErrorField) {
                    firstErrorField = element;
                }
                isValid = false;
            } else if (field === 'email') {
                // Email validation regex
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(element.value.trim())) {
                    element.classList.add('error');
                    if (!firstErrorField) {
                        firstErrorField = element;
                    }
                    isValid = false;
                }
            } else if (field === 'edad') {
                // Age validation
                const age = parseInt(element.value);
                if (isNaN(age) || age <= 0 || age > 120) {
                    element.classList.add('error');
                    if (!firstErrorField) {
                        firstErrorField = element;
                    }
                    isValid = false;
                }
            } else if (field === 'telefono' || field === 'contact_phone') {
                // Phone validation - only numbers and at least 10 digits
                const phoneRegex = /^\d{10,}$/;
                if (!phoneRegex.test(element.value.replace(/\D/g, ''))) {
                    element.classList.add('error');
                    if (!firstErrorField) {
                        firstErrorField = element;
                    }
                    isValid = false;
                }
            }
        });
        
        // Check if date and time are selected
        if (!selectedDate) {
            dateSelectBtn.classList.add('error');
            if (!firstErrorField) {
                firstErrorField = dateSelectBtn;
            }
            isValid = false;
        }
        
        if (!selectedTime) {
            timeSelectBtn.classList.add('error');
            if (!firstErrorField) {
                firstErrorField = timeSelectBtn;
            }
            isValid = false;
        }
        
        // Check if padecimiento details are provided when "Si" is selected
        const padecimientoSi = document.getElementById('si');
        const detalles = document.getElementById('detalles');
        
        if (padecimientoSi.checked && !detalles.value.trim()) {
            detalles.classList.add('error');
            if (!firstErrorField) {
                firstErrorField = detalles;
            }
            isValid = false;
        }
        
        // Focus and scroll to the first error field
        if (firstErrorField) {
            firstErrorField.focus();
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        return isValid;
    }
});
