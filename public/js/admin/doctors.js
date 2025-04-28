document.addEventListener('DOMContentLoaded', function() {
    const addDoctorBtn = document.getElementById('addDoctorBtn');
    const doctorModal = document.getElementById('doctorModal');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveBtn = document.getElementById('saveBtn');
    const modalTitle = document.getElementById('modalTitle');
    const doctorForm = document.getElementById('doctorForm');
    const doctorId = document.getElementById('doctorId');
    
    // Botones de editar doctor
    const editBtns = document.querySelectorAll('.edit-doctor-btn');
    
    // Botones de eliminar doctor
    const deleteBtns = document.querySelectorAll('.delete-doctor-btn');
    
    // Función para abrir el modal
    function openModal(title = 'Agregar Doctor', id = '') {
        modalTitle.textContent = title;
        doctorId.value = id;
        doctorModal.style.display = 'flex';
        
        // Si es edición, cargar datos del doctor (simulado)
        if (id) {
            // Aquí normalmente harías una petición AJAX para obtener los datos del doctor
            // Para este ejemplo, usamos datos simulados
            const doctorData = {
                1: {
                    name: 'Dr. Juan Pérez',
                    specialty: 'general',
                    email: 'juan.perez@clinicamiel.com',
                    phone: '+52 55 1234 5678',
                    status: 'active'
                },
                2: {
                    name: 'Dra. María Rodríguez',
                    specialty: 'dental',
                    email: 'maria.rodriguez@clinicamiel.com',
                    phone: '+52 55 8765 4321',
                    status: 'active'
                },
                3: {
                    name: 'Dr. Carlos López',
                    specialty: 'cardio',
                    email: 'carlos.lopez@clinicamiel.com',
                    phone: '+52 55 2468 1357',
                    status: 'inactive'
                },
                4: {
                    name: 'Dra. Ana Martínez',
                    specialty: 'pediatria',
                    email: 'ana.martinez@clinicamiel.com',
                    phone: '+52 55 1357 2468',
                    status: 'active'
                }
            };
            
            const doctor = doctorData[id];
            
            document.getElementById('doctorName').value = doctor.name;
            document.getElementById('doctorSpecialty').value = doctor.specialty;
            document.getElementById('doctorEmail').value = doctor.email;
            document.getElementById('doctorPhone').value = doctor.phone;
            document.getElementById('doctorStatus').value = doctor.status;
        } else {
            // Si es agregar, limpiar el formulario
            doctorForm.reset();
        }
    }
    
    // Función para cerrar el modal
    function closeModalFunc() {
        doctorModal.style.display = 'none';
    }
    
    // Eventos para abrir/cerrar el modal
    addDoctorBtn.addEventListener('click', function() {
        openModal();
    });
    
    closeModal.addEventListener('click', closeModalFunc);
    cancelBtn.addEventListener('click', closeModalFunc);
    
    // Evento para guardar doctor
    saveBtn.addEventListener('click', function() {
        // Validar formulario
        if (!doctorForm.checkValidity()) {
            alert('Por favor complete todos los campos requeridos.');
            return;
        }
        
        // Aquí normalmente harías una petición AJAX para guardar los datos
        // Para este ejemplo, solo mostramos un mensaje
        const isEditing = doctorId.value !== '';
        const message = isEditing ? 'Doctor actualizado correctamente.' : 'Doctor agregado correctamente.';
        
        alert(message);
        closeModalFunc();
        
        // En una aplicación real, aquí actualizarías la tabla con los nuevos datos
    });
    
    // Eventos para editar doctor
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            openModal('Editar Doctor', id);
        });
    });
    
    // Eventos para eliminar doctor
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const confirmDelete = confirm('¿Está seguro que desea eliminar este doctor?');
            
            if (confirmDelete) {
                // Aquí normalmente harías una petición AJAX para eliminar el doctor
                // Para este ejemplo, solo mostramos un mensaje
                alert('Doctor eliminado correctamente.');
                
                // En una aplicación real, aquí eliminarías la fila de la tabla
            }
        });
    });
    
    // Cerrar modal si se hace clic fuera de él
    window.addEventListener('click', function(event) {
        if (event.target === doctorModal) {
            closeModalFunc();
        }
    });
});
