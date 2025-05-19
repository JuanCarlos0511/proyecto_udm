document.addEventListener('DOMContentLoaded', function() {
    console.log('Script de corrección iniciado para citas a domicilio');
    
    // Elementos principales
    const dateSelectBtn = document.getElementById('dateSelectBtn');
    const timeSelectBtn = document.getElementById('timeSelectBtn');
    const calendarPopup = document.getElementById('calendarPopup');
    const timePopup = document.getElementById('timePopup');
    const calendarGrid = document.getElementById('calendarGrid');
    const currentMonthElement = document.getElementById('currentMonth');
    const prevMonthButton = document.getElementById('prevMonth');
    const nextMonthButton = document.getElementById('nextMonth');
    
    // Comprobar que los elementos existen
    if (!dateSelectBtn) console.error('No se encontró el botón de fecha');
    if (!calendarPopup) console.error('No se encontró el popup de calendario');
    if (!calendarGrid) console.error('No se encontró el grid del calendario');
    
    // Habilitar botones
    if (dateSelectBtn) {
        dateSelectBtn.disabled = false;
        console.log('Botón de fecha habilitado');
        
        // Reimplementar el evento de click
        dateSelectBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Botón de fecha clickeado');
            toggleCalendar();
            renderCalendar(); // Asegurar que el calendario se renderice
        });
    }
    
    // Configurar el botón de hora
    if (timeSelectBtn) {
        // Forma más agresiva de remover el disabled - eliminar completamente el atributo
        timeSelectBtn.removeAttribute('disabled');
        console.log('Atributo disabled removido del botón de tiempo');
        
        // Forzar la actualización visual del botón
        timeSelectBtn.classList.remove('disabled');
        timeSelectBtn.style.opacity = '1';
        timeSelectBtn.style.cursor = 'pointer';
        
        console.log('Botón de tiempo habilitado por inicialización');
        
        timeSelectBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Botón de hora clickeado');
            toggleTimePopup();
            renderTimeSlots();
        });
    }
    
    // Función para mostrar/ocultar el calendario
    function toggleCalendar() {
        console.log('Alternando visibilidad del calendario');
        if (calendarPopup) {
            calendarPopup.classList.toggle('active');
            
            // Si el popup de tiempo está abierto, cerrarlo
            if (timePopup && timePopup.classList.contains('active')) {
                timePopup.classList.remove('active');
            }
        }
    }
    
    // Variables para el calendario
    let currentDate = new Date();
    let selectedDate = null;
    let currentView = {
        year: currentDate.getFullYear(),
        month: currentDate.getMonth()
    };
    
    // Renderizar calendario inicialmente
    renderCalendar();
    
    // Configurar eventos para navegación del calendario
    if (prevMonthButton) {
        prevMonthButton.addEventListener('click', function(e) {
            e.preventDefault();
            currentView.month--;
            if (currentView.month < 0) {
                currentView.month = 11;
                currentView.year--;
            }
            renderCalendar();
            updateMonthDisplay();
        });
    }
    
    if (nextMonthButton) {
        nextMonthButton.addEventListener('click', function(e) {
            e.preventDefault();
            currentView.month++;
            if (currentView.month > 11) {
                currentView.month = 0;
                currentView.year++;
            }
            renderCalendar();
            updateMonthDisplay();
        });
    }
    
    // Función para renderizar el calendario
    function renderCalendar() {
        if (!calendarGrid) return;
        
        // Limpiar el grid del calendario
        calendarGrid.innerHTML = '';
        
        // Obtener número de días en el mes actual
        const firstDay = new Date(currentView.year, currentView.month, 1);
        const lastDay = new Date(currentView.year, currentView.month + 1, 0);
        const totalDays = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();
        
        console.log('Renderizando calendario:', currentView.year, currentView.month + 1, 'Total días:', totalDays);
        
        // Actualizar el texto del mes
        updateMonthDisplay();
        
        // Crear celdas vacías para los días anteriores al primer día del mes
        for (let i = 0; i < startingDayOfWeek; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.classList.add('empty');
            calendarGrid.appendChild(emptyCell);
        }
        
        // Crear celdas para cada día del mes
        for (let day = 1; day <= totalDays; day++) {
            const dayCell = document.createElement('div');
            const currentDateObj = new Date(currentView.year, currentView.month, day);
            const isToday = currentDateObj.getDate() === currentDate.getDate() && 
                            currentDateObj.getMonth() === currentDate.getMonth() && 
                            currentDateObj.getFullYear() === currentDate.getFullYear();
            
            dayCell.textContent = day;
            dayCell.classList.add('calendar-day');
            
            if (isToday) {
                dayCell.classList.add('today');
            }
            
            // Deshabilitar días pasados
            if (currentDateObj < new Date().setHours(0,0,0,0)) {
                dayCell.classList.add('disabled');
            } else {
                dayCell.classList.add('selectable');
                dayCell.addEventListener('click', function() {
                    // Quitar clase seleccionada de todos los días
                    document.querySelectorAll('.calendar-day.selected').forEach(el => {
                        el.classList.remove('selected');
                    });
                    
                    // Agregar clase seleccionada a este día
                    dayCell.classList.add('selected');
                    
                    // Actualizar fecha seleccionada
                    selectedDate = new Date(currentView.year, currentView.month, day);
                    updateSelectedDateDisplay();
                    
                    // Cerrar popup de calendario
                    calendarPopup.classList.remove('active');
                    
                    // Habilitar el botón de selección de hora
                    if (timeSelectBtn) {
                        timeSelectBtn.disabled = false;
                        console.log('Botón de hora habilitado');
                    }
                });
            }
            
            calendarGrid.appendChild(dayCell);
        }
    }
    
    function updateMonthDisplay() {
        if (!currentMonthElement) return;
        
        const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        currentMonthElement.textContent = `${months[currentView.month]} de ${currentView.year}`;
    }
    
    function updateSelectedDateDisplay() {
        const selectedDateEl = document.getElementById('selectedDate');
        if (!selectedDate || !selectedDateEl) return;
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        selectedDateEl.textContent = selectedDate.toLocaleDateString('es-ES', options);
        selectedDateEl.parentElement.style.display = 'block';
    }
    
    // Cerrar popups cuando se hace clic fuera
    document.addEventListener('click', function(event) {
        if (calendarPopup && !calendarPopup.contains(event.target) && event.target !== dateSelectBtn) {
            calendarPopup.classList.remove('active');
        }
        if (timePopup && !timePopup.contains(event.target) && event.target !== timeSelectBtn) {
            timePopup.classList.remove('active');
        }
    });
    
    // Función para mostrar/ocultar el popup de tiempo
    function toggleTimePopup() {
        console.log('Alternando visibilidad del popup de tiempo');
        if (timePopup) {
            timePopup.classList.toggle('active');
            
            // Si el popup de calendario está abierto, cerrarlo
            if (calendarPopup && calendarPopup.classList.contains('active')) {
                calendarPopup.classList.remove('active');
            }
        }
    }
    
    // Función para renderizar los slots de tiempo disponibles
    function renderTimeSlots() {
        if (!timeSlots) {
            console.error('No se encontró el contenedor de slots de tiempo');
            return;
        }
        
        // Limpiar slots anteriores
        timeSlots.innerHTML = '';
        
        const currentHour = new Date().getHours();
        const currentMinutes = new Date().getMinutes();
        
        // Generar slots de tiempo de 9:00 a 17:00 en intervalos de 1 hora
        for (let hour = 9; hour <= 17; hour++) {
            const timeSlot = document.createElement('div');
            timeSlot.classList.add('time-slot');
            
            // Formato de 24 horas
            const timeText = `${hour}:00 hrs`;
            timeSlot.textContent = timeText;
            
            // Verificar si el slot de tiempo es válido (2 horas después de la hora actual)
            const isSelectedDateToday = selectedDate && 
                selectedDate.getDate() === currentDate.getDate() && 
                selectedDate.getMonth() === currentDate.getMonth() && 
                selectedDate.getFullYear() === currentDate.getFullYear();
                
            const isTooEarly = isSelectedDateToday && (hour <= currentHour + 1);
            
            if (isTooEarly) {
                timeSlot.classList.add('disabled');
            } else {
                timeSlot.classList.add('selectable');
                timeSlot.addEventListener('click', function() {
                    // Quitar clase seleccionada de todos los slots
                    document.querySelectorAll('.time-slot.selected').forEach(el => {
                        el.classList.remove('selected');
                    });
                    
                    // Agregar clase seleccionada a este slot
                    timeSlot.classList.add('selected');
                    
                    // Actualizar hora seleccionada
                    selectedTime = timeText;
                    updateSelectedTimeDisplay();
                    
                    // Cerrar popup de tiempo
                    timePopup.classList.remove('active');
                });
            }
            
            timeSlots.appendChild(timeSlot);
        }
    }
    
    // Función para actualizar la visualización de la hora seleccionada
    function updateSelectedTimeDisplay() {
        const selectedTimeEl = document.getElementById('selectedTime');
        if (!selectedTime || !selectedTimeEl) return;
        
        selectedTimeEl.textContent = selectedTime;
        selectedTimeEl.parentElement.style.display = 'block';
    }
    
    // Autocompletar formulario
    autofillUserData();
    
    function autofillUserData() {
        if (document.getElementById('isAuthenticated') && document.getElementById('isAuthenticated').value === '1') {
            try {
                const userDataElement = document.getElementById('userData');
                if (userDataElement) {
                    const userData = JSON.parse(userDataElement.value);
                    console.log('Datos de usuario para autocompletar:', userData);
                    
                    // Rellenar campos
                    fillField('nombre', userData.name);
                    fillField('edad', userData.age);
                    fillField('email', userData.email);
                    fillField('telefono', userData.phoneNumber);
                    fillField('direccion', userData.adress);
                }
            } catch (error) {
                console.error('Error al procesar datos de usuario:', error);
            }
        }
    }
    
    function fillField(fieldId, value) {
        const field = document.getElementById(fieldId);
        if (field && value) {
            field.value = value;
        }
    }
});
