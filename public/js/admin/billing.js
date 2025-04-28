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
    
    // Datos de pacientes de ejemplo para demostración
    const patients = [
        { id: 1, name: 'María González', email: 'maria.gonzalez@example.com', avatar: '/assets/avatar1.png' },
        { id: 2, name: 'Carlos Rodríguez', email: 'carlos.rodriguez@example.com', avatar: '/assets/avatar2.png' },
        { id: 3, name: 'Ana Martínez', email: 'ana.martinez@example.com', avatar: '/assets/avatar3.png' },
        { id: 4, name: 'José López', email: 'jose.lopez@example.com', avatar: '/assets/avatar4.png' },
        { id: 5, name: 'Laura Sánchez', email: 'laura.sanchez@example.com', avatar: '/assets/avatar5.png' }
    ];
    
    // Funcionalidad de búsqueda de pacientes
    patientSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        if (searchTerm.length < 2) {
            patientSearchResults.classList.remove('show');
            return;
        }
        
        const filteredPatients = patients.filter(patient => 
            patient.name.toLowerCase().includes(searchTerm) || 
            patient.email.toLowerCase().includes(searchTerm)
        );
        
        if (filteredPatients.length > 0) {
            patientSearchResults.innerHTML = '';
            
            filteredPatients.forEach(patient => {
                const resultItem = document.createElement('div');
                resultItem.className = 'patient-result-item';
                resultItem.innerHTML = `
                    <div class="patient-name">${patient.name}</div>
                    <div class="patient-email">${patient.email}</div>
                `;
                
                resultItem.addEventListener('click', function() {
                    selectPatient(patient);
                });
                
                patientSearchResults.appendChild(resultItem);
            });
            
            patientSearchResults.classList.add('show');
        } else {
            patientSearchResults.innerHTML = '<div class="patient-result-item">No se encontraron resultados</div>';
            patientSearchResults.classList.add('show');
        }
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
        
        if (!userId || !rfc || !codigoPostal || !regimenFiscal || !cfdi) {
            alert('Por favor complete todos los campos requeridos.');
            return;
        }
        
        // Aquí normalmente enviaríamos los datos del formulario al servidor
        // Para fines de demostración, solo mostraremos una alerta
        alert('Factura guardada correctamente.');
        
        // Reiniciar formulario después de envío exitoso
        invoiceForm.reset();
        userIdInput.value = '';
        patientSearch.value = '';
    });
});
