document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButton = document.querySelector('.compensations-filter');
    
    if (filterButton) {
        filterButton.addEventListener('click', function() {
            // Here you would typically show a filter modal or dropdown
            console.log('Filter button clicked');
            
            // For demonstration purposes, we'll just log a message
            // In a real implementation, you might show a dropdown or modal with filter options
        });
    }
    
    // Export functionality
    const exportButton = document.querySelector('.compensations-export');
    
    if (exportButton) {
        exportButton.addEventListener('click', function() {
            // Here you would typically trigger an export action
            console.log('Export button clicked');
            
            // For demonstration purposes, we'll just log a message
            // In a real implementation, you might trigger a download of CSV or PDF
        });
    }
    
    // Action buttons functionality
    const actionButtons = document.querySelectorAll('.action-button');
    
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const compensationId = this.closest('tr').getAttribute('data-id');
            
            console.log(`Action ${action} triggered for compensation ${compensationId}`);
            
            // Handle different actions
            switch(action) {
                case 'view':
                    // View compensation details
                    console.log('Viewing compensation details');
                    break;
                case 'download':
                    // Download compensation receipt
                    console.log('Downloading compensation receipt');
                    break;
                default:
                    console.log('Unknown action');
            }
        });
    });
    
    // Pagination functionality
    const paginationButtons = document.querySelectorAll('.pagination-button');
    
    paginationButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Skip if it's already active or it's a navigation arrow
            if (this.classList.contains('active') || this.querySelector('i')) {
                return;
            }
            
            // Remove active class from all pagination buttons
            paginationButtons.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Here you would typically load the corresponding page of compensations
            // For demonstration purposes, we'll just log the page number
            const pageNumber = this.textContent;
            console.log('Page selected:', pageNumber);
            
            // You could implement actual pagination logic here
            // For example, making an AJAX request to load the next page of compensations
        });
    });
});
