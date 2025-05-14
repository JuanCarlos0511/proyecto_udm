// Appointment History Controller
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let appointments = [];
    let currentPage = 1;
    let itemsPerPage = 10;
    let currentFilter = 'all';
    let startDate = null;
    let endDate = null;
    
    const appointmentHistoryTable = document.getElementById('appointmentHistory');
    const searchInput = document.querySelector('.search-box input');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const prevButton = document.querySelector('.prev-btn');
    const nextButton = document.querySelector('.next-btn');
    const pageInfo = document.querySelector('.pagination span');
    const periodFilter = document.getElementById('periodFilter');
    const dateModal = document.getElementById('dateModal');
    const closeDateModal = document.getElementById('closeDateModal');
    const resetDatesBtn = document.getElementById('resetDates');
    const applyDatesBtn = document.getElementById('applyDates');
    const periodText = document.getElementById('periodText');
    
    // Initialize
    fetchAppointments();
    
    // Event listeners
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Update filter and render
            currentFilter = this.dataset.filter;
            renderAppointments(filterAppointments(appointments, currentFilter));
        });
    });
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            currentPage = 1;
            renderAppointments(filterAppointments(appointments, currentFilter));
            updatePagination();
        });
    }
    
    prevButton.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
            renderAppointments(filterAppointments(appointments, currentFilter));
        }
    });
    
    nextButton.addEventListener('click', function() {
        const filteredData = filterAppointments(appointments, currentFilter);
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
            renderAppointments(filteredData);
        }
    });
    
    // Inicializar Flatpickr para los selectores de fecha
    const startDatePicker = flatpickr("#startDate", {
        dateFormat: "Y-m-d",
        locale: "es",
        maxDate: "today",
        onChange: function(selectedDates) {
            // Actualizar la fecha mínima del selector de fecha final
            if (selectedDates.length > 0) {
                endDatePicker.set('minDate', selectedDates[0]);
            }
        }
    });
    
    const endDatePicker = flatpickr("#endDate", {
        dateFormat: "Y-m-d",
        locale: "es",
        maxDate: "today"
    });
    
    // Event listener para abrir el modal de fechas
    periodFilter.addEventListener('click', function() {
        dateModal.style.display = 'flex';
    });
    
    // Event listener para cerrar el modal de fechas
    closeDateModal.addEventListener('click', function() {
        dateModal.style.display = 'none';
    });
    
    // Event listener para cerrar el modal al hacer clic fuera de él
    window.addEventListener('click', function(event) {
        if (event.target === dateModal) {
            dateModal.style.display = 'none';
        }
    });
    
    // Event listener para restablecer las fechas
    resetDatesBtn.addEventListener('click', function() {
        startDatePicker.clear();
        endDatePicker.clear();
        startDate = null;
        endDate = null;
        periodText.textContent = 'Todos los periodos';
        dateModal.style.display = 'none';
        
        // Actualizar la tabla con todas las citas
        currentPage = 1;
        renderAppointments(filterAppointments(appointments, currentFilter));
        updatePagination();
    });
    
    // Event listener para aplicar el filtro de fechas
    applyDatesBtn.addEventListener('click', function() {
        startDate = document.getElementById('startDate').value || null;
        endDate = document.getElementById('endDate').value || null;
        
        if (startDate && endDate) {
            // Formatear las fechas para mostrar
            const formattedStartDate = new Date(startDate).toLocaleDateString('es-ES');
            const formattedEndDate = new Date(endDate).toLocaleDateString('es-ES');
            periodText.textContent = `${formattedStartDate} - ${formattedEndDate}`;
        } else if (startDate) {
            const formattedStartDate = new Date(startDate).toLocaleDateString('es-ES');
            periodText.textContent = `Desde ${formattedStartDate}`;
        } else if (endDate) {
            const formattedEndDate = new Date(endDate).toLocaleDateString('es-ES');
            periodText.textContent = `Hasta ${formattedEndDate}`;
        } else {
            periodText.textContent = 'Todos los periodos';
        }
        
        dateModal.style.display = 'none';
        
        // Actualizar la tabla con las citas filtradas
        currentPage = 1;
        renderAppointments(filterAppointments(appointments, currentFilter));
        updatePagination();
    });
    
    // Fetch appointments data
    function fetchAppointments() {
        // Show loading state
        appointmentHistoryTable.innerHTML = '<tr><td colspan="8" class="loading-message">Cargando citas...</td></tr>';
        
        // Get CSRF token for the request
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Mostrar información del token en consola para depuración
        console.log('CSRF Token en fetchAppointments:', csrfToken);
        console.log('Meta tag CSRF:', document.querySelector('meta[name="csrf-token"]'));
        
        // Verificar si el token existe
        if (!csrfToken) {
            console.error('No se encontró el token CSRF en el documento');
            appointmentHistoryTable.innerHTML = `
                <tr>
                    <td colspan="8" class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        Error: No se encontró el token CSRF. Por favor, recarga la página.
                    </td>
                </tr>
            `;
            return;
        }
        
        // Usar la variable global con el ID del usuario (definida en history.blade.php)
        const userId = typeof AUTHENTICATED_USER_ID !== 'undefined' ? AUTHENTICATED_USER_ID : null;
        
        console.log('ID de usuario autenticado:', userId);
        
        // URL de la API - usando la ruta específica de usuario si está disponible
        const apiUrl = userId ? `/api/appointments/user/${userId}` : '/api/appointments';
        
        // Fetch appointments from the API
        fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'include'
        })
        .then(response => {
            console.log('Estado de la respuesta:', response.status);
            
            // Si el usuario no está autenticado (401)
            if (response.status === 401) {
                throw new Error('Usuario no autenticado. Por favor inicia sesión para ver tus citas.');
            }
            
            // Otros errores
            if (!response.ok) {
                throw new Error('Error al cargar las citas');
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            
            // Normalizar el formato de los datos (manejar tanto array directo como objeto con propiedad data)
            let appointmentsData = Array.isArray(data) ? data : (data.data || []);
            
            console.log('Datos normalizados:', appointmentsData);
            
            // Verificar si hay citas
            if (appointmentsData.length === 0) {
                appointmentHistoryTable.innerHTML = `
                    <tr>
                        <td colspan="8" class="empty-message">
                            <i class="fas fa-calendar-times"></i>
                            No tienes citas registradas
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Procesar los datos de las citas para asegurar que tengan el formato correcto
            appointments = appointmentsData.map(appointment => {
                return {
                    id: appointment.id,
                    date: appointment.date,
                    patient: appointment.user ? appointment.user.name : 'Usuario',
                    subject: appointment.subject,
                    modality: appointment.modality,
                    status: appointment.status,
                    price: parseFloat(appointment.price || 0)
                };
            });
            
            renderAppointments(filterAppointments(appointments, currentFilter));
            updatePagination();
        })
        .catch(error => {
            console.error('Error al cargar citas:', error);
            
            // Determinar el mensaje de error
            let errorMessage = 'Error al cargar las citas. Por favor, intenta de nuevo más tarde.';
            
            if (error.message.includes('no autenticado')) {
                errorMessage = 'Debes iniciar sesión para ver tu historial de citas personal.';
                
                // Mostrar notificación si está disponible
                if (typeof showNotification === 'function') {
                    showNotification('error', 'Acceso denegado', 'Debes iniciar sesión para ver tu historial de citas.', 5000);
                }
            }
            
            // Show error message
            appointmentHistoryTable.innerHTML = `
                <tr>
                    <td colspan="8" class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        ${errorMessage}
                    </td>
                </tr>
            `;
        });
    }
    
    // Filter appointments by status, search term and date range
    function filterAppointments(data, filter) {
        // Obtener el término de búsqueda
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        
        return data.filter(item => {
            // Filtrar por estado
            const statusMatch = filter === 'all' || item.status.toLowerCase() === filter.toLowerCase();
            
            // Filtrar por término de búsqueda
            const searchMatch = searchTerm === '' || 
                item.date.toLowerCase().includes(searchTerm) ||
                item.subject.toLowerCase().includes(searchTerm) ||
                item.modality.toLowerCase().includes(searchTerm) ||
                item.status.toLowerCase().includes(searchTerm) ||
                String(item.price).includes(searchTerm);
            
            // Filtrar por rango de fechas
            let dateMatch = true;
            if (startDate || endDate) {
                const appointmentDate = new Date(item.date);
                
                if (startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);
                    end.setHours(23, 59, 59); // Incluir todo el día final
                    dateMatch = appointmentDate >= start && appointmentDate <= end;
                } else if (startDate) {
                    const start = new Date(startDate);
                    dateMatch = appointmentDate >= start;
                } else if (endDate) {
                    const end = new Date(endDate);
                    end.setHours(23, 59, 59); // Incluir todo el día final
                    dateMatch = appointmentDate <= end;
                }
            }
            
            return statusMatch && searchMatch && dateMatch;
        });
    }
    
    // Render appointments to table
    function renderAppointments(data) {
        appointmentHistoryTable.innerHTML = '';
        
        if (data.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `<td colspan="8" class="empty-message">
                <i class="far fa-calendar-times"></i>
                No hay citas en el periodo seleccionado
            </td>`;
            appointmentHistoryTable.appendChild(emptyRow);
            return;
        }
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedData = data.slice(startIndex, endIndex);
        
        paginatedData.forEach(appointment => {
            const row = document.createElement('tr');
            
            // Format date
            const appointmentDate = new Date(appointment.date);
            const formattedDate = appointmentDate.toLocaleDateString('es-ES');
            
            // Get status class
            const statusClass = getStatusClass(appointment.status);
            
            // Format price
            const formattedPrice = new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN'
            }).format(appointment.price);
            
            row.innerHTML = `
                <td>${formattedDate}</td>
                <td>${appointment.subject}</td>
                <td>${appointment.modality}</td>
                <td><span class="${statusClass}">${appointment.status}</span></td>
                <td>${formattedPrice}</td>
            `;
            
            appointmentHistoryTable.appendChild(row);
        });
    }
    
    // Update pagination display
    function updatePagination() {
        const filteredData = filterAppointments(appointments, currentFilter);
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        
        document.querySelector('.pagination span').textContent = `Página ${currentPage} de ${totalPages}`;
        
        // Update button states
        prevButton.disabled = currentPage === 1;
        nextButton.disabled = currentPage === totalPages;
    }
    
    // Get status class based on status text
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
        return ''; // Default
    }
});
