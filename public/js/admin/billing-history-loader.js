// Variable global para verificar si la página ya está cargada
let pageLoaded = false;

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando historial de facturas...');
    
    // Inicializar fechas
    const dateFromInput = document.querySelector('input[name="date_from"]');
    const dateToInput = document.querySelector('input[name="date_to"]');
    
    // Obtener el botón de filtrar
    const loadBillsButton = document.getElementById('loadBillsBtn');
    
    // Cargar datos al iniciar
    loadBillsData();
    
    // Eventos
    if (loadBillsButton) {
        loadBillsButton.addEventListener('click', function(e) {
            e.preventDefault();
            loadBillsData();
        });
    }
    
    // Función para cargar datos de facturas
    function loadBillsData() {
        const dateFrom = dateFromInput ? dateFromInput.value : '2025-01-01';
        const dateTo = dateToInput ? dateToInput.value : '2025-12-31';
        
        console.log(`Cargando facturas desde ${dateFrom} hasta ${dateTo}`);
        
        // Mostrar indicador de carga
        const loadingIndicator = document.querySelector('.loading');
        if (loadingIndicator) {
            loadingIndicator.style.display = 'flex';
        }
        
        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Realizar petición AJAX
        fetch('/admin/api/historial-facturas', {
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
            
            // Ocultar indicador de carga
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
            
            if (data.success) {
                // Actualizar estadísticas
                updateStatistics(data.data.stats);
                
                // Actualizar tabla
                updateBillsTable(data.data.bills);
            } else {
                showError(data.message || 'Error al cargar datos de facturas');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Ocultar indicador de carga
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
            
            showError('Error de conexión al cargar datos de facturas');
        });
    }
    
    // Función para actualizar estadísticas
    function updateStatistics(stats) {
        const totalMonth = document.getElementById('total-month');
        const pendingBills = document.getElementById('pending-bills');
        
        if (totalMonth) {
            totalMonth.textContent = stats.monthly_bills || '0';
        }
        
        if (pendingBills) {
            pendingBills.textContent = stats.pending || '0';
        }
    }
    
    // Función para actualizar la tabla de facturas
    function updateBillsTable(bills) {
        console.log('Actualizando tabla con datos:', bills);
        
        // Buscar el tbody de la tabla por ID
        let tableBody = document.getElementById('billsTableBody');
        
        if (!tableBody) {
            console.error('No se encontró el tbody de la tabla');
            return;
        }
        
        // Limpiar contenido existente
        tableBody.innerHTML = '';
        
        console.log(`Se encontraron ${bills.length} facturas para mostrar`);
        
        if (!bills || bills.length === 0) {
            console.log('No hay facturas para mostrar');
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `<td colspan="7" class="text-center">No hay facturas para mostrar</td>`;
            tableBody.appendChild(emptyRow);
            return;
        }
        
        // Agregar filas de facturas
        bills.forEach(bill => {
            const row = document.createElement('tr');
            // Usar el invoice_number para identificar la fila sin exponer el ID crudo
            const billNumber = bill.invoice_number.replace('#INV-', '');
            row.id = `bill-${billNumber}`;
            
            // Determinar si se debe mostrar el botón de "Terminado"
            const completedButton = bill.status === 'pendiente' 
                ? `<button class="complete-button" onclick="markBillAsCompleted(${bill.id}, '${billNumber}')">
                    <i class="fas fa-check"></i> Terminado
                   </button>` 
                : '';
            
            row.innerHTML = `
                <td>${bill.invoice_number}</td>
                <td>${bill.patient}</td>
                <td>${bill.date}</td>
                <td>${bill.phone}</td>
                <td>${bill.rfc}</td>
                <td><span class="bill-status status-${bill.status}">${bill.status === 'pendiente' ? 'Pendiente' : 'Realizada'}</span></td>
                <td>
                    <div class="bill-actions">
                        ${completedButton}
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
    }
    
    // Función para mostrar errores
    function showError(message) {
        const tableBody = document.getElementById('billsTableBody');
        if (tableBody) {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">${message}</td></tr>`;
        }
    }
});

// Función global para marcar una factura como completada
function markBillAsCompleted(billId, billNumber) {
    console.log('Marcando factura como completada:', billNumber);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/admin/bills/${billId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar la interfaz
            const statusElement = document.querySelector(`#bill-${billNumber} .bill-status`);
            if (statusElement) {
                statusElement.classList.remove('status-pendiente');
                statusElement.classList.add('status-realizada');
                statusElement.textContent = 'Realizada';
            }
            
            // Ocultar el botón "Terminado"
            const completeButton = document.querySelector(`#bill-${billNumber} .complete-button`);
            if (completeButton) {
                completeButton.style.display = 'none';
            }
            
            // Mostrar notificación de éxito
            alert('Factura marcada como realizada correctamente');
        } else {
            alert('Error al actualizar el estado de la factura');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión al actualizar estado de la factura');
    });
}
