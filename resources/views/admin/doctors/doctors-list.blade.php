@extends('layouts.admin')

@section('title', 'Gestión de Doctores')

@section('page-title', 'Gestión de Doctores')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Doctores</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/doctors.css') }}">
@endsection

@section('content')
<div class="doctors-container">
    <div class="doctors-header">
        <h2 class="doctors-title">Listado de Doctores</h2>
        <div class="doctors-actions">
            <button class="btn-add" id="addDoctorBtn">
                <i class="fas fa-plus"></i>
                Agregar Doctor
            </button>            
        </div>
    </div>
    
    <div class="doctors-filters">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Buscar doctor...">
        </div>
        <div class="filter-options">
            <select class="filter-select">
                <option value="">Todas las especialidades</option>
                <option value="general">Medicina General</option>
                <option value="dental">Odontología</option>
                <option value="cardio">Cardiología</option>
                <option value="pediatria">Pediatría</option>
            </select>
            <select class="filter-select">
                <option value="">Todos los estados</option>
                <option value="active">Activo</option>
                <option value="inactive">Inactivo</option>
            </select>
        </div>
    </div>
    
    <table class="doctors-table">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctors as $doctor)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="doctor-avatar">
                            <img src="{{ $doctor->avatar ? $doctor->avatar : asset('assets/profile.png') }}" alt="{{ $doctor->name }}">
                        </div>
                        <div>
                            <div class="doctor-name">{{ $doctor->name }}</div>
                            <div class="doctor-specialty">{{ ucfirst($doctor->role) }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ ucfirst($doctor->role) }}</td>
                <td>{{ $doctor->email }}</td>
                <td>{{ $doctor->phoneNumber }}</td>
                <td><span class="status-badge status-{{ $doctor->status }}">{{ ucfirst($doctor->status) }}</span></td>
                <td>
                    <div class="doctor-actions">
                        <button class="action-btn edit-btn edit-doctor-btn" 
                            data-id="{{ $doctor->id }}" 
                            data-name="{{ $doctor->name }}" 
                            data-email="{{ $doctor->email }}" 
                            data-phone="{{ $doctor->phoneNumber }}" 
                            data-specialty="{{ $doctor->specialty ?? 'general' }}" 
                            data-status="{{ $doctor->status }}" 
                            onclick="editDoctor({{ $doctor->id }})" 
                            title="Editar doctor">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach

            @if(count($doctors) == 0)
            <tr>
                <td colspan="6" class="text-center">No hay doctores registrados</td>
            </tr>
            @endif
        </tbody>
    </table>
    
    <div class="pagination">
        <div class="page-item active">1</div>
        <div class="page-item">2</div>
        <div class="page-item">3</div>
        <div class="page-item">
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar doctor -->
<div class="modal-overlay" id="doctorModal" onclick="if(event.target === this) { this.style.display='none'; document.body.style.overflow='auto'; }">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Agregar Doctor</h3>
            <button class="modal-close" id="closeModal" onclick="document.getElementById('doctorModal').style.display='none';document.body.style.overflow='auto';">&times;</button>
        </div>
        <div class="modal-body">
            <form id="doctorForm">
                <input type="hidden" id="doctorId">
                <div class="form-group">
                    <label for="doctorName" class="form-label">Nombre completo</label>
                    <input type="text" id="doctorName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="doctorSpecialty" class="form-label">Especialidad</label>
                    <select id="doctorSpecialty" class="form-select" required>
                        <option value="">Seleccionar especialidad</option>
                        <option value="general">Medicina General</option>
                        <option value="dental">Odontología</option>
                        <option value="cardio">Cardiología</option>
                        <option value="pediatria">Pediatría</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="doctorEmail" class="form-label">Correo electrónico</label>
                    <input type="email" id="doctorEmail" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="doctorPhone" class="form-label">Teléfono</label>
                    <input type="tel" id="doctorPhone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="doctorStatus" class="form-label">Estado</label>
                    <select id="doctorStatus" class="form-select" required>
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelBtn" onclick="document.getElementById('doctorModal').style.display='none';document.body.style.overflow='auto';">Cancelar</button>
            <button class="btn btn-primary" id="saveBtn" onclick="saveDoctor()">Guardar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Script simplificado para el modal -->
    <script src="{{ asset('js/admin/doctor-modal.js') }}"></script>
    
    <!-- Script principal para CRUD de doctores -->
    <script src="{{ asset('js/admin/doctors.js') }}"></script>
    
    <script>
        // Script inline para asegurar que el modal funcione
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script inline cargado');
            
            const addButton = document.getElementById('addDoctorBtn');
            const modal = document.getElementById('doctorModal');
            
            if (addButton) {
                addButton.addEventListener('click', function() {
                    console.log('Botón agregar clickeado (inline)');
                    // Resetear el formulario para agregar un nuevo doctor
                    document.getElementById('doctorForm').reset();
                    document.getElementById('doctorId').value = '';
                    document.getElementById('modalTitle').textContent = 'Agregar Doctor';
                    document.getElementById('doctorStatus').value = 'active';
                    document.getElementById('doctorSpecialty').value = 'general';
                    
                    // Mostrar el modal
                    if (modal) {
                        modal.style.display = 'flex';
                        document.body.style.overflow = 'hidden';
                        console.log('Modal mostrado (inline)');
                    }
                });
            }
        });
        
        // Función para editar doctor
        function editDoctor(id) {
            console.log('Editando doctor con ID:', id);
            
            // Obtener el botón que fue clickeado
            const button = document.querySelector(`.edit-doctor-btn[data-id="${id}"]`);
            if (!button) {
                console.error('Botón no encontrado');
                return;
            }
            
            // Obtener datos del doctor desde los atributos data-*
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const phone = button.getAttribute('data-phone') || '';
            const specialty = button.getAttribute('data-specialty') || 'general';
            const status = button.getAttribute('data-status');
            
            console.log('Datos del doctor:', { name, email, phone, specialty, status });
            
            // Establecer título del modal
            document.getElementById('modalTitle').textContent = 'Editar Doctor';
            
            // Establecer ID del doctor en el campo oculto
            document.getElementById('doctorId').value = id;
            
            // Llenar campos del formulario
            document.getElementById('doctorName').value = name;
            document.getElementById('doctorEmail').value = email;
            document.getElementById('doctorPhone').value = phone;
            document.getElementById('doctorSpecialty').value = specialty;
            document.getElementById('doctorStatus').value = status;
            
            // Mostrar el modal
            const modal = document.getElementById('doctorModal');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                console.log('Modal mostrado para edición');
            }
        }
        
        // Función para guardar doctor
        function saveDoctor() {
            console.log('Guardando doctor...');
            
            // Obtener el botón de guardar y deshabilitarlo durante el proceso
            const saveBtn = document.getElementById('saveBtn');
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.textContent = 'Guardando...';
            }
            
            try {
                // Obtener datos del formulario
                const doctorId = document.getElementById('doctorId').value;
                const name = document.getElementById('doctorName').value;
                const email = document.getElementById('doctorEmail').value;
                const phone = document.getElementById('doctorPhone').value;
                const specialty = document.getElementById('doctorSpecialty').value;
                const status = document.getElementById('doctorStatus').value;
                
                console.log('Datos recopilados:', { doctorId, name, email, phone, specialty, status });
                
                // Validar campos requeridos
                if (!name || !email || !phone) {
                    showNotification('error', 'Campos incompletos', 'Por favor complete todos los campos requeridos');
                    if (saveBtn) {
                        saveBtn.disabled = false;
                        saveBtn.textContent = 'Guardar';
                    }
                    return;
                }
                
                // Crear objeto FormData para enviar los datos
                const formData = new FormData();
                formData.append('name', name);
                formData.append('email', email);
                formData.append('phoneNumber', phone);
                formData.append('specialty', specialty);
                
                // Determinar si es edición o creación
                const isEditing = doctorId !== '';
                
                // Configurar la URL correcta según las rutas de Laravel
                let url;
                if (isEditing) {
                    url = `/admin/doctores/${doctorId}`;
                    formData.append('status', status);
                    formData.append('_method', 'PUT'); // Laravel requiere esto para simular PUT
                } else {
                    url = '/admin/doctores';
                    
                    // Si es nuevo doctor, solicitar contraseña
                    const password = prompt('Ingrese la contraseña para el nuevo doctor:');
                    if (!password) {
                        showNotification('error', 'Campo requerido', 'La contraseña es requerida para crear un nuevo doctor');
                        if (saveBtn) {
                            saveBtn.disabled = false;
                            saveBtn.textContent = 'Guardar';
                        }
                        return;
                    }
                    
                    formData.append('password', password);
                    formData.append('role', 'doctor');
                    formData.append('status', 'active');
                    formData.append('age', '30'); // Valor por defecto, se puede editar después
                }
                
                console.log('Enviando datos a:', url, 'Modo:', isEditing ? 'Edición' : 'Creación');
                
                // Obtener token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                console.log('CSRF Token:', csrfToken ? 'Obtenido' : 'No encontrado');
                
                // Mostrar datos que se van a enviar
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
                
                // Enviar datos mediante AJAX
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    console.log('Respuesta recibida:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Error response:', text);
                            throw new Error(`Error HTTP: ${response.status}. ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos de respuesta:', data);
                    if (data.success) {
                        // Mostrar mensaje de éxito con notificación
                        const successTitle = isEditing ? 'Doctor actualizado' : 'Doctor agregado';
                        const successMessage = data.message || (isEditing ? 'Doctor actualizado correctamente' : 'Doctor agregado correctamente');
                        showNotification('success', successTitle, successMessage);
                        
                        // Cerrar modal
                        const modal = document.getElementById('doctorModal');
                        if (modal) {
                            modal.style.display = 'none';
                            document.body.style.overflow = 'auto';
                        }
                        
                        // Recargar la página para mostrar los cambios
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000); // Esperar 1 segundo para que se vea la notificación
                    } else {
                        throw new Error(data.message || 'Error al procesar la solicitud');
                    }
                })
                .catch(error => {
                    console.error('Error al guardar doctor:', error);
                    showNotification('error', 'Error', error.message || 'Error al procesar la solicitud. Por favor, intente nuevamente.');
                })
                .finally(() => {
                    // Restaurar botón de guardar
                    if (saveBtn) {
                        saveBtn.disabled = false;
                        saveBtn.textContent = 'Guardar';
                    }
                });
            } catch (error) {
                console.error('Error en la función saveDoctor:', error);
                alert('Ocurrió un error inesperado. Por favor, intente nuevamente.');
                
                // Restaurar botón de guardar
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Guardar';
                }
            }
        }
    </script>
@endsection
