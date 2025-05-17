document.addEventListener('DOMContentLoaded', function() {
    const invoiceForm = document.getElementById('invoiceForm');
    const patientSearch = document.getElementById('patientSearch');
    const patientSearchResults = document.getElementById('patientSearchResults');
    const clearFormButton = document.getElementById('clearForm');
    const userIdInput = document.getElementById('userId');
    const selectedPatientCard = document.getElementById('selectedPatientCard');
    const patientAvatar = document.getElementById('patientAvatar');
    const patientName = document.getElementById('patientName');
    const patientEmail = document.getElementById('patientEmail');
    const changePatientBtn = document.getElementById('changePatientBtn');
    
    // Funcionalidad de búsqueda de pacientes
    let searchTimeout;
    let allPatients = []; // Almacenar todos los pacientes para filtrado local
    
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
                    patient.avatar = '/assets/profile.png';
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
    
    // Filtrar pacientes mientras se escribe (ahora filtrado local)
    patientSearch.addEventListener('input', function() {
        const searchTerm = this.value;
        
        // Limpiar cualquier timeout anterior
        clearTimeout(searchTimeout);
        
        // Pequeño retraso para mejorar rendimiento
        searchTimeout = setTimeout(() => {
            filterPatients(searchTerm);
        }, 100); // Reducido a 100ms ya que es filtrado local
    });
    
    // Función para seleccionar un paciente
    function selectPatient(patient) {
        // Actualizar los campos ocultos
        userIdInput.value = patient.id;
        
        // Mostrar la tarjeta de paciente seleccionado
        patientAvatar.src = patient.avatar;
        patientName.textContent = patient.name;
        patientEmail.textContent = patient.email;
        
        // Ocultar la búsqueda y mostrar la tarjeta
        patientSearch.parentElement.style.display = 'none';
        selectedPatientCard.style.display = 'flex';
        
        // Ocultar resultados de búsqueda
        patientSearchResults.classList.remove('show');
    }
    
    // Botón para cambiar el paciente seleccionado
    changePatientBtn.addEventListener('click', function() {
        // Ocultar la tarjeta y mostrar la búsqueda
        selectedPatientCard.style.display = 'none';
        patientSearch.parentElement.style.display = 'block';
        patientSearch.value = '';
        patientSearch.focus();
    });
    
    // Ocultar resultados de búsqueda al hacer clic fuera
    document.addEventListener('click', function(event) {
        if (!patientSearch.contains(event.target) && !patientSearchResults.contains(event.target)) {
            patientSearchResults.classList.remove('show');
        }
    });
    
    // Funcionalidad del botón para limpiar formulario
    clearFormButton.addEventListener('click', function() {
        invoiceForm.reset();
        userIdInput.value = '';
        patientSearch.value = '';
        selectedPatientCard.style.display = 'none';
        patientSearch.parentElement.style.display = 'block';
    });
    
    // Envío del formulario
    invoiceForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Validar campos requeridos
        const userId = userIdInput.value;
        const rfc = document.getElementById('rfc').value;
        const codigoPostal = document.getElementById('codigoPostal').value;
        const regimenFiscal = document.getElementById('regimenFiscal').value;
        const cfdi = document.getElementById('cfdi').value;
        const cuentaConSeguro = document.getElementById('cuentaConSeguro').checked;
        
        if (!userId || !rfc || !codigoPostal || !regimenFiscal || !cfdi) {
            alert('Por favor complete todos los campos requeridos.');
            return;
        }
        
        // Mostrar indicador de carga
        const submitButton = document.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        submitButton.disabled = true;
        
        // Crear un objeto FormData con los datos del formulario
        const formData = new FormData();
        formData.append('user_id', userId);
        formData.append('rfc', rfc);
        formData.append('codigo_postal', codigoPostal);
        formData.append('cuenta_con_seguro', cuentaConSeguro ? 1 : 0);
        formData.append('regimen_fiscal', regimenFiscal);
        formData.append('cfdi', cfdi);
        formData.append('status', 'pendiente');
        
        // Obtener el token CSRF del formulario y agregarlo a FormData
        const csrfToken = document.querySelector('input[name="_token"]').value;
        formData.append('_token', csrfToken);
        
        // Para depuración: mostrar los datos que se envían
        console.log('Enviando datos:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Enviar los datos al servidor mediante fetch API
        fetch('/admin/bills', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Para depuración: mostrar la respuesta completa
            console.log('Respuesta del servidor:', response);
            
            // Si la respuesta no es exitosa, obtener el texto del error
            if (!response.ok) {
                return response.json().then(errorData => {
                    console.error('Error detallado:', errorData);
                    throw new Error(errorData.message || 'Error al guardar la factura');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos de respuesta exitosa:', data);
            
            // Mostrar mensaje de éxito
            alert('Factura guardada correctamente.');
            
            // Reiniciar formulario después de envío exitoso
            invoiceForm.reset();
            userIdInput.value = '';
            patientSearch.value = '';
            
            // Ocultar la tarjeta de paciente seleccionado
            selectedPatientCard.style.display = 'none';
            patientSearch.parentElement.style.display = 'block';
            
            // Redirigir al historial de facturas después de 1 segundo
            setTimeout(() => {
                window.location.href = '/admin/historial-facturas';
            }, 1000);
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            alert('Error al guardar la factura: ' + error.message);
        })
        .finally(() => {
            // Restaurar el botón
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
        });
    });
});
