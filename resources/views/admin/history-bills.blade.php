@extends('layouts.admin')

@section('title', 'Historial de Facturación')

@section('page-title', 'Historial de Facturación')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Historial de Facturación</span>
@endsection

@section('styles')
<style>
    /* Statistics Section */
    .top-section {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .statistics-card {
        flex: 1;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        min-width: 280px;
    }

    .statistics-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }

    .statistics-grid {
        display: flex;
        gap: 15px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
    }

    .stat-icon.purple {
        background-color: #6c5dd3;
    }

    .stat-icon.blue {
        background-color: #3e7bfa;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
    }

    /* Date Range Section */
    .date-range-section {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex: 2;
    }

    .chart-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }
    
    .date-range-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .date-picker-container {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .date-picker-label {
        font-size: 12px;
        color: #666;
    }

    .date-picker {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #f5f6fa;
        border-radius: 8px;
        padding: 10px 15px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .date-picker:hover {
        background-color: #eef0f7;
    }

    .date-picker-icon {
        color: #6c5dd3;
    }

    .date-picker-value {
        font-size: 14px;
        color: #333;
    }

    /* Bills Table */
    .bills-section {
        margin-top: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    .bills-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .bills-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .bills-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .bills-filter {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #f5f6fa;
        border-radius: 8px;
        padding: 8px 15px;
        cursor: pointer;
    }

    .bills-filter-icon {
        color: #6c5dd3;
    }

    .bills-filter-text {
        font-size: 14px;
        color: #333;
    }

    .bills-table {
        width: 100%;
        border-collapse: collapse;
    }

    .bills-table th {
        text-align: left;
        padding: 15px;
        font-size: 14px;
        font-weight: 500;
        color: #666;
        border-bottom: 1px solid #e0e0e0;
    }

    .bills-table td {
        padding: 15px;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #e0e0e0;
    }

    .bills-table tr:last-child td {
        border-bottom: none;
    }

    .bills-table tr:hover {
        background-color: #f9f9f9;
    }

    .bill-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-paid {
        background-color: rgba(52, 168, 83, 0.1);
        color: #34A853;
    }

    .status-pending {
        background-color: rgba(251, 188, 5, 0.1);
        color: #FBBC05;
    }

    .status-overdue {
        background-color: rgba(234, 67, 53, 0.1);
        color: #EA4335;
    }

    .bill-actions {
        display: flex;
        gap: 10px;
    }

    .action-button {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        background-color: #f5f6fa;
        cursor: pointer;
        transition: all 0.2s;
    }

    .action-button:hover {
        background-color: #6c5dd3;
        color: white;
    }

    /* Checkbox styling */
    .checkbox-cell {
        width: 40px;
    }

    .custom-checkbox {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 2px solid #e0e0e0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .custom-checkbox.checked {
        background-color: #6c5dd3;
        border-color: #6c5dd3;
    }

    .custom-checkbox.checked::after {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        color: white;
        font-size: 12px;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-top: 20px;
        gap: 10px;
    }

    .pagination-button {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f6fa;
        color: #666;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination-button:hover {
        background-color: #eef0f7;
    }

    .pagination-button.active {
        background-color: #6c5dd3;
        color: white;
    }

    /* Date Picker Modal */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        width: 300px;
        padding: 20px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .modal-close {
        cursor: pointer;
        font-size: 18px;
        color: #666;
    }

    .month-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .month-item {
        padding: 10px;
        text-align: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .month-item:hover {
        background-color: #f5f6fa;
    }

    .month-item.active {
        background-color: #6c5dd3;
        color: white;
    }

    .year-selector {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .year-value {
        font-size: 16px;
        font-weight: 500;
    }

    .year-arrow {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f6fa;
        cursor: pointer;
        transition: all 0.2s;
    }

    .year-arrow:hover {
        background-color: #eef0f7;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .modal-button {
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .cancel-button {
        background-color: #f5f6fa;
        color: #666;
    }

    .cancel-button:hover {
        background-color: #eef0f7;
    }

    .apply-button {
        background-color: #6c5dd3;
        color: white;
    }

    .apply-button:hover {
        background-color: #5a4cbe;
    }
</style>
@endsection

@section('content')
    <div class="history-container">
        <div class="top-section">
            <div class="statistics-card">
                <h2 class="statistics-title">Estadísticas Rápidas</h2>
                <div class="statistics-grid">
                    <div class="stat-item">
                        <div class="stat-icon purple">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">$24,850</div>
                            <div class="stat-label">Ingresos Este Mes</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon blue">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">42</div>
                            <div class="stat-label">Facturas Emitidas</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="date-range-section">
                <div class="date-range-container">
                    <div class="date-picker-container">
                        <div class="date-picker-label">From</div>
                        <div class="date-picker" id="startDatePicker">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>
                            <span class="date-picker-value" id="startDateValue">Jan 2022</span>
                        </div>
                    </div>
                    <div class="date-picker-container">
                        <div class="date-picker-label">To</div>
                        <div class="date-picker" id="endDatePicker">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>
                            <span class="date-picker-value" id="endDateValue">Sep 2022</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bills-section">
            <div class="bills-header">
                <h2 class="bills-title">Historial de Facturación</h2>
                <div class="bills-actions">
                    <div class="bills-filter">
                        <i class="fas fa-filter bills-filter-icon"></i>
                        <span class="bills-filter-text">Filtrar</span>
                    </div>
                </div>
            </div>
            <table class="bills-table">
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <div class="custom-checkbox" id="selectAll"></div>
                        </th>
                        <th>Nº Factura</th>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Método de Pago</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#INV-001</td>
                        <td>María González</td>
                        <td>24 Ago 2022</td>
                        <td>$350.00</td>
                        <td>Tarjeta de Crédito</td>
                        <td><span class="bill-status status-paid">Pagada</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#INV-002</td>
                        <td>Carlos Rodríguez</td>
                        <td>25 Ago 2022</td>
                        <td>$180.00</td>
                        <td>Efectivo</td>
                        <td><span class="bill-status status-paid">Pagada</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#INV-003</td>
                        <td>Ana Martínez</td>
                        <td>26 Ago 2022</td>
                        <td>$520.00</td>
                        <td>Transferencia</td>
                        <td><span class="bill-status status-pending">Pendiente</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#INV-004</td>
                        <td>José López</td>
                        <td>27 Ago 2022</td>
                        <td>$120.00</td>
                        <td>Tarjeta de Débito</td>
                        <td><span class="bill-status status-paid">Pagada</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="checkbox-cell">
                            <div class="custom-checkbox"></div>
                        </td>
                        <td>#INV-005</td>
                        <td>Laura Sánchez</td>
                        <td>28 Ago 2022</td>
                        <td>$450.00</td>
                        <td>Tarjeta de Crédito</td>
                        <td><span class="bill-status status-overdue">Vencida</span></td>
                        <td>
                            <div class="bill-actions">
                                <div class="action-button">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-print"></i>
                                </div>
                                <div class="action-button">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <div class="pagination-button">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="pagination-button active">1</div>
                <div class="pagination-button">2</div>
                <div class="pagination-button">3</div>
                <div class="pagination-button">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date picker functionality
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const startDatePicker = document.getElementById('startDatePicker');
        const endDatePicker = document.getElementById('endDatePicker');
        const startDateValue = document.getElementById('startDateValue');
        const endDateValue = document.getElementById('endDateValue');
        
        let currentStartDate = { month: 0, year: 2022 }; // Jan 2022
        let currentEndDate = { month: 8, year: 2022 }; // Sep 2022
        let activePickerType = null;
        
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
        
        function updateData() {
            // This would typically fetch data from the server based on the selected date range
            // For demo purposes, we'll just log the current selection
            const startDateText = document.getElementById('startDateValue').textContent;
            const endDateText = document.getElementById('endDateValue').textContent;
            console.log(`Fetching billing data from ${startDateText} to ${endDateText}`);
            
            // Refresh the table data based on the selected date range
        }
    });
</script>
@endsection
