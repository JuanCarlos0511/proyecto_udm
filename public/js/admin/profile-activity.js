document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const activityFilters = document.querySelectorAll('.activity-filter');
    
    activityFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            // Remove active class from all filters
            activityFilters.forEach(f => f.classList.remove('active'));
            
            // Add active class to clicked filter
            this.classList.add('active');
            
            // Here you would typically filter the timeline items based on the selected filter
            // For demonstration purposes, we'll just log the filter type
            const filterType = this.getAttribute('data-filter');
            console.log('Filter selected:', filterType);
            
            // You could implement actual filtering logic here
            // For example, showing/hiding timeline items based on their category
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
            
            // Here you would typically load the corresponding page of timeline items
            // For demonstration purposes, we'll just log the page number
            const pageNumber = this.textContent;
            console.log('Page selected:', pageNumber);
            
            // You could implement actual pagination logic here
            // For example, making an AJAX request to load the next page of activities
        });
    });
});
