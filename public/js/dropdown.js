document.addEventListener('DOMContentLoaded', function() {
    // Navigation dropdown handling
    setupDropdown('.dropdown-nav', '.dropdown-content');
    
    // Profile dropdown handling
    setupDropdown('.profile-dropdown', '.profile-dropdown-content');
    
    /**
     * Setup dropdown functionality
     * @param {string} dropdownSelector - CSS selector for the dropdown container
     * @param {string} contentSelector - CSS selector for the dropdown content
     */
    function setupDropdown(dropdownSelector, contentSelector) {
        const dropdownElement = document.querySelector(dropdownSelector);
        const dropdownContent = dropdownElement ? dropdownElement.querySelector(contentSelector) : null;
        
        if (!dropdownElement || !dropdownContent) return;
        
        // Toggle dropdown on click
        dropdownElement.addEventListener('click', function(e) {
            if (e.target.closest(contentSelector + ' a')) {
                // Don't toggle if clicking a link in the dropdown
                return;
            }
            
            const isVisible = dropdownContent.style.display === 'block';
            dropdownContent.style.display = isVisible ? 'none' : 'block';
            
            // Close other dropdowns
            document.querySelectorAll(contentSelector).forEach(content => {
                if (content !== dropdownContent) {
                    content.style.display = 'none';
                }
            });
        });
        
        // Prevent dropdown from closing when hovering over it
        dropdownContent.addEventListener('mouseenter', function() {
            dropdownContent.style.display = 'block';
        });
        
        // Close dropdown when mouse leaves both the dropdown and content
        const closeDropdownTimeout = 200; // milliseconds
        let timeoutId;
        
        dropdownElement.addEventListener('mouseleave', function(e) {
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
        
        dropdownElement.addEventListener('mouseenter', function() {
            clearTimeout(timeoutId);
        });
        
        dropdownContent.addEventListener('mouseenter', function() {
            clearTimeout(timeoutId);
        });
    }
    
    // Close all dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        const dropdowns = document.querySelectorAll('.dropdown-nav, .profile-dropdown');
        let clickedInsideDropdown = false;
        
        dropdowns.forEach(dropdown => {
            if (dropdown.contains(e.target)) {
                clickedInsideDropdown = true;
            }
        });
        
        if (!clickedInsideDropdown) {
            document.querySelectorAll('.dropdown-content, .profile-dropdown-content').forEach(content => {
                content.style.display = 'none';
            });
        }
    });
});
