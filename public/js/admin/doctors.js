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
    
    // Token CSRF para peticiones AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Función para abrir el modal
    function openModal(title = 'Agregar Doctor', id = '') {
        modalTitle.textContent = title;
        doctorId.value = id;
        doctorModal.style.display = 'flex';
        
        // Si es edición, cargar datos del doctor mediante AJAX
        if (id) {
            fetch(`/admin/doctors/${id}/edit`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.doctor) {
                    document.getElementById('doctorName').value = data.doctor.name;
                    document.getElementById('doctorEmail').value = data.doctor.email;
                    document.getElementById('doctorPhone').value = data.doctor.phoneNumber;
                    document.getElementById('doctorStatus').value = data.doctor.status;
                    
                    // Limpiar el campo de contraseña en edición
                    if (document.getElementById('doctorPassword')) {
                        document.getElementById('doctorPassword').value = '';
                    }
                }
            })
            .catch(error => {
                console.error('Error al cargar datos del doctor:', error);
                alert('Error al cargar los datos del doctor. Por favor, intente nuevamente.');
            });
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
        
        const id = doctorId.value;
        const isEditing = id !== '';
        const url = isEditing ? `/admin/doctors/${id}` : '/admin/doctors';
        const method = isEditing ? 'PUT' : 'POST';
        
        // Recopilar datos del formulario
        const formData = new FormData();
        formData.append('name', document.getElementById('doctorName').value);
        formData.append('email', document.getElementById('doctorEmail').value);
        formData.append('phoneNumber', document.getElementById('doctorPhone').value);
        
        // Solo incluir contraseña si se está creando un nuevo doctor o si se ha ingresado una nueva contraseña
        const passwordField = document.getElementById('doctorPassword');
        if (passwordField && passwordField.value) {
            formData.append('password', passwordField.value);
        }
        
        if (isEditing) {
            formData.append('status', document.getElementById('doctorStatus').value);
            formData.append('_method', 'PUT'); // Laravel requiere esto para simular PUT
        } else {
            formData.append('role', 'doctor');
            formData.append('status', 'active');
        }
        
        // Enviar datos mediante AJAX
        fetch(url, {
            method: 'POST', // Siempre POST para FormData, _method simula PUT
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || (isEditing ? 'Doctor actualizado correctamente.' : 'Doctor agregado correctamente.'));
                closeModalFunc();
                // Recargar la página para mostrar los cambios
                window.location.reload();
            } else {
                alert(data.message || 'Error al procesar la solicitud.');
            }
        })
        .catch(error => {
            console.error('Error al guardar doctor:', error);
            alert('Error al procesar la solicitud. Por favor, intente nuevamente.');
        });
    });
    
    // Eventos para editar doctor
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            openModal('Editar Doctor', id);
        });
    });
    

    
    // Cerrar modal si se hace clic fuera de él
    window.addEventListener('click', function(event) {
        if (event.target === doctorModal) {
            closeModalFunc();
        }
    });
});
