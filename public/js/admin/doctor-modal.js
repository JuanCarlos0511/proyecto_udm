// Script simplificado para manejar el modal de doctores
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script de modal de doctores cargado');
    
    // Elementos del DOM
    const addDoctorBtn = document.getElementById('addDoctorBtn');
    const doctorModal = document.getElementById('doctorModal');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    
    console.log('Elementos encontrados:', {
        addDoctorBtn,
        doctorModal,
        closeModal,
        cancelBtn
    });
    
    // Función para abrir el modal
    function openModal() {
        console.log('Intentando abrir modal');
        if (doctorModal) {
            doctorModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            console.log('Modal abierto');
        } else {
            console.error('Modal no encontrado');
        }
    }
    
    // Función para cerrar el modal
    function closeModal() {
        console.log('Intentando cerrar modal');
        if (doctorModal) {
            doctorModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            console.log('Modal cerrado');
        } else {
            console.error('Modal no encontrado');
        }
    }
    
    // Eventos
    if (addDoctorBtn) {
        console.log('Agregando evento a botón agregar');
        addDoctorBtn.addEventListener('click', function(e) {
            console.log('Botón agregar clickeado');
            e.preventDefault();
            openModal();
        });
    }
    
    if (closeModal) {
        closeModal.addEventListener('click', function(e) {
            console.log('Botón cerrar clickeado');
            e.preventDefault();
            closeModal();
        });
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            console.log('Botón cancelar clickeado');
            e.preventDefault();
            closeModal();
        });
    }
    
    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target === doctorModal) {
            closeModal();
        }
    });
    
    // Agregar evento directo al botón
    console.log('Agregando evento directo al botón');
    if (addDoctorBtn) {
        addDoctorBtn.onclick = function() {
            console.log('Clic directo en botón agregar');
            openModal();
            return false;
        };
    }
});
