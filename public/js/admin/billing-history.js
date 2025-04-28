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
    
    // Checkbox functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.custom-checkbox:not(#selectAll)');
    
    selectAllCheckbox.addEventListener('click', function() {
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
    
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('click', function() {
            this.classList.toggle('checked');
            
            // Check if all checkboxes are checked
            const allChecked = Array.from(rowCheckboxes).every(cb => cb.classList.contains('checked'));
            const someChecked = Array.from(rowCheckboxes).some(cb => cb.classList.contains('checked'));
            
            if (allChecked) {
                selectAllCheckbox.classList.add('checked');
            } else {
                selectAllCheckbox.classList.remove('checked');
            }
        });
    });
});
