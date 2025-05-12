document.addEventListener('DOMContentLoaded', function() {
    // Cargar datos de facturas desde el servidor
    loadBillsData();
    
    // Date picker functionality
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const startDatePicker = document.getElementById('startDatePicker');
    const endDatePicker = document.getElementById('endDatePicker');
    const startDateValue = document.getElementById('startDateValue');
    const endDateValue = document.getElementById('endDateValue');
    
    // Establecer fechas iniciales (mes actual y 6 meses atrás)
    const currentDate = new Date();
    let currentStartDate = { 
        month: new Date(currentDate.getFullYear(), currentDate.getMonth() - 6, 1).getMonth(), 
        year: new Date(currentDate.getFullYear(), currentDate.getMonth() - 6, 1).getFullYear() 
    };
    let currentEndDate = { 
        month: currentDate.getMonth(), 
        year: currentDate.getFullYear() 
    };
    
    // Actualizar los valores mostrados
    startDateValue.textContent = `${months[currentStartDate.month]} ${currentStartDate.year}`;
    endDateValue.textContent = `${months[currentEndDate.month]} ${currentEndDate.year}`;
    
    let activePickerType = null;
    
    // Función para cargar datos de facturas
    function loadBillsData() {
        const billsTableBody = document.querySelector('.bills-table tbody');
        const statisticsValues = document.querySelectorAll('.stat-value');
        
        // Mostrar indicador de carga
        billsTableBody.innerHTML = '<tr><td colspan="8" class="loading-cell">Cargando facturas...</td></tr>';
        
        // Realizar la solicitud AJAX para obtener los datos de facturas
        fetch('/admin/bills/data')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar los datos de facturas');
                }
                return response.json();
            })
            .then(data => {
                // Actualizar estadísticas
                if (data.statistics) {
                    statisticsValues[0].textContent = `$${data.statistics.monthly_income || 0}`;
                    statisticsValues[1].textContent = data.statistics.total_bills || 0;
                }
                
                // Limpiar la tabla
                billsTableBody.innerHTML = '';
                
                // Si no hay facturas, mostrar mensaje
                if (!data.bills || data.bills.length === 0) {
                    billsTableBody.innerHTML = '<tr><td colspan="8" class="empty-cell">No se encontraron facturas</td></tr>';
                    return;
                }
                
                // Agregar las facturas a la tabla
                data.bills.forEach(bill => {
                    const row = document.createElement('tr');
                    
                    // Determinar el estado de la factura
                    let statusClass = '';
                    let statusText = '';
                    
                    switch(bill.status) {
                        case 'pendiente':
                            statusClass = 'status-pending';
                            statusText = 'Pendiente';
                            break;
                        case 'realizada':
                            statusClass = 'status-paid';
                            statusText = 'Pagada';
                            break;
                        default:
                            statusClass = 'status-pending';
                            statusText = 'Pendiente';
                    }
                    
                    row.innerHTML = `
                        <td class="checkbox-cell">
                            <div class="custom-checkbox" data-id="${bill.id}"></div>
                        </td>
                        <td>#INV-${bill.id.toString().padStart(3, '0')}</td>
                        <td>${bill.patient_name}</td>
                        <td>${bill.created_at}</td>
                        <td>$350.00</td>
                        <td>Tarjeta de Crédito</td>
                        <td><span class="bill-status ${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button" data-action="view" data-id="${bill.id}">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button" data-action="print" data-id="${bill.id}">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button" data-action="download" data-id="${bill.id}">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    `;
                    
                    billsTableBody.appendChild(row);
                });
                
                // Reiniciar la funcionalidad de los checkboxes
                initCheckboxes();
                
                // Inicializar acciones de facturas
                initBillActions();
            })
            .catch(error => {
                console.error('Error:', error);
                billsTableBody.innerHTML = '<tr><td colspan="8" class="error-cell">Error al cargar las facturas</td></tr>';
            });
    }
    
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
            } else {
                endDateValue.textContent = `${months[currentEndDate.month]} ${currentEndDate.year}`;
            }
            document.body.removeChild(modal);
        });
    }
    
    // Open date picker modal on click
    startDatePicker.addEventListener('click', function() {
        createDatePickerModal('start');
    });
    
    endDatePicker.addEventListener('click', function() {
        createDatePickerModal('end');
    });
    
    // Función para inicializar la funcionalidad de los checkboxes
    function initCheckboxes() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.custom-checkbox:not(#selectAll)');
        
        // Limpiar eventos anteriores
        const newSelectAllCheckbox = selectAllCheckbox.cloneNode(true);
        selectAllCheckbox.parentNode.replaceChild(newSelectAllCheckbox, selectAllCheckbox);
        
        // Agregar evento al checkbox de seleccionar todos
        newSelectAllCheckbox.addEventListener('click', function() {
            const isChecked = this.classList.contains('checked');
            if (isChecked) {
                this.classList.remove('checked');
                rowCheckboxes.forEach(checkbox => {
                    checkbox.classList.remove('checked');
                });
            } else {
                this.classList.add('checked');
                rowCheckboxes.forEach(checkbox => {
                    checkbox.classList.add('checked');
                });
            }
        });
        
        // Agregar eventos a los checkboxes de las filas
        rowCheckboxes.forEach(checkbox => {
            const newCheckbox = checkbox.cloneNode(true);
            checkbox.parentNode.replaceChild(newCheckbox, checkbox);
            
            newCheckbox.addEventListener('click', function() {
                this.classList.toggle('checked');
                
                // Verificar si todos los checkboxes están marcados
                const allChecked = Array.from(rowCheckboxes).every(cb => cb.classList.contains('checked'));
                
                if (allChecked) {
                    newSelectAllCheckbox.classList.add('checked');
                } else {
                    newSelectAllCheckbox.classList.remove('checked');
                }
            });
        });
    }
    
    // Función para inicializar las acciones de facturas
    function initBillActions() {
        const actionButtons = document.querySelectorAll('.action-button');
        
        actionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                const billId = this.getAttribute('data-id');
                
                switch(action) {
                    case 'view':
                        window.location.href = `/admin/facturas/${billId}`;
                        break;
                    case 'print':
                        alert('Imprimiendo factura #' + billId);
                        // Aquí se implementaría la funcionalidad de impresión
                        break;
                    case 'download':
                        alert('Descargando factura #' + billId);
                        // Aquí se implementaría la funcionalidad de descarga
                        break;
                }
            });
        });
    }
    
    // Inicializar filtros
    document.querySelector('.bills-filter').addEventListener('click', function() {
        alert('Funcionalidad de filtrado en desarrollo');
    });
    
    // Aplicar filtros cuando se cambian las fechas
    startDatePicker.addEventListener('click', function() {
        createDatePickerModal('start');
    });
    
    endDatePicker.addEventListener('click', function() {
        createDatePickerModal('end');
    });
});
