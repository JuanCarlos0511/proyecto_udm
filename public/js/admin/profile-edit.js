document.addEventListener('DOMContentLoaded', function() {
    // File upload preview
    const photoInput = document.getElementById('photo-upload');
    const profilePhoto = document.querySelector('.profile-photo');
    const uploadButton = document.querySelector('.upload-button');
    const removeButton = document.querySelector('.remove-button');
    
    if (uploadButton) {
        uploadButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (photoInput) {
                photoInput.click();
            }
        });
    }
    
    if (photoInput) {
        photoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    profilePhoto.src = e.target.result;
                }
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    if (removeButton) {
        removeButton.addEventListener('click', function() {
            // Reset the file input
            photoInput.value = '';
            // Set default profile photo
            profilePhoto.src = '/img/default-avatar.png';
        });
    }
    
    // Form validation
    const profileForm = document.getElementById('profile-edit-form');
    
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Get all required inputs
            const requiredInputs = this.querySelectorAll('[required]');
            
            // Check each required input
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    // Add error class or message
                    input.classList.add('is-invalid');
                } else {
                    // Remove error class or message
                    input.classList.remove('is-invalid');
                }
            });
            
            // Email validation
            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.value.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value.trim())) {
                    isValid = false;
                    emailInput.classList.add('is-invalid');
                }
            }
            
            // Phone validation
            const phoneInput = document.getElementById('phone');
            if (phoneInput && phoneInput.value.trim()) {
                const phoneRegex = /^\d{10}$/;
                if (!phoneRegex.test(phoneInput.value.trim())) {
                    isValid = false;
                    phoneInput.classList.add('is-invalid');
                }
            }
            
            // If form is not valid, prevent submission
            if (!isValid) {
                e.preventDefault();
                // Show error message
                alert('Por favor, corrija los errores en el formulario antes de continuar.');
            }
        });
    }
    
    // Cancel button functionality
    const cancelButton = document.querySelector('.cancel-button');
    
    if (cancelButton) {
        cancelButton.addEventListener('click', function(e) {
            e.preventDefault();
            // Redirect to profile page
            window.location.href = '/admin/perfil';
        });
    }
});
