document.addEventListener('DOMContentLoaded', function() {
    // Elementos DOM
    const scheduleAppointment = document.getElementById('scheduleAppointment');
    const appointmentOptions = document.getElementById('appointmentOptions');
    const scheduleInPerson = document.getElementById('scheduleInPerson');
    const scheduleAtHome = document.getElementById('scheduleAtHome');

    // Event Listeners
    scheduleAppointment.addEventListener('click', () => {
        appointmentOptions.style.display = appointmentOptions.style.display === 'block' ? 'none' : 'block';
    });

    scheduleInPerson.addEventListener('click', () => {
        window.location.href = 'views/appointment.html';
    });

    scheduleAtHome.addEventListener('click', () => {
        window.location.href = 'views/appointment.html?type=home';
    });

    // Cerrar el dropdown cuando se hace clic fuera de él
    document.addEventListener('click', (e) => {
        if (!e.target.matches('.schedule-btn') && !e.target.matches('.dropdown-btn')) {
            appointmentOptions.style.display = 'none';
        }
    });
});
