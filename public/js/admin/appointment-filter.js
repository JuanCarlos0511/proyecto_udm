document.addEventListener('DOMContentLoaded', function() {
    // Variables de paginación
    const itemsPerPage = 10;
    let currentPage = 1;
    

    
    // Inicializar la paginación al cargar la página
    initializePagination();
    
    // Actualizar la información de paginación
    function updatePaginationInfo() {
        const totalItems = document.getElementById('total-items');
        const showingFrom = document.getElementById('showing-from');
        const showingTo = document.getElementById('showing-to');
        
        if (totalItems && showingFrom && showingTo) {
            const visibleRows = document.querySelectorAll('.appointment-row:not([style*="display: none"])').length;
            const totalRows = document.querySelectorAll('.appointment-row').length;
            
            totalItems.textContent = totalRows;
            
            if (visibleRows > 0) {
                const start = (currentPage - 1) * itemsPerPage + 1;
                const end = Math.min(start + itemsPerPage - 1, visibleRows);
                showingFrom.textContent = start;
                showingTo.textContent = end;
            } else {
                showingFrom.textContent = '0';
                showingTo.textContent = '0';
            }
        }
        
        // Actualizar la paginación después de filtrar
        updatePaginationControls();
    }
    
    // Inicializar la paginación
    function initializePagination() {
        applyPagination(currentPage);
        updatePaginationControls();
    }
    
    // Aplicar la paginación a la tabla
    function applyPagination(page) {
        currentPage = page;
        const rows = document.querySelectorAll('.appointment-row:not([style*="display: none"])');
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        
        rows.forEach((row, index) => {
            if (index >= startIndex && index < endIndex) {
                row.classList.add('page-visible');
                row.style.display = '';
            } else {
                row.classList.remove('page-visible');
                row.style.display = 'none';
            }
        });
        
        updatePaginationInfo();
    }
    
    // Actualizar los controles de paginación
    function updatePaginationControls() {
        const paginationContainer = document.getElementById('pagination-container');
        if (!paginationContainer) return;
        
        const visibleRows = document.querySelectorAll('.appointment-row:not([style*="display: none"])');
        const totalPages = Math.ceil(visibleRows.length / itemsPerPage);
        
        paginationContainer.innerHTML = '';
        
        // Botón de página anterior
        const prevButton = document.createElement('a');
        prevButton.href = '#';
        prevButton.className = 'page-link' + (currentPage === 1 ? ' disabled' : '');
        prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                applyPagination(currentPage - 1);
            }
        });
        const prevItem = document.createElement('li');
        prevItem.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
        prevItem.appendChild(prevButton);
        paginationContainer.appendChild(prevItem);
        
        // Generar páginas
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage + 1 < maxVisiblePages && startPage > 1) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageLink = document.createElement('a');
            pageLink.href = '#';
            pageLink.className = 'page-link';
            pageLink.textContent = i;
            pageLink.addEventListener('click', function(e) {
                e.preventDefault();
                applyPagination(i);
            });
            
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === currentPage ? ' active' : '');
            pageItem.appendChild(pageLink);
            paginationContainer.appendChild(pageItem);
        }
        
        // Separador de puntos suspensivos
        if (endPage < totalPages) {
            const separator = document.createElement('li');
            separator.className = 'page-item disabled';
            const separatorSpan = document.createElement('span');
            separatorSpan.className = 'pagination-separator';
            separatorSpan.textContent = '...';
            separator.appendChild(separatorSpan);
            paginationContainer.appendChild(separator);
            
            // Última página
            const lastPageLink = document.createElement('a');
            lastPageLink.href = '#';
            lastPageLink.className = 'page-link';
            lastPageLink.textContent = totalPages;
            lastPageLink.addEventListener('click', function(e) {
                e.preventDefault();
                applyPagination(totalPages);
            });
            
            const lastPageItem = document.createElement('li');
            lastPageItem.className = 'page-item';
            lastPageItem.appendChild(lastPageLink);
            paginationContainer.appendChild(lastPageItem);
        }
        
        // Botón de página siguiente
        const nextButton = document.createElement('a');
        nextButton.href = '#';
        nextButton.className = 'page-link' + (currentPage === totalPages || totalPages === 0 ? ' disabled' : '');
        nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage < totalPages) {
                applyPagination(currentPage + 1);
            }
        });
        const nextItem = document.createElement('li');
        nextItem.className = 'page-item' + (currentPage === totalPages || totalPages === 0 ? ' disabled' : '');
        nextItem.appendChild(nextButton);
        paginationContainer.appendChild(nextItem);
    }
    
    // Función para ver detalles de la cita
    window.viewAppointmentDetails = function(appointmentId) {
        // Implementar lógica para ver detalles
        alert(`Ver detalles de la cita ${appointmentId}`);
    };
    
    // Función para alternar la visibilidad del formulario de edición
    window.toggleAppointmentDetails = function(appointmentId) {
        const detailsRow = document.getElementById(`details-${appointmentId}`);
        const appointmentRow = document.querySelector(`.appointment-row[data-id="${appointmentId}"]`);
        
        if (detailsRow.style.display === 'none') {
            detailsRow.style.display = '';
            appointmentRow.classList.add('editing');
        } else {
            detailsRow.style.display = 'none';
            appointmentRow.classList.remove('editing');
        }
    };
    
    // Función para actualizar una cita
    window.updateAppointment = function(appointmentId, form) {
        // Enviar el formulario
        form.submit();
    };
    
    // Función para aceptar una cita
    window.acceptAppointment = function(appointmentId) {
        // Redirigir al método principal en appointments.js
        if (typeof acceptAppointment === 'function' && acceptAppointment !== window.acceptAppointment) {
            acceptAppointment(appointmentId);
            return;
        }
        
        // Implementación de respaldo por si no existe la función principal
        const form = document.getElementById(`form-${appointmentId}`);
        if (form) {
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'accept';
            form.appendChild(actionInput);
            form.submit();
        }
    };
    
    // Evento para los botones de cancelar cita
    document.querySelectorAll('.cancel-appointment').forEach(button => {
        button.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-id');
            
            if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/citas/${appointmentId}`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'cancel';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                form.appendChild(actionInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
    
    // Función debounce para evitar muchas llamadas en poco tiempo
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }
});
