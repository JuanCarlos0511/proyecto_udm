// Mensaje de depuración para confirmar que el script se ha cargado
console.log('Script de doctores cargado correctamente');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded activado');
    // Elementos del DOM
    const addDoctorBtn = document.getElementById('addDoctorBtn');
    console.log('addDoctorBtn:', addDoctorBtn);
    
    const doctorModal = document.getElementById('doctorModal');
    console.log('doctorModal:', doctorModal);
    
    const closeModal = document.getElementById('closeModal');
    console.log('closeModal:', closeModal);
    
    const cancelBtn = document.getElementById('cancelBtn');
    console.log('cancelBtn:', cancelBtn);
    
    const saveBtn = document.getElementById('saveBtn');
    console.log('saveBtn:', saveBtn);
    
    const modalTitle = document.getElementById('modalTitle');
    const doctorForm = document.getElementById('doctorForm');
    const doctorId = document.getElementById('doctorId');
    
    // Campos del formulario
    const doctorName = document.getElementById('doctorName');
    const doctorEmail = document.getElementById('doctorEmail');
    const doctorPhone = document.getElementById('doctorPhone');
    const doctorStatus = document.getElementById('doctorStatus');
    const doctorSpecialty = document.getElementById('doctorSpecialty');
    
    // Botones de editar doctor
    const editBtns = document.querySelectorAll('.edit-doctor-btn');
    
    // Token CSRF para peticiones AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Función para abrir el modal
    function openModal(title = 'Agregar Doctor', id = '') {
        console.log('Abriendo modal con título:', title, 'y id:', id);
        
        if (!doctorModal) {
            console.error('El elemento doctorModal no existe');
            return;
        }
        
        if (modalTitle) modalTitle.textContent = title;
        if (doctorId) doctorId.value = id;
        
        // Forzar la visualización del modal
        doctorModal.style.display = 'flex';
        doctorModal.style.opacity = '1';
        document.body.style.overflow = 'hidden'; // Prevenir scroll en el fondo
        
        console.log('Modal abierto, display:', doctorModal.style.display)
        
        // Si es edición, cargar datos del doctor mediante AJAX
        if (id) {
            fetch(`/admin/doctors/${id}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.doctor) {
                    // Llenar campos del formulario
                    doctorName.value = data.doctor.name;
                    doctorEmail.value = data.doctor.email;
                    doctorPhone.value = data.doctor.phoneNumber || '';
                    doctorStatus.value = data.doctor.status;
                    doctorSpecialty.value = data.doctor.specialty || 'general';
                } else {
                    throw new Error(data.message || 'Error al cargar los datos del doctor');
                }
            })
            .catch(error => {
                console.error('Error al cargar datos del doctor:', error);
                alert('Error al cargar los datos del doctor. Por favor, intente nuevamente.');
                closeModalFunc();
            });
        } else {
            // Si es agregar, limpiar el formulario y establecer valores por defecto
            doctorForm.reset();
            doctorStatus.value = 'active';
            doctorSpecialty.value = 'general';
        }
    }
    
    // Función para cerrar el modal
    function closeModalFunc() {
        doctorModal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restaurar scroll
        doctorForm.reset(); // Limpiar formulario al cerrar
    }
    
    // Eventos para abrir/cerrar el modal
    if (addDoctorBtn) {
        console.log('Agregando evento click a addDoctorBtn');
        addDoctorBtn.addEventListener('click', function(e) {
            console.log('Botón Agregar Doctor clickeado');
            e.preventDefault();
            openModal();
        });
    }
    
    if (closeModal) {
        closeModal.addEventListener('click', function(e) {
            console.log('Botón cerrar modal clickeado');
            e.preventDefault();
            closeModalFunc();
        });
    }
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            console.log('Botón cancelar clickeado');
            e.preventDefault();
            closeModalFunc();
        });
    }
    
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
        
        // Recopilar datos del formulario
        const formData = new FormData();
        formData.append('name', doctorName.value.trim());
        formData.append('email', doctorEmail.value.trim());
        formData.append('phoneNumber', doctorPhone.value.trim());
        formData.append('specialty', doctorSpecialty.value);
        
        // Validaciones adicionales
        if (!formData.get('name')) {
            alert('El nombre es requerido');
            return;
        }
        if (!formData.get('email')) {
            alert('El correo electrónico es requerido');
            return;
        }
        if (!formData.get('phoneNumber')) {
            alert('El teléfono es requerido');
            return;
        }
        
        // Si es edición, incluir estado y método PUT
        if (isEditing) {
            formData.append('status', doctorStatus.value);
            formData.append('_method', 'PUT'); // Laravel requiere esto para simular PUT
        } else {
            formData.append('role', 'doctor');
            formData.append('status', 'active');
            
            // Contraseña solo requerida para nuevos doctores
            const password = prompt('Ingrese la contraseña para el nuevo doctor:');
            if (!password) {
                alert('La contraseña es requerida para crear un nuevo doctor');
                return;
            }
            formData.append('password', password);
        }
        
        // Mostrar indicador de carga
        saveBtn.disabled = true;
        saveBtn.textContent = 'Guardando...';
        
        // Enviar datos mediante AJAX
        fetch(url, {
            method: 'POST', // Siempre POST para FormData, _method simula PUT
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                alert(data.message || (isEditing ? 'Doctor actualizado correctamente.' : 'Doctor agregado correctamente.'));
                
                // Cerrar modal
                closeModalFunc();
                
                // Actualizar la tabla sin recargar la página
                if (data.doctor) {
                    updateDoctorRow(data.doctor, isEditing);
                } else {
                    // Si no tenemos los datos actualizados, recargar la página
                    window.location.reload();
                }
            } else {
                throw new Error(data.message || 'Error al procesar la solicitud');
            }
        })
        .catch(error => {
            console.error('Error al guardar doctor:', error);
            alert(error.message || 'Error al procesar la solicitud. Por favor, intente nuevamente.');
        })
        .finally(() => {
            // Restaurar botón de guardar
            saveBtn.disabled = false;
            saveBtn.textContent = 'Guardar';
        });
    });
    
    // Eventos para editar doctor
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            openModal('Editar Doctor', id);
        });
    });
    

    
    // Función para actualizar o agregar una fila en la tabla de doctores
    function updateDoctorRow(doctor, isEditing) {
        const tbody = document.querySelector('.doctors-table tbody');
        let row;
        
        if (isEditing) {
            // Encontrar la fila existente
            row = document.querySelector(`tr[data-doctor-id="${doctor.id}"]`);
        } else {
            // Crear nueva fila
            row = document.createElement('tr');
            row.setAttribute('data-doctor-id', doctor.id);
            tbody.insertBefore(row, tbody.firstChild);
        }
        
        // Actualizar contenido de la fila
        row.innerHTML = `
            <td>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="doctor-avatar">
                        <img src="${doctor.avatar || '/assets/profile.png'}" alt="${doctor.name}">
                    </div>
                    <div>
                        <div class="doctor-name">${doctor.name}</div>
                        <div class="doctor-specialty">${doctor.specialty || 'General'}</div>
                    </div>
                </div>
            </td>
            <td>${doctor.specialty || 'General'}</td>
            <td>${doctor.email}</td>
            <td>${doctor.phoneNumber || ''}</td>
            <td><span class="status-badge status-${doctor.status}">${doctor.status.charAt(0).toUpperCase() + doctor.status.slice(1)}</span></td>
            <td>
                <div class="doctor-actions">
                    <button class="action-btn edit-btn edit-doctor-btn" data-id="${doctor.id}" title="Editar doctor">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </td>
        `;
        
        // Agregar evento de edición al nuevo botón
        const editBtn = row.querySelector('.edit-doctor-btn');
        editBtn.addEventListener('click', function() {
            openModal('Editar Doctor', this.getAttribute('data-id'));
        });
        
        // Si no hay doctores, eliminar el mensaje de "No hay doctores registrados"
        const emptyMessage = tbody.querySelector('tr td[colspan="6"]');
        if (emptyMessage) {
            emptyMessage.parentElement.remove();
        }
    }
    
    // Cerrar modal si se hace clic fuera de él
    window.addEventListener('click', function(event) {
        if (event.target === doctorModal) {
            closeModalFunc();
        }
    });
});
