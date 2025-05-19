// Variables para los datos y paginación
let appointmentsData = [];
const itemsPerPage = 10;
let currentPage = 1;
let filteredData = [];

// Variables para estadísticas
let statsData = {
    total_month: 0,
    pending: 0
};

document.addEventListener('DOMContentLoaded', function() {
    // Variables para el rango de fechas en la sección superior
    const startDatePicker = document.getElementById('startDatePicker');
    const endDatePicker = document.getElementById('endDatePicker');
    const startDateValue = document.getElementById('startDateValue');
    const endDateValue = document.getElementById('endDateValue');
    
    // Variables para el modal de filtrado
    const openFilterModalBtn = document.getElementById('openFilterModal');
    const filterModal = document.getElementById('filterModal');
    const closeFilterModalBtn = document.getElementById('closeFilterModal');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const activeFilters = document.getElementById('activeFilters');
    
    // Variables para las etiquetas de filtros
    const statusFilterBadge = document.getElementById('statusFilterBadge');
    const doctorFilterBadge = document.getElementById('doctorFilterBadge');
    const dateFilterBadge = document.getElementById('dateFilterBadge');
    
    // Funcionalidad del modal de filtrado
    openFilterModalBtn.addEventListener('click', function() {
        filterModal.style.display = 'flex';
    });
    
    closeFilterModalBtn.addEventListener('click', function() {
        filterModal.style.display = 'none';
    });
    
    // Cerrar modal al hacer clic fuera del contenido
    filterModal.addEventListener('click', function(event) {
        if (event.target === filterModal) {
            filterModal.style.display = 'none';
        }
    });
    
    // Aplicar filtros
    applyFiltersBtn.addEventListener('click', function() {
        // Obtener los valores de los filtros
        const statusFilters = [];
        document.querySelectorAll('input[name="status"]:checked').forEach(checkbox => {
            statusFilters.push(checkbox.value);
        });
        
        const doctorFilter = document.getElementById('doctorFilter').value;
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        
        // Mostrar las etiquetas de filtros activos
        if (statusFilters.length > 0) {
            statusFilterBadge.querySelector('.filter-value').textContent = statusFilters.join(', ');
            statusFilterBadge.style.display = 'inline-flex';
            activeFilters.style.display = 'flex';
        } else {
            statusFilterBadge.style.display = 'none';
        }
        
        if (doctorFilter) {
            const doctorName = document.getElementById('doctorFilter').options[document.getElementById('doctorFilter').selectedIndex].text;
            doctorFilterBadge.querySelector('.filter-value').textContent = doctorName;
            doctorFilterBadge.style.display = 'inline-flex';
            activeFilters.style.display = 'flex';
        } else {
            doctorFilterBadge.style.display = 'none';
        }
        
        if (dateFrom || dateTo) {
            let dateText = '';
            if (dateFrom && dateTo) {
                dateText = `${dateFrom} - ${dateTo}`;
            } else if (dateFrom) {
                dateText = `Desde ${dateFrom}`;
            } else {
                dateText = `Hasta ${dateTo}`;
            }
            dateFilterBadge.querySelector('.filter-value').textContent = dateText;
            dateFilterBadge.style.display = 'inline-flex';
            activeFilters.style.display = 'flex';
        } else {
            dateFilterBadge.style.display = 'none';
        }
        
        // Si no hay filtros activos, ocultar la sección
        if (!statusFilters.length && !doctorFilter && !dateFrom && !dateTo) {
            activeFilters.style.display = 'none';
        }
        
        // Cerrar el modal
        filterModal.style.display = 'none';
        
        // Aquí se llamaría a la función para filtrar los datos
        filterAppointments(statusFilters, doctorFilter, dateFrom, dateTo);
    });
    
    // Restablecer filtros
    resetFiltersBtn.addEventListener('click', function() {
        // Limpiar los checkboxes de estado
        document.querySelectorAll('input[name="status"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Restablecer el selector de doctor
        document.getElementById('doctorFilter').value = '';
        
        // Limpiar los campos de fecha
        document.getElementById('dateFrom').value = '';
        document.getElementById('dateTo').value = '';
    });
    
    // Eliminar filtros individuales
    document.querySelectorAll('.remove-filter').forEach(removeBtn => {
        removeBtn.addEventListener('click', function() {
            const badge = this.parentElement;
            badge.style.display = 'none';
            
            // Identificar qué filtro se está eliminando
            if (badge === statusFilterBadge) {
                document.querySelectorAll('input[name="status"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            } else if (badge === doctorFilterBadge) {
                document.getElementById('doctorFilter').value = '';
            } else if (badge === dateFilterBadge) {
                document.getElementById('dateFrom').value = '';
                document.getElementById('dateTo').value = '';
            }
            
            // Verificar si hay algún filtro activo
            const visibleBadges = document.querySelectorAll('.filter-badge[style*="display: inline-flex"]');
            if (visibleBadges.length === 0) {
                activeFilters.style.display = 'none';
            }
            
            // Actualizar la tabla sin el filtro eliminado
            const statusFilters = [];
            document.querySelectorAll('input[name="status"]:checked').forEach(checkbox => {
                statusFilters.push(checkbox.value);
            });
            
            const doctorFilter = document.getElementById('doctorFilter').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            filterAppointments(statusFilters, doctorFilter, dateFrom, dateTo);
        });
    });
    
    // Current date values para el picker superior
    let currentStartDate = { month: 0, year: 2022 }; // January 2022
    let currentEndDate = { month: 8, year: 2022 }; // September 2022
    let activePickerType = null;
    
    // Array de nombres de meses
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    function createDatePickerModal(type) {
        activePickerType = type;
        const currentDate = type === 'start' ? currentStartDate : currentEndDate;
        
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Select ${type === 'start' ? 'Start' : 'End'} Date</div>
                    <div class="modal-close">&times;</div>
                </div>
                <div class="year-selector">
                    <div class="year-arrow prev-year">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="year-value">${currentDate.year}</div>
                    <div class="year-arrow next-year">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <div class="month-grid">
                    ${months.map((month, index) => `
                        <div class="month-item ${index === currentDate.month ? 'active' : ''}" data-month="${index}">
                            ${month}
                        </div>
                    `).join('')}
                </div>
                <div class="modal-actions">
                    <div class="modal-button cancel-button">Cancel</div>
                    <div class="modal-button apply-button">Apply</div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close button functionality
        modal.querySelector('.modal-close').addEventListener('click', function() {
            document.body.removeChild(modal);
        });
        
        // Cancel button functionality
        modal.querySelector('.cancel-button').addEventListener('click', function() {
            document.body.removeChild(modal);
        });
        
        // Month selection functionality
        const monthItems = modal.querySelectorAll('.month-item');
        monthItems.forEach(item => {
            item.addEventListener('click', function() {
                monthItems.forEach(m => m.classList.remove('active'));
                this.classList.add('active');
                const monthIndex = parseInt(this.getAttribute('data-month'));
                if (activePickerType === 'start') {
                    currentStartDate.month = monthIndex;
                } else {
                    currentEndDate.month = monthIndex;
                }
            });
        });
        
        // Year navigation functionality
        modal.querySelector('.prev-year').addEventListener('click', function() {
            const yearValue = modal.querySelector('.year-value');
            const currentYear = parseInt(yearValue.textContent);
            if (activePickerType === 'start') {
                currentStartDate.year = currentYear - 1;
            } else {
                currentEndDate.year = currentYear - 1;
            }
            yearValue.textContent = currentYear - 1;
        });
        
        modal.querySelector('.next-year').addEventListener('click', function() {
            const yearValue = modal.querySelector('.year-value');
            const currentYear = parseInt(yearValue.textContent);
            if (activePickerType === 'start') {
                currentStartDate.year = currentYear + 1;
            } else {
                currentEndDate.year = currentYear + 1;
            }
            yearValue.textContent = currentYear + 1;
        });
        
        // Apply button functionality
        modal.querySelector('.apply-button').addEventListener('click', function() {
            if (activePickerType === 'start') {
                startDateValue.textContent = `${months[currentStartDate.month]} ${currentStartDate.year}`;
                
                // Ensure end date is not before start date
                if (currentEndDate.year < currentStartDate.year || 
                    (currentEndDate.year === currentStartDate.year && currentEndDate.month < currentStartDate.month)) {
                    currentEndDate = { ...currentStartDate };
                    endDateValue.textContent = `${months[currentEndDate.month]} ${currentEndDate.year}`;
                }
            } else {
                endDateValue.textContent = `${months[currentEndDate.month]} ${currentEndDate.year}`;
                
                // Ensure start date is not after end date
                if (currentStartDate.year > currentEndDate.year || 
                    (currentStartDate.year === currentEndDate.year && currentStartDate.month > currentEndDate.month)) {
                    currentStartDate = { ...currentEndDate };
                    startDateValue.textContent = `${months[currentStartDate.month]} ${currentStartDate.year}`;
                }
            }
            
            document.body.removeChild(modal);
            updateData();
        });
    }
    
    startDatePicker.addEventListener('click', function() {
        createDatePickerModal('start');
    });
    
    endDatePicker.addEventListener('click', function() {
        createDatePickerModal('end');
    });
    
    // Checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.custom-checkbox:not(#selectAll)');
    
    selectAllCheckbox.addEventListener('click', function() {
        this.classList.toggle('checked');
        const isChecked = this.classList.contains('checked');
        
        checkboxes.forEach(checkbox => {
            if (isChecked) {
                checkbox.classList.add('checked');
            } else {
                checkbox.classList.remove('checked');
            }
        });
    });
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('click', function() {
            this.classList.toggle('checked');
            
            // Check if all checkboxes are checked
            const allChecked = Array.from(checkboxes).every(cb => cb.classList.contains('checked'));
            
            if (allChecked) {
                selectAllCheckbox.classList.add('checked');
            } else {
                selectAllCheckbox.classList.remove('checked');
            }
        });
    });
},function renderAppointments() {
    const tableBody = document.querySelector('#appointmentsTable tbody');
    if (!tableBody) return;

    tableBody.innerHTML = '';

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const visibleItems = filteredData.slice(start, end);

    if (visibleItems.length === 0) {
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = `
            <td colspan="8" class="empty-message">
                No hay citas que coincidan con los filtros seleccionados
            </td>
        `;
        tableBody.appendChild(emptyRow);
        return;
    }
    
    paginatedData.forEach(appointment => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="checkbox-cell">
                <div class="custom-checkbox"></div>
            </td>
            <td>#APT-${appointment.id}</td>
            <td>${appointment.patient}</td>
            <td>${appointment.timeToHuman}</td>
            <td>${appointment.time}</td>
            <td>${appointment.service}</td>
            <td><span class="appointment-status status-${appointment.status}">${appointment.status}</span></td>
            <td>
                <div class="appointment-actions">
                    <div class="action-button">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="action-button">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="action-button">
                        <i class="fas fa-trash"></i>
                    </div>
                </div>
            </td>
        `;
        
        tableBody.appendChild(row);
    });
}

,function updatePagination() {
    const pagination = document.getElementById('appointmentsPagination');
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    
    pagination.innerHTML = '';
    
    // Agregar botón anterior
    const prevButton = document.createElement('a');
    prevButton.href = '#';
    prevButton.className = 'pagination-item';
    prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
    prevButton.addEventListener('click', function(e) {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
            renderAppointments();
        }
    });
    pagination.appendChild(prevButton);
    // Función para filtrar las citas
    function filterAppointments(statusFilters, doctorFilter, dateFrom, dateTo) {
        filteredData = appointmentsData.filter(appointment => {
            // Filtrar por estado
            if (statusFilters.length > 0 && !statusFilters.includes(appointment.status)) {
                return false;
            }
            
            // Filtrar por doctor
            if (doctorFilter && appointment.doctor !== getDoctorNameById(doctorFilter)) {
                return false;
            }
            
            // Filtrar por fecha
            if (dateFrom && new Date(appointment.date) < new Date(dateFrom)) {
                return false;
            }
            
            if (dateTo && new Date(appointment.date) > new Date(dateTo)) {
                return false;
            }
            
            return true;
        });
        
        // Resetear a la primera página y actualizar la tabla
        currentPage = 1;
        updatePagination();
        renderAppointments();
    }
    
    // Función para obtener el nombre del doctor por ID
    function getDoctorNameById(id) {
        const doctors = {
            '1': 'Dr. Juan Pérez',
            '2': 'Dra. María López',
            '3': 'Dr. Carlos Rodríguez'
        };
        return doctors[id] || '';
    }
    
    // Función para renderizar las citas en la tabla
    function renderAppointments() {
        const tableBody = document.querySelector('.appointments-table tbody');
        tableBody.innerHTML = '';
        
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedData = filteredData.slice(startIndex, endIndex);
        
        if (paginatedData.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `<td colspan="7" style="text-align: center;">No se encontraron citas con los filtros seleccionados</td>`;
            tableBody.appendChild(row);
            return;
        }
        
        paginatedData.forEach(appointment => {
            const row = document.createElement('tr');
            
            // Obtener la clase de estado para el estilo
            let statusClass = '';
            let statusText = '';
            
            switch(appointment.status) {
                case 'completed':
                    statusClass = 'status-completed';
                    statusText = 'Completada';
                    break;
                case 'pending':
                    statusClass = 'status-pending';
                    statusText = 'Pendiente';
                    break;
                case 'cancelled':
                    statusClass = 'status-cancelled';
                    statusText = 'Cancelada';
                    break;
            }
            
            row.innerHTML = `
                <td class="checkbox-cell">
                    <div class="custom-checkbox"></div>
                </td>
                <td>#APT-${appointment.id}</td>
                <td>${appointment.patient}</td>
                <td>${appointment.timeToHuman}</td>
                <td>${appointment.time}</td>
                <td>${appointment.service}</td>
                <td><span class="appointment-status status-${appointment.status}">${appointment.status}</span></td>
                <td>
                    <div class="appointment-actions">
                        <div class="action-button">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="action-button">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="action-button">
                            <i class="fas fa-trash"></i>
                        </div>
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
            
            // Agregar funcionalidad al checkbox
            const checkbox = document.querySelector(`.custom-checkbox`);
            checkbox.addEventListener('click', function() {
                this.classList.toggle('checked');
                
                // Verificar si todos los checkboxes están marcados
                const allChecked = Array.from(document.querySelectorAll('.custom-checkbox:not(#selectAll)')).every(cb => cb.classList.contains('checked'));
                
                if (allChecked) {
                    selectAllCheckbox.classList.add('checked');
                } else {
                    selectAllCheckbox.classList.remove('checked');
                }
            });
        });
    }
    
    // Función para actualizar la paginación
    function updatePagination() {
        const pagination = document.getElementById('appointmentsPagination');
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        
        pagination.innerHTML = '';
        
        // Agregar botón anterior
        const prevButton = document.createElement('a');
        prevButton.href = '#';
        prevButton.className = 'pagination-item';
        prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
                renderAppointments();
            }
        });
        pagination.appendChild(prevButton);
        
        // Mostrar páginas
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage + 1 < maxVisiblePages && startPage > 1) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageLink = document.createElement('a');
            pageLink.href = '#';
            pageLink.className = 'pagination-item' + (i === currentPage ? ' active' : '');
            pageLink.textContent = i;
            pageLink.dataset.page = i;
            
            pageLink.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = parseInt(this.dataset.page);
                updatePagination();
                renderAppointments();
            });
            
            pagination.appendChild(pageLink);
        }
        
        // Mostrar puntos suspensivos si hay más páginas
        if (endPage < totalPages) {
            const separator = document.createElement('span');
            separator.className = 'pagination-separator';
            separator.textContent = '...';
            pagination.appendChild(separator);
            
            // Mostrar la última página
            const lastPage = document.createElement('a');
            lastPage.href = '#';
            lastPage.className = 'pagination-item';
            lastPage.textContent = totalPages;
            lastPage.dataset.page = totalPages;
            
            lastPage.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = parseInt(this.dataset.page);
                updatePagination();
                renderAppointments();
            });
            
            pagination.appendChild(lastPage);
        }
        
        // Agregar botón siguiente
        const nextButton = document.createElement('a');
        nextButton.href = '#';
        nextButton.className = 'pagination-item';
        nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
                renderAppointments();
            }
        });
        pagination.appendChild(nextButton);
    }
    
    // Inicializar la tabla y la paginación
    renderAppointments();
    updatePagination();
    
    // Función para cargar los datos de citas desde el servidor
    function loadAppointmentData() {
        // Mostrar indicador de carga
        document.querySelector('.loading').style.display = 'block';
        document.querySelector('.appointments-table').style.display = 'none';
        
        // Obtener las fechas de filtrado
        const dateFrom = document.querySelector('input[name="date_from"]').value;
        const dateTo = document.querySelector('input[name="date_to"]').value;
        
        console.log(`Cargando citas desde ${dateFrom} hasta ${dateTo}`);
        
        // Preparar el token CSRF para la petición
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Hacer la petición al servidor
        fetch('/api/appointment-history', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                date_from: dateFrom,
                date_to: dateTo
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data);
            
            if (data.success) {
                // Actualizar los datos de citas
                appointmentsData = data.data.appointments;
                filteredData = [...appointmentsData];
                
                // Actualizar estadísticas
                if (data.data.stats) {
                    updateStats(data.data.stats);
                }
                
                // Actualizar la tabla
                currentPage = 1;
                updatePagination();
                renderAppointments();
                
                // Ocultar indicador de carga y mostrar tabla
                document.querySelector('.loading').style.display = 'none';
                document.querySelector('.appointments-table').style.display = 'table';
            } else {
                console.error('Error al cargar los datos:', data.message);
                document.querySelector('.loading').style.display = 'none';
                // Mostrar mensaje de error
                showErrorMessage('Error al cargar los datos de citas');
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            document.querySelector('.loading').style.display = 'none';
            // Mostrar mensaje de error
            showErrorMessage('Error de conexión al cargar las citas');
        });
    }
    
    // Función para actualizar las estadísticas en la UI
    function updateStats(stats) {
        if (document.getElementById('total-month')) {
            document.getElementById('total-month').textContent = stats.total_month;
        }
        if (document.getElementById('pending-appointments')) {
            document.getElementById('pending-appointments').textContent = stats.pending;
        }
    }
    
    // Función para mostrar mensajes de error
    function showErrorMessage(message) {
        const tableBody = document.querySelector('#appointmentsTable tbody');
        if (tableBody) {
            tableBody.innerHTML = `<tr><td colspan="8" class="error-message">${message}</td></tr>`;
            document.querySelector('.appointments-table').style.display = 'table';
        }
    }
});
