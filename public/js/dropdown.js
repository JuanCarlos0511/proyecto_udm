document.addEventListener('DOMContentLoaded', function() {
    const dropdownNav = document.querySelector('.dropdown-nav');
    const dropdownContent = document.querySelector('.dropdown-content');
    
    if (dropdownNav && dropdownContent) {
        // Toggle dropdown on click
        dropdownNav.addEventListener('click', function(e) {
            if (e.target.closest('.dropdown-content a')) {
                // Don't toggle if clicking a link in the dropdown
                return;
            }
            
            const isVisible = dropdownContent.style.display === 'block';
            dropdownContent.style.display = isVisible ? 'none' : 'block';
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownNav.contains(e.target)) {
                dropdownContent.style.display = 'none';
            }
        });
        
        // Prevent dropdown from closing when hovering over it
        dropdownContent.addEventListener('mouseenter', function() {
            dropdownContent.style.display = 'block';
        });
        
        // Close dropdown when mouse leaves both the nav and content
        const closeDropdownTimeout = 200; // milliseconds
        let timeoutId;
        
        dropdownNav.addEventListener('mouseleave', function(e) {
            // Check if we're moving to the dropdown content
            if (e.relatedTarget && dropdownContent.contains(e.relatedTarget)) {
                return;
            }
            
            timeoutId = setTimeout(function() {
                dropdownContent.style.display = 'none';
            }, closeDropdownTimeout);
        });
        
        dropdownContent.addEventListener('mouseleave', function() {
            timeoutId = setTimeout(function() {
                dropdownContent.style.display = 'none';
            }, closeDropdownTimeout);
        });
        
        dropdownNav.addEventListener('mouseenter', function() {
            clearTimeout(timeoutId);
        });
        
        dropdownContent.addEventListener('mouseenter', function() {
            clearTimeout(timeoutId);
        });
    }
});
