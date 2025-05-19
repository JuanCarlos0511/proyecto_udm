document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const backButton = document.getElementById('backToHome');
    const calendarGrid = document.getElementById('calendarGrid');
    const currentMonthElement = document.getElementById('currentMonth');
    const prevMonthButton = document.getElementById('prevMonth');
    const nextMonthButton = document.getElementById('nextMonth');
    const timeSelectionContainer = document.getElementById('timeSelectionContainer');
    const timeSlotsContainer = document.getElementById('timeSlots');
    const cancelButton = document.getElementById('cancelBtn');
    const submitButton = document.getElementById('scheduleBtn');
    const confirmationModal = document.getElementById('confirmationModal');
    const goToHomeButton = document.getElementById('goToHome');
    const goToHistoryButton = document.getElementById('goToHistory');
    const padecimientoRadios = document.querySelectorAll('input[name="padecimiento"]');
    const padecimientoDetails = document.getElementById('padecimientoDetails');
    const dateSelectBtn = document.getElementById('dateSelectBtn');
    const timeSelectBtn = document.getElementById('timeSelectBtn');
    const calendarPopup = document.getElementById('calendarPopup');
    const timePopup = document.getElementById('timePopup');
    const selectedDateEl = document.getElementById('selectedDate');
    const selectedTimeEl = document.getElementById('selectedTime');

    console.log('Inicializando controlador de citas a domicilio');

    // Check if user is authenticated and populate form
    const isAuthenticated = document.getElementById('isAuthenticated')?.value === "1";
    if (isAuthenticated) {
        try {
            const userData = JSON.parse(document.getElementById('userData').value);
            // Populate form fields
            document.getElementById('nombre').value = userData.name || '';
            document.getElementById('edad').value = userData.age || '';
            document.getElementById('email').value = userData.email || '';
            document.getElementById('telefono').value = userData.phoneNumber || '';
            
            if (userData.adress) {
                document.getElementById('direccion').value = userData.adress;
            }
            
            console.log('Datos de usuario autorrellados correctamente');
        } catch (error) {
            console.error('Error al autocompletar datos de usuario:', error);
        }
    }

    // Current date
    const currentDate = new Date();
    let currentYear = currentDate.getFullYear();
    let currentMonth = currentDate.getMonth();
    let selectedDate = null;
    let selectedTimeSlot = null;

    // Initialize calendar
    renderCalendar(currentYear, currentMonth);
    updateMonthDisplay();

    // Remove disabled attribute from date button
    if (dateSelectBtn) {
        dateSelectBtn.removeAttribute('disabled');
        dateSelectBtn.style.opacity = '1';
        dateSelectBtn.style.cursor = 'pointer';
    }

    // Event listeners
    if (backButton) {
        backButton.addEventListener('click', function() {
            window.location.href = '/';
        });
    }

    if (prevMonthButton) {
        prevMonthButton.addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentYear, currentMonth);
            updateMonthDisplay();
        });
    }

    if (nextMonthButton) {
        nextMonthButton.addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar(currentYear, currentMonth);
            updateMonthDisplay();
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            window.location.href = '/';
        });
    }

    if (dateSelectBtn) {
        dateSelectBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Botón de fecha clickeado');
            toggleCalendarPopup();
        });
    }

    if (timeSelectBtn) {
        // Remove disabled attribute from time button
        timeSelectBtn.removeAttribute('disabled');
        timeSelectBtn.style.opacity = '1';
        timeSelectBtn.style.cursor = 'pointer';
        
        timeSelectBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Botón de hora clickeado');
            toggleTimePopup();
            generateTimeSlots();
        });
    }

    if (submitButton) {
        submitButton.addEventListener('click', async function() {
            // Validate form
            const form = document.getElementById('modernAppointmentHomeForm');
            if (!form) {
                console.error('No se encontró el formulario');
                return;
            }
            
            const formElements = form.elements;
            let isValid = true;

            for (let i = 0; i < formElements.length; i++) {
                if (formElements[i].hasAttribute('required') && !formElements[i].value) {
                    formElements[i].classList.add('invalid');
                    isValid = false;
                } else {
                    formElements[i].classList.remove('invalid');
                }
            }

            if (!selectedDate || !selectedTimeSlot) {
                alert('Por favor seleccione una fecha y hora para su cita.');
                isValid = false;
            }

            // Verificar si padecimiento está seleccionado y tiene detalles
            const padecimientoSi = document.querySelector('input[name="padecimiento"][value="si"]');
            if (padecimientoSi && padecimientoSi.checked) {
                const detalles = document.getElementById('detalles');
                if (!detalles.value.trim()) {
                    detalles.classList.add('invalid');
                    isValid = false;
                }
            }

            if (isValid) {
                try {
                    const appointmentDate = new Date(selectedDate);
                    appointmentDate.setHours(selectedTimeSlot, 0, 0);

                    // Prepare appointment data - siguiendo exactamente la estructura de appointmentClinicController
                    const appointmentData = {
                        date: appointmentDate.toISOString(),
                        subject: document.getElementById('especialidad').value,
                        modality: 'Domicilio',
                        status: 'Pendiente',
                        name: document.getElementById('nombre').value,
                        age: document.getElementById('edad').value,
                        email: document.getElementById('email').value,
                        phoneNumber: document.getElementById('telefono').value,
                        adress: document.getElementById('direccion').value,
                        emergency_contact_name: document.getElementById('contact_name').value,
                        emergency_contact_phone: document.getElementById('contact_phone').value,
                        emergency_contact_relationship: document.getElementById('contact_relationship').value
                    };

                    // Verificar y agregar detalles de padecimiento (condición médica)
                    const hasPadecimiento = document.querySelector('input[name="padecimiento"]:checked');
                    if (hasPadecimiento && hasPadecimiento.value === 'si') {
                        appointmentData.diagnosis = document.getElementById('detalles').value;
                    }

                    // Get CSRF token
                    const token = document.querySelector('input[name="_token"]').value;

                    // Send data to server
                    const response = await fetch('/api/appointments', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(appointmentData)
                    });

                    if (!response.ok) {
                        throw new Error(`Error: ${response.status}`);
                    }

                    const result = await response.json();
                    console.log('Cita a domicilio guardada:', result);

                    // Show confirmation modal
                    if (confirmationModal) {
                        confirmationModal.classList.add('active');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Hubo un error al agendar la cita. Por favor intente nuevamente.');
                }
            }
        });
    }

    if (goToHomeButton) {
        goToHomeButton.addEventListener('click', function() {
            window.location.href = '/';
        });
    }

    if (goToHistoryButton) {
        goToHistoryButton.addEventListener('click', function() {
            window.location.href = '/history';
        });
    }

    // Padecimiento radios
    padecimientoRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'si') {
                padecimientoDetails.style.display = 'block';
            } else {
                padecimientoDetails.style.display = 'none';
            }
        });
    });

    // Cerrar popups cuando se hace clic fuera
    document.addEventListener('click', function(event) {
        if (calendarPopup && !calendarPopup.contains(event.target) && event.target !== dateSelectBtn) {
            calendarPopup.classList.remove('active');
        }
        if (timePopup && !timePopup.contains(event.target) && event.target !== timeSelectBtn) {
            timePopup.classList.remove('active');
        }
    });

    // Functions
    function renderCalendar(year, month) {
        if (!calendarGrid) {
            console.error('No se encontró el grid del calendario');
            return;
        }
        
        // Clear calendar grid
        calendarGrid.innerHTML = '';
        
        // Get first day of month and last day of month
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        
        // Get day of week of first day (0-6, where 0 is Sunday)
        const firstDayOfWeek = firstDayOfMonth.getDay();
        
        // Get total days in month
        const daysInMonth = lastDayOfMonth.getDate();
        
        console.log(`Renderizando calendario: ${month + 1}/${year}, días: ${daysInMonth}, primer día: ${firstDayOfWeek}`);
        
        // Add empty cells for days before first day of month
        for (let i = 0; i < firstDayOfWeek; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.classList.add('empty');
            calendarGrid.appendChild(emptyCell);
        }
        
        // Add cells for days in month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement('div');
            dayCell.classList.add('calendar-day');
            
            const currentDateObj = new Date(year, month, day);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // Check if this day is today
            const isToday = currentDateObj.getTime() === today.getTime();
            if (isToday) {
                dayCell.classList.add('today');
            }
            
            // Disable past days
            if (currentDateObj < today) {
                dayCell.classList.add('disabled');
            } else {
                dayCell.addEventListener('click', function() {
                    // Remove selected class from all days
                    document.querySelectorAll('.calendar-day').forEach(function(cell) {
                        cell.classList.remove('selected');
                    });
                    
                    // Add selected class to this day
                    this.classList.add('selected');
                    
                    // Store selected date
                    selectedDate = new Date(year, month, day);
                    
                    // Update selected date display
                    updateSelectedDateDisplay();
                    
                    // Enable time button
                    if (timeSelectBtn) {
                        timeSelectBtn.disabled = false;
                        if (timeSelectBtn.parentElement) {
                            timeSelectBtn.parentElement.style.opacity = '1';
                        }
                    }
                    
                    // Close calendar popup
                    if (calendarPopup) {
                        calendarPopup.classList.remove('active');
                    }
                    
                    // Generate time slots
                    generateTimeSlots();
                });
            }
            
            dayCell.textContent = day;
            calendarGrid.appendChild(dayCell);
        }
    }

    function updateMonthDisplay() {
        if (!currentMonthElement) return;
        
        const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        currentMonthElement.textContent = `${months[currentMonth]} de ${currentYear}`;
    }

    function updateSelectedDateDisplay() {
        if (!selectedDateEl || !selectedDate) return;
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        selectedDateEl.textContent = selectedDate.toLocaleDateString('es-ES', options);
        
        // Show selected date container
        const container = selectedDateEl.parentElement;
        if (container) {
            container.style.display = 'block';
        }
    }

    function updateSelectedTimeDisplay() {
        if (!selectedTimeEl || !selectedTimeSlot) return;
        
        selectedTimeEl.textContent = `${selectedTimeSlot}:00 hrs`;
        
        // Show selected time container
        const container = selectedTimeEl.parentElement;
        if (container) {
            container.style.display = 'block';
        }
    }

    function generateTimeSlots() {
        if (!timeSlotsContainer) {
            console.error('No se encontró el contenedor de slots de tiempo');
            return;
        }
        
        // Clear time slots
        timeSlotsContainer.innerHTML = '';
        
        // Generate time slots from 8:00 to 19:00 with 1-hour intervals
        const startHour = 8;
        const endHour = 19;
        
        // Get current date and time for validation
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinutes = now.getMinutes();
        
        // Calculate the minimum available hour - Hora actual + 2 (según regla solicitada)
        // Si son las 4:05, la siguiente hora es 5:00 y la siguiente de esta es 6:00
        let minAvailableHour = currentHour + 2;
        
        console.log('Hora actual:', currentHour, 'Minutos:', currentMinutes);
        console.log('Hora mínima disponible:', minAvailableHour);
        
        // Verificar si la fecha seleccionada es hoy
        const isToday = selectedDate && 
            selectedDate.getDate() === now.getDate() && 
            selectedDate.getMonth() === now.getMonth() && 
            selectedDate.getFullYear() === now.getFullYear();
        
        for (let hour = startHour; hour <= endHour; hour++) {
            const timeSlot = document.createElement('div');
            timeSlot.classList.add('time-slot');
            
            // Format time as 24-hour format for mejor claridad
            timeSlot.textContent = `${hour}:00 hrs`;
            
            // Disable hours before the minimum available hour on the current day
            const hourTotalMinutes = hour * 60;
            const currentTotalMinutes = (currentHour * 60) + currentMinutes;
            const minAvailableTotalMinutes = ((currentHour + 2) * 60); // Hora actual + 2 horas en minutos
            
            // Deshabilitar todas las horas que estén a menos de 2 horas completas de la hora actual
            if (isToday && hourTotalMinutes < minAvailableTotalMinutes) {
                timeSlot.classList.add('disabled');
                timeSlot.title = 'Este horario no está disponible para agendar. Solo puede agendar a partir de las ' + 
                                 Math.ceil((currentHour + 2)) + ':00 hrs.';
                console.log('Slot NO disponible:', hour + ':00', 'Minutos totales:', hourTotalMinutes, 'Mínimo requerido:', minAvailableTotalMinutes);
            } else {
                timeSlot.addEventListener('click', function() {
                    // Remove selected class from all time slots
                    document.querySelectorAll('.time-slot').forEach(function(slot) {
                        slot.classList.remove('selected');
                    });
                    
                    // Add selected class to this time slot
                    this.classList.add('selected');
                    
                    // Store selected time slot
                    selectedTimeSlot = hour;
                    
                    // Update selected time display
                    updateSelectedTimeDisplay();
                    
                    // Close time popup
                    if (timePopup) {
                        timePopup.classList.remove('active');
                    }
                });
            }
            
            timeSlotsContainer.appendChild(timeSlot);
        }
    }

    function toggleCalendarPopup() {
        if (!calendarPopup) return;
        
        calendarPopup.classList.toggle('active');
        
        if (timePopup && timePopup.classList.contains('active')) {
            timePopup.classList.remove('active');
        }
    }

    function toggleTimePopup() {
        if (!timePopup) return;
        
        timePopup.classList.toggle('active');
        
        if (calendarPopup && calendarPopup.classList.contains('active')) {
            calendarPopup.classList.remove('active');
        }
    }
});
