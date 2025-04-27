@extends('layouts.admin')

@section('title', 'Generar Reporte')

@section('page-title', 'Generar Reporte')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Generar Reporte</span>
@endsection

@section('styles')
<style>
    .report-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px 20px;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .date-range-section {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 30px;
        width: 100%;
        margin-bottom: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .date-range-container {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .date-picker-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .date-picker-label {
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }

    .date-picker {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #f5f6fa;
        border-radius: 8px;
        padding: 12px 20px;
        cursor: pointer;
        transition: background-color 0.2s;
        min-width: 150px;
    }

    .date-picker:hover {
        background-color: #eef0f7;
    }

    .date-picker-icon {
        color: #6c5dd3;
        font-size: 16px;
    }

    .date-picker-value {
        font-size: 16px;
        color: #333;
        font-weight: 500;
    }
    
    .download-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        width: 100%;
    }
    
    .download-info {
        text-align: center;
        max-width: 500px;
        margin-bottom: 20px;
    }
    
    .download-title {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }
    
    .download-description {
        font-size: 16px;
        color: #666;
        line-height: 1.5;
    }
    
    .download-button {
        background-color: #6c5dd3;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 20px 60px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 15px rgba(108, 93, 211, 0.3);
    }
    
    .download-button:hover {
        background-color: #5a4cbe;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108, 93, 211, 0.4);
    }
    
    .download-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(108, 93, 211, 0.3);
    }
    
    .download-icon {
        font-size: 20px;
    }
    
    .report-options {
        display: flex;
        gap: 20px;
        margin-top: 30px;
    }
    
    .report-option {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    
    .report-option-radio {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #ddd;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .report-option-radio.selected {
        border-color: #6c5dd3;
    }
    
    .report-option-radio.selected::after {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #6c5dd3;
    }
    
    .report-option-label {
        font-size: 16px;
        color: #333;
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
        width: 320px;
        padding: 25px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }

    .modal-close {
        cursor: pointer;
        font-size: 20px;
        color: #666;
    }

    .month-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 25px;
    }

    .month-item {
        padding: 10px;
        text-align: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 15px;
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
        margin-bottom: 25px;
    }

    .year-value {
        font-size: 18px;
        font-weight: 500;
    }

    .year-arrow {
        width: 36px;
        height: 36px;
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
        gap: 12px;
    }

    .modal-button {
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 15px;
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
    <div class="report-container">
        <div class="date-range-section">
            <div class="date-range-container">
                <div class="date-picker-container">
                    <div class="date-picker-label">Desde</div>
                    <div class="date-picker" id="startDatePicker">
                        <i class="fas fa-calendar-alt date-picker-icon"></i>
                        <span class="date-picker-value" id="startDateValue">Ene 2022</span>
                    </div>
                </div>
                <div class="date-picker-container">
                    <div class="date-picker-label">Hasta</div>
                    <div class="date-picker" id="endDatePicker">
                        <i class="fas fa-calendar-alt date-picker-icon"></i>
                        <span class="date-picker-value" id="endDateValue">Abr 2022</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="download-section">
            <div class="download-info">
                <h2 class="download-title">Generar Reporte</h2>
                <p class="download-description">
                    Seleccione el rango de fechas y el tipo de reporte que desea generar. 
                    El reporte se descargará en formato PDF con todos los datos del período seleccionado.
                </p>
            </div>
            
            <button class="download-button" id="downloadReport">
                <i class="fas fa-download download-icon"></i>
                Descargar Reporte
            </button>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date picker functionality
        const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        const startDatePicker = document.getElementById('startDatePicker');
        const endDatePicker = document.getElementById('endDatePicker');
        const startDateValue = document.getElementById('startDateValue');
        const endDateValue = document.getElementById('endDateValue');
        const downloadButton = document.getElementById('downloadReport');
        
        let currentStartDate = { month: 0, year: 2022 }; // Ene 2022
        let currentEndDate = { month: 3, year: 2022 }; // Abr 2022
        let activePickerType = null;
        let selectedReportType = 'appointments';
        
        // Report type selection
        const reportOptions = document.querySelectorAll('.report-option-radio');
        
        reportOptions.forEach(option => {
            option.addEventListener('click', function() {
                reportOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                selectedReportType = this.getAttribute('data-type');
            });
        });
        
        function createDatePickerModal(type) {
            activePickerType = type;
            const currentDate = type === 'start' ? currentStartDate : currentEndDate;
            
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">Seleccionar ${type === 'start' ? 'Fecha Inicial' : 'Fecha Final'}</div>
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
                        <div class="modal-button cancel-button">Cancelar</div>
                        <div class="modal-button apply-button">Aplicar</div>
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
            });
        }
        
        startDatePicker.addEventListener('click', function() {
            createDatePickerModal('start');
        });
        
        endDatePicker.addEventListener('click', function() {
            createDatePickerModal('end');
        });
        
        // Download button functionality
        downloadButton.addEventListener('click', function() {
            const startDateText = document.getElementById('startDateValue').textContent;
            const endDateText = document.getElementById('endDateValue').textContent;
            
            // Animation for button click
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'none';
            }, 100);
            
            // Here you would typically trigger the report generation and download
            console.log(`Generando reporte de ${selectedReportType} desde ${startDateText} hasta ${endDateText}`);
            
            // Simulate download (in a real app, this would be an AJAX call to generate the report)
            setTimeout(() => {
                alert(`Reporte de ${getReportTypeName(selectedReportType)} generado correctamente para el período ${startDateText} - ${endDateText}`);
            }, 800);
        });
        
        function getReportTypeName(type) {
            switch(type) {
                case 'appointments':
                    return 'Citas';
                case 'bills':
                    return 'Facturación';
                case 'complete':
                    return 'Completo';
                default:
                    return '';
            }
        }
    });
</script>
@endsection
