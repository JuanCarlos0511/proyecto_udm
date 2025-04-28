document.addEventListener('DOMContentLoaded', function() {
    // Date picker functionality
    const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    const startDatePicker = document.getElementById('startDatePicker');
    const endDatePicker = document.getElementById('endDatePicker');
    const startDateValue = document.getElementById('startDateValue');
    const endDateValue = document.getElementById('endDateValue');
    
    let currentStartDate = { month: 0, year: 2022 }; // Ene 2022
    let currentEndDate = { month: 8, year: 2022 }; // Sep 2022
    let activePickerType = null;
    
    function createDatePickerModal(type) {
        activePickerType = type;
        const currentDate = type === 'start' ? currentStartDate : currentEndDate;
        
        const modal = document.createElement('div');
        modal.className = 'report-modal';
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
            } else {
                endDateValue.textContent = `${months[currentEndDate.month]} ${currentEndDate.year}`;
            }
            document.body.removeChild(modal);
        });
    }
    
    // Open date picker modal on click
    if (startDatePicker) {
        startDatePicker.addEventListener('click', function() {
            createDatePickerModal('start');
        });
    }
    
    if (endDatePicker) {
        endDatePicker.addEventListener('click', function() {
            createDatePickerModal('end');
        });
    }
    
    // Report type selection
    const reportTypeCards = document.querySelectorAll('.report-type-card');
    
    reportTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active class from all cards
            reportTypeCards.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked card
            this.classList.add('active');
            
            // Update hidden input value
            const reportTypeInput = document.getElementById('reportType');
            if (reportTypeInput) {
                reportTypeInput.value = this.getAttribute('data-type');
            }
        });
    });
    
    // Form submission
    const reportForm = document.getElementById('reportForm');
    
    if (reportForm) {
        reportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            let isValid = true;
            
            // Check if a report type is selected
            const reportTypeInput = document.getElementById('reportType');
            if (!reportTypeInput || !reportTypeInput.value) {
                isValid = false;
                alert('Por favor, seleccione un tipo de reporte.');
            }
            
            // If form is valid, submit
            if (isValid) {
                // En una implementación real, aquí se enviaría el formulario o se haría una petición AJAX
                // Para fines de demostración, solo mostraremos una alerta
                alert('Generando reporte...');
                
                // Simulamos un retraso en la generación del reporte
                setTimeout(function() {
                    alert('Reporte generado con éxito. Descargando...');
                }, 2000);
            }
        });
    }
});
