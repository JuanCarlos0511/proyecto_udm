document.addEventListener('DOMContentLoaded', function() {
    // Dropdown functionality for appointment scheduling
    const scheduleBtn = document.getElementById('scheduleAppointment');
    const appointmentOptions = document.getElementById('appointmentOptions');
    
    if (scheduleBtn && appointmentOptions) {
        scheduleBtn.addEventListener('click', function() {
            appointmentOptions.style.display = appointmentOptions.style.display === 'block' ? 'none' : 'block';
        });
        
        // Close the dropdown if clicked outside
        document.addEventListener('click', function(event) {
            if (!event.target.matches('#scheduleAppointment') && !appointmentOptions.contains(event.target)) {
                appointmentOptions.style.display = 'none';
            }
        });
        
        // Handle in-person appointment scheduling
        const scheduleInPerson = document.getElementById('scheduleInPerson');
        if (scheduleInPerson) {
            scheduleInPerson.addEventListener('click', function() {
                window.location.href = '/appointment';
            });
        }
        
        // Handle at-home appointment scheduling
        const scheduleAtHome = document.getElementById('scheduleAtHome');
        if (scheduleAtHome) {
            scheduleAtHome.addEventListener('click', function() {
                window.location.href = '/appointment?type=home';
            });
        }
    }
    
    // Admin button functionality
    const adminButton = document.getElementById('adminButton');
    if (adminButton) {
        adminButton.addEventListener('click', function() {
            window.location.href = '/admin';
        });
    }
});
