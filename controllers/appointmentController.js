document.addEventListener('DOMContentLoaded', function() {
    // Elementos DOM
    const appointmentForm = document.getElementById('appointmentForm');
    const backToHome = document.getElementById('backToHome');
    const padecimientoRadios = document.getElementsByName('padecimiento');
    const padecimientoDetails = document.getElementById('padecimientoDetails');
    const confirmationModal = document.getElementById('confirmationModal');
    const goToHome = document.getElementById('goToHome');
    const goToHistory = document.getElementById('goToHistory');
    const calendar = document.getElementById('calendar');
    const timeSelectionContainer = document.getElementById('timeSelectionContainer');
    const timeSlots = document.getElementById('timeSlots');

    // Event Listeners
    backToHome.addEventListener('click', () => {
        window.location.href = '../index.html';
    });

    padecimientoRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (e.target.value === 'si') {
                padecimientoDetails.style.display = 'block';
            } else {
                padecimientoDetails.style.display = 'none';
            }
        });
    });

    // Calendario
    function generateCalendar() {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth();
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        
        const calendarGrid = calendar.querySelector('.calendar-grid');
        calendarGrid.innerHTML = '';

        // Agregar días vacíos hasta el primer día del mes
        for (let i = 0; i < firstDay.getDay(); i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day empty';
            calendarGrid.appendChild(emptyDay);
        }

        // Agregar los días del mes
        for (let day = 1; day <= lastDay.getDate(); day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;
            
            // No permitir seleccionar días pasados
            if (new Date(year, month, day) < new Date(today.getFullYear(), today.getMonth(), today.getDate())) {
                dayElement.classList.add('disabled');
            } else {
                dayElement.addEventListener('click', () => selectDate(year, month, day));
            }
            
            calendarGrid.appendChild(dayElement);
        }
    }

    function selectDate(year, month, day) {
        // Remover selección previa
        const previousSelected = calendar.querySelector('.selected');
        if (previousSelected) {
            previousSelected.classList.remove('selected');
        }

        // Seleccionar nuevo día
        const selectedDay = calendar.querySelector(`.calendar-day:not(.empty):nth-child(${day + new Date(year, month, 1).getDay()})`);
        selectedDay.classList.add('selected');

        // Mostrar horarios disponibles
        showTimeSlots();
    }

    function showTimeSlots() {
        timeSelectionContainer.style.display = 'block';
        timeSlots.innerHTML = '';

        // Horarios de ejemplo (esto debería venir del backend)
        const availableTimeSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00'
        ];

        availableTimeSlots.forEach(time => {
            const timeSlot = document.createElement('button');
            timeSlot.className = 'time-slot';
            timeSlot.textContent = time;
            timeSlot.addEventListener('click', () => selectTimeSlot(time));
            timeSlots.appendChild(timeSlot);
        });
    }

    function selectTimeSlot(time) {
        // Remover selección previa
        const previousSelected = timeSlots.querySelector('.selected');
        if (previousSelected) {
            previousSelected.classList.remove('selected');
        }

        // Seleccionar nuevo horario
        const selectedSlot = Array.from(timeSlots.children).find(slot => slot.textContent === time);
        selectedSlot.classList.add('selected');
    }

    // Manejo del formulario
    document.getElementById('solicitar').addEventListener('click', (e) => {
        e.preventDefault();
        // TODO: Validar formulario y enviar datos al backend
        confirmationModal.style.display = 'block';
    });

    document.getElementById('cancelar').addEventListener('click', () => {
        if (confirm('¿Está seguro que desea cancelar la cita?')) {
            window.location.href = '../index.html';
        }
    });

    goToHome.addEventListener('click', () => {
        window.location.href = '../index.html';
    });

    goToHistory.addEventListener('click', () => {
        window.location.href = 'history.html';
    });

    // Inicializar calendario
    generateCalendar();
});
