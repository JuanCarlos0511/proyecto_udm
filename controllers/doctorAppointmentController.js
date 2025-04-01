document.addEventListener('DOMContentLoaded', function() {
    // Elementos DOM
    const doctorAppointmentForm = document.getElementById('doctorAppointmentForm');
    const doctorCalendar = document.getElementById('doctorCalendar');
    const timeSlotsPicker = document.getElementById('timeSlotsPicker');
    const cancelAppointment = document.getElementById('cancelAppointment');
    const submitAppointment = document.getElementById('submitAppointment');

    // Event Listeners
    cancelAppointment.addEventListener('click', () => {
        if (confirm('¿Está seguro que desea cancelar la cita?')) {
            window.location.href = 'admin.html';
        }
    });

    // Calendario
    function generateCalendar() {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth();
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        
        const calendarHTML = `
            <div class="calendar-header">
                <h3>${new Intl.DateTimeFormat('es-ES', { month: 'long', year: 'numeric' }).format(today)}</h3>
            </div>
            <div class="calendar-days">
                <div>Dom</div><div>Lun</div><div>Mar</div><div>Mié</div>
                <div>Jue</div><div>Vie</div><div>Sáb</div>
            </div>
            <div class="calendar-grid">
                ${generateCalendarDays(firstDay, lastDay)}
            </div>
        `;
        
        doctorCalendar.innerHTML = calendarHTML;
        addCalendarListeners();
    }

    function generateCalendarDays(firstDay, lastDay) {
        let calendarDays = '';
        
        // Agregar días vacíos hasta el primer día del mes
        for (let i = 0; i < firstDay.getDay(); i++) {
            calendarDays += '<div class="calendar-day empty"></div>';
        }

        // Agregar los días del mes
        for (let day = 1; day <= lastDay.getDate(); day++) {
            calendarDays += `<div class="calendar-day" data-day="${day}">${day}</div>`;
        }

        return calendarDays;
    }

    function addCalendarListeners() {
        const days = doctorCalendar.querySelectorAll('.calendar-day:not(.empty)');
        days.forEach(day => {
            day.addEventListener('click', () => selectDate(day));
        });
    }

    function selectDate(dayElement) {
        // Remover selección previa
        const previousSelected = doctorCalendar.querySelector('.selected');
        if (previousSelected) {
            previousSelected.classList.remove('selected');
        }

        // Seleccionar nuevo día
        dayElement.classList.add('selected');

        // Mostrar horarios disponibles
        showTimeSlots();
    }

    function showTimeSlots() {
        timeSlotsPicker.innerHTML = '';

        // Horarios de ejemplo (esto debería venir del backend)
        const availableTimeSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00'
        ];

        const timeSlotsHTML = availableTimeSlots.map(time => `
            <button class="time-slot" data-time="${time}">${time}</button>
        `).join('');

        timeSlotsPicker.innerHTML = `
            <h4>Horarios disponibles:</h4>
            <div class="time-slots-grid">
                ${timeSlotsHTML}
            </div>
        `;

        addTimeSlotListeners();
    }

    function addTimeSlotListeners() {
        const timeSlots = timeSlotsPicker.querySelectorAll('.time-slot');
        timeSlots.forEach(slot => {
            slot.addEventListener('click', () => selectTimeSlot(slot));
        });
    }

    function selectTimeSlot(slotElement) {
        // Remover selección previa
        const previousSelected = timeSlotsPicker.querySelector('.selected');
        if (previousSelected) {
            previousSelected.classList.remove('selected');
        }

        // Seleccionar nuevo horario
        slotElement.classList.add('selected');
    }

    // Manejo del formulario
    doctorAppointmentForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Validar que se haya seleccionado fecha y hora
        const selectedDate = doctorCalendar.querySelector('.calendar-day.selected');
        const selectedTime = timeSlotsPicker.querySelector('.time-slot.selected');
        
        if (!selectedDate || !selectedTime) {
            alert('Por favor, seleccione una fecha y hora para la cita');
            return;
        }

        // TODO: Enviar datos al backend
        alert('Cita agendada correctamente');
        window.location.href = 'admin.html';
    });

    // Inicializar calendario
    generateCalendar();
});
