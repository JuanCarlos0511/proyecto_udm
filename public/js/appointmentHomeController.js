document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const backButton = document.getElementById('backToHome');
    const calendarGrid = document.getElementById('calendarGrid');
    const currentMonthElement = document.getElementById('currentMonth');
    const prevMonthButton = document.getElementById('prevMonth');
    const nextMonthButton = document.getElementById('nextMonth');
    const timeSelectionContainer = document.getElementById('timeSelectionContainer');
    const timeSlotsContainer = document.getElementById('timeSlots');
    const cancelButton = document.getElementById('cancelar');
    const submitButton = document.getElementById('solicitar');
    const confirmationModal = document.getElementById('confirmationModal');
    const goToHomeButton = document.getElementById('goToHome');
    const goToHistoryButton = document.getElementById('goToHistory');
    const padecimientoRadios = document.querySelectorAll('input[name="padecimiento"]');
    const padecimientoDetails = document.getElementById('padecimientoDetails');

    // Current date
    const currentDate = new Date();
    let currentYear = currentDate.getFullYear();
    let currentMonth = currentDate.getMonth();
    let selectedDate = null;
    let selectedTimeSlot = null;

    // Initialize calendar
    renderCalendar(currentYear, currentMonth);
    updateMonthDisplay();

    // Event listeners
    backButton.addEventListener('click', function() {
        window.location.href = '/';
    });

    prevMonthButton.addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentYear, currentMonth);
        updateMonthDisplay();
    });

    nextMonthButton.addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentYear, currentMonth);
        updateMonthDisplay();
    });

    cancelButton.addEventListener('click', function() {
        window.location.href = '/';
    });

    submitButton.addEventListener('click', function() {
        // Validate form
        const form = document.getElementById('appointmentHomeForm');
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

        if (isValid) {
            // Show confirmation modal
            confirmationModal.style.display = 'flex';
        }
    });

    goToHomeButton.addEventListener('click', function() {
        window.location.href = '/';
    });

    goToHistoryButton.addEventListener('click', function() {
        window.location.href = '/history';
    });

    // Handle padecimiento radio buttons
    padecimientoRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.value === 'si') {
                padecimientoDetails.style.display = 'block';
            } else {
                padecimientoDetails.style.display = 'none';
            }
        });
    });

    // Functions
    function renderCalendar(year, month) {
        calendarGrid.innerHTML = '';
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();
        
        // Add empty cells for days before the first day of the month
        for (let i = 0; i < startingDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.classList.add('calendar-day', 'empty');
            calendarGrid.appendChild(emptyCell);
        }
        
        // Add cells for each day of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement('div');
            dayCell.classList.add('calendar-day');
            
            // Check if this date is in the past
            const cellDate = new Date(year, month, day);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (cellDate < today) {
                dayCell.classList.add('past');
            } else {
                // For home visits, we only allow appointments 24 hours in advance
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                
                if (cellDate < tomorrow) {
                    dayCell.classList.add('unavailable');
                    dayCell.title = 'Las citas a domicilio requieren al menos 24 horas de anticipación';
                } else {
                    dayCell.classList.add('available');
                    dayCell.addEventListener('click', function() {
                        // Remove selected class from all days
                        document.querySelectorAll('.calendar-day').forEach(function(cell) {
                            cell.classList.remove('selected');
                        });
                        
                        // Add selected class to this day
                        this.classList.add('selected');
                        
                        // Store selected date
                        selectedDate = new Date(year, month, day);
                        
                        // Show time selection
                        timeSelectionContainer.style.display = 'block';
                        
                        // Generate time slots
                        generateTimeSlots();
                    });
                }
            }
            
            dayCell.textContent = day;
            calendarGrid.appendChild(dayCell);
        }
    }

    function updateMonthDisplay() {
        const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        currentMonthElement.textContent = `${months[currentMonth]} de ${currentYear}`;
    }

    function generateTimeSlots() {
        timeSlotsContainer.innerHTML = '';
        
        // Generate time slots from 9:00 to 17:00 with 1-hour intervals for home visits
        // Note: More limited hours for home visits compared to clinic visits
        const startHour = 9;
        const endHour = 17;
        
        // Get current date and time for validation
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinutes = now.getMinutes();
        
        // Calculate the minimum available hour (next hour + 1)
        // If we're at 10:13, the next available hour would be 12:00
        let minAvailableHour = currentHour + 2; // Start with current hour + 2
        
        // If we're already past the startHour and it's the same day, adjust the startHour
        const isToday = selectedDate && 
            selectedDate.getDate() === now.getDate() && 
            selectedDate.getMonth() === now.getMonth() && 
            selectedDate.getFullYear() === now.getFullYear();
        
        for (let hour = startHour; hour <= endHour; hour++) {
            const timeSlot = document.createElement('div');
            timeSlot.classList.add('time-slot');
            
            // Format time as 12-hour with AM/PM
            const hourFormatted = hour > 12 ? hour - 12 : hour;
            const amPm = hour >= 12 ? 'PM' : 'AM';
            timeSlot.textContent = `${hourFormatted}:00 ${amPm}`;
            
            // Disable hours before the minimum available hour on the current day
            if (isToday && hour < minAvailableHour) {
                timeSlot.classList.add('disabled');
                timeSlot.title = 'Esta hora no está disponible para agendar';
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
                });
            }
            
            timeSlotsContainer.appendChild(timeSlot);
        }
    }
});
