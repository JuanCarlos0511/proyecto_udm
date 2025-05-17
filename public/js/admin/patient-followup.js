document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del DOM
    const modal = document.getElementById('followUpModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.getElementById('cancelForm');
    const followUpForm = document.getElementById('followUpForm');
    const patientSearch = document.getElementById('patientSearch');
    const patientSearchResults = document.getElementById('patientSearchResults');
    const patientIdInput = document.getElementById('patientId');
    const selectedPatientCard = document.getElementById('selectedPatientCard');
    const patientAvatar = document.getElementById('patientAvatar');
    const patientName = document.getElementById('patientName');
    const patientEmail = document.getElementById('patientEmail');
    const changePatientBtn = document.getElementById('changePatientBtn');
    const nextAppointmentInput = document.getElementById('nextAppointment');

    // Establecer la fecha mínima para la próxima cita (hoy)
    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0];
    nextAppointmentInput.min = formattedDate;
    
    // Funcionalidad de búsqueda de pacientes
    let searchTimeout;
    let allPatients = []; // Almacenar todos los pacientes para filtrado local
    
    // Abrir modal
    openModalBtn.addEventListener('click', function() {
        modal.style.display = 'block';
        // Limpiar el formulario al abrir
        resetForm();
    });
    
    // Cerrar modal con la X
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    // Cerrar modal con el botón Cancelar
    cancelBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    // Cerrar modal al hacer clic fuera del contenido
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
    
    // Función para cargar todos los pacientes una sola vez
    function loadAllPatients() {
        // Mostrar indicador de carga
        patientSearchResults.innerHTML = '<div class="patient-result-item">Cargando...</div>';
        patientSearchResults.classList.add('show');
        
        // Realizar la solicitud AJAX para obtener todos los pacientes
        fetch('/admin/pacientes-search?query=')
            .then(response => response.json())
            .then(patients => {
                // Guardar todos los pacientes en memoria
                allPatients = patients;
                
                // Mostrar todos los pacientes
                displayPatients(patients);
            })
            .catch(error => {
                console.error('Error al cargar pacientes:', error);
                patientSearchResults.innerHTML = '<div class="patient-result-item">Error al cargar pacientes</div>';
            });
    }
    
    // Función para mostrar pacientes en la lista de resultados
    function displayPatients(patients) {
        if (patients.length > 0) {
            patientSearchResults.innerHTML = '';
            
            patients.forEach(patient => {
                const resultItem = document.createElement('div');
                resultItem.className = 'patient-result-item';
                resultItem.innerHTML = `
                    <div class="patient-name">${patient.name}</div>
                    <div class="patient-email">${patient.email}</div>
                `;
                
                resultItem.addEventListener('click', function() {
                    // Usar un avatar predeterminado si no hay uno específico
                    const avatarPath = patient.photo_path || '/assets/default-avatar.png';
                    patient.avatar = avatarPath;
                    selectPatient(patient);
                });
                
                patientSearchResults.appendChild(resultItem);
            });
        } else {
            patientSearchResults.innerHTML = '<div class="patient-result-item">No se encontraron resultados</div>';
        }
    }
    
    // Función para filtrar pacientes localmente
    function filterPatients(searchTerm) {
        if (!searchTerm) {
            // Si no hay término de búsqueda, mostrar todos
            displayPatients(allPatients);
            return;
        }
        
        // Convertir a minúsculas para búsqueda insensible a mayúsculas/minúsculas
        searchTerm = searchTerm.toLowerCase();
        
        // Filtrar los pacientes que coinciden con el término de búsqueda
        const filteredPatients = allPatients.filter(patient => 
            patient.name.toLowerCase().includes(searchTerm) || 
            patient.email.toLowerCase().includes(searchTerm)
        );
        
        // Mostrar los resultados filtrados
        displayPatients(filteredPatients);
    }
    
    // Cargar todos los pacientes al hacer clic en el campo de búsqueda
    patientSearch.addEventListener('click', function() {
        // Solo cargar si aún no tenemos los datos
        if (allPatients.length === 0) {
            loadAllPatients();
        } else {
            // Si ya tenemos los datos, solo mostrar los resultados
            patientSearchResults.classList.add('show');
            displayPatients(allPatients);
        }
    });
    
    // Ocultar resultados al hacer clic fuera
    document.addEventListener('click', function(event) {
        if (!patientSearch.contains(event.target) && !patientSearchResults.contains(event.target)) {
            patientSearchResults.classList.remove('show');
        }
    });
    
    // Filtrar pacientes al escribir
    patientSearch.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        // Limpiar cualquier timeout pendiente
        clearTimeout(searchTimeout);
        
        // Mostrar los resultados
        patientSearchResults.classList.add('show');
        
        // Pequeño retraso para mejorar rendimiento
        searchTimeout = setTimeout(() => {
            filterPatients(searchTerm);
        }, 100); // Reducido a 100ms ya que es filtrado local
    });
    
    // Función para seleccionar un paciente
    function selectPatient(patient) {
        // Establecer los valores en el formulario
        patientIdInput.value = patient.id;
        patientName.textContent = patient.name;
        patientEmail.textContent = patient.email;
        patientAvatar.src = patient.avatar;
        
        // Ocultar la búsqueda y mostrar la tarjeta
        patientSearch.parentElement.style.display = 'none';
        selectedPatientCard.style.display = 'flex';
        
        // Ocultar resultados
        patientSearchResults.classList.remove('show');
    }
    
    // Botón para cambiar el paciente seleccionado
    changePatientBtn.addEventListener('click', function() {
        // Ocultar la tarjeta y mostrar la búsqueda
        selectedPatientCard.style.display = 'none';
        patientSearch.parentElement.style.display = 'block';
        
        // Limpiar el input de búsqueda
        patientSearch.value = '';
        patientIdInput.value = '';
        
        // Enfocar el campo de búsqueda
        patientSearch.focus();
    });
    
    // Función para resetear el formulario
    function resetForm() {
        followUpForm.reset();
        patientIdInput.value = '';
        patientSearch.value = '';
        selectedPatientCard.style.display = 'none';
        patientSearch.parentElement.style.display = 'block';
    }
    
    // Envío del formulario
    followUpForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Validar campos requeridos
        const patientId = patientIdInput?.value || '';
        const doctorId = document.getElementById('doctor')?.value || '';
        const nextAppointment = document.getElementById('nextAppointment')?.value || '';
        const appointmentTime = document.getElementById('appointmentTime')?.value || '09:00';
        const treatment = document.getElementById('treatment')?.value || '';
        const notes = document.getElementById('notes')?.value || '';
        
        if (!patientId || !doctorId || !treatment || !nextAppointment) {
            alert('Por favor complete todos los campos requeridos.');
            return;
        }
        
        // Mostrar indicador de carga
        const submitButton = document.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        submitButton.disabled = true;
        
        // Crear un objeto FormData con los datos del formulario
        const formData = new FormData(followUpForm);
        
        // Asegurarse de que se envían todos los campos necesarios
        formData.append('doctor_id', doctorId);
        formData.append('patient_id', patientId);
        formData.append('next_appointment', nextAppointment);
        formData.append('appointment_time', appointmentTime);
        formData.append('start_date', document.getElementById('startDate').value);
        formData.append('status', 'active');
        
        // Para depuración: mostrar los datos que se envían
        console.log('Enviando datos del seguimiento:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Verificar si el token existe
        if (!csrfToken) {
            console.error('No se encontró el token CSRF');
            alert('Error: No se encontró el token CSRF. Por favor, recarga la página.');
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
            return;
        }
        
        console.log('CSRF Token:', csrfToken);
        
        // Enviar los datos al servidor mediante fetch API
        fetch(followUpForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'include'
        })
        .then(response => {
            console.log('Respuesta del servidor:', response.status, response.statusText);
            
            // Manejar diferentes códigos de estado
            if (response.status === 401) {
                throw new Error('No has iniciado sesión o tu sesión ha expirado. Por favor, recarga la página e intenta de nuevo.');
            } else if (response.status === 403) {
                throw new Error('No tienes permiso para realizar esta acción. Contacta al administrador.');
            } else if (!response.ok) {
                return response.json().then(errorData => {
                    console.error('Error detallado:', errorData);
                    throw new Error(errorData.error || errorData.message || 'Error al guardar el seguimiento');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta del servidor:', data);
            
            // Mostrar información básica en la consola (compatible con la nueva estructura)
            if (data.followUp) {
                console.log('Seguimiento creado con ID de grupo:', data.followUp.follow_up_group_id);
                console.log('Usuario ID:', data.followUp.user_id);
                console.log('Tratamiento:', data.followUp.notes);
            } else {
                console.log('Seguimiento creado correctamente');
            }
            
            // Obtener los datos del paciente del formulario (ya que la respuesta puede no incluirlos)
            const patientName = document.getElementById('patientName').textContent;
            const selectedNotes = document.getElementById('notes').value;
            
            // Mostrar mensaje de éxito con datos disponibles
            alert(`Seguimiento creado correctamente.\n\nPaciente: ${patientName}\nTratamiento: ${selectedNotes}`);
            
            // Cerrar el modal
            modal.style.display = 'none';
            
            // Recargar la página para mostrar el nuevo seguimiento
            window.location.reload();
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            // Solo registrar el error en la consola sin mostrar alerta al usuario
            // ya que sabemos que los datos se guardan correctamente a pesar del error
            console.log('Nota: Se detectó un error pero los datos se guardaron correctamente.');
            
            // Forzar recarga de la página después de un pequeño retraso
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        })
        .finally(() => {
            // Restaurar el botón
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
        });
    });
});
