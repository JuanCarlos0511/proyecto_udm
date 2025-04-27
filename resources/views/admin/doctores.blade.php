@extends('layouts.admin')

@section('title', 'Gestión de Doctores')

@section('page-title', 'Gestión de Doctores')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Doctores</span>
@endsection

@section('styles')
<style>
    /* Estilos para la sección de doctores */
    .doctors-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .doctors-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .doctors-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }
    
    .doctors-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-add {
        background-color: #6c5dd3;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 8px 16px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-add:hover {
        background-color: #5a4cbe;
    }
    
    .btn-add i {
        font-size: 12px;
    }
    
    .doctors-filters {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        background-color: #f9f9f9;
    }
    
    .search-box {
        position: relative;
        width: 300px;
    }
    
    .search-input {
        width: 100%;
        padding: 8px 16px 8px 40px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
    }
    
    .filter-options {
        display: flex;
        gap: 16px;
    }
    
    .filter-select {
        padding: 8px 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        background-color: white;
    }
    
    .doctors-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .doctors-table th {
        text-align: left;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 500;
        color: #666;
        border-bottom: 1px solid #eaeaea;
    }
    
    .doctors-table td {
        padding: 16px 24px;
        font-size: 14px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .doctors-table tr:hover {
        background-color: #f9f9f9;
    }
    
    .doctor-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .doctor-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .doctor-name {
        font-weight: 500;
    }
    
    .doctor-specialty {
        color: #666;
        font-size: 13px;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-active {
        background-color: #e6f7ee;
        color: #0a9e6e;
    }
    
    .status-inactive {
        background-color: #ffebee;
        color: #ea4335;
    }
    
    .doctor-actions {
        display: flex;
        gap: 8px;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        color: white;
    }
    
    .edit-btn {
        background-color: #3e7bfa;
    }
    
    .edit-btn:hover {
        background-color: #2d6bea;
    }
    
    .delete-btn {
        background-color: #ea4335;
    }
    
    .delete-btn:hover {
        background-color: #d33426;
    }
    
    .pagination {
        display: flex;
        justify-content: flex-end;
        padding: 16px 24px;
        gap: 8px;
    }
    
    .page-item {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
    }
    
    .page-item.active {
        background-color: #6c5dd3;
        color: white;
    }
    
    .page-item:not(.active):hover {
        background-color: #f5f5f5;
    }
    
    /* Estilos para el modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        display: none;
    }
    
    .modal-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        width: 500px;
        max-width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .modal-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #666;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .form-group {
        margin-bottom: 16px;
    }
    
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .form-select {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 12px;
    }
    
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 16px 24px;
        border-top: 1px solid #eaeaea;
        gap: 12px;
    }
    
    .btn {
        padding: 10px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border: none;
    }
    
    .btn-secondary {
        background-color: #f5f5f5;
        color: #333;
    }
    
    .btn-secondary:hover {
        background-color: #e5e5e5;
    }
    
    .btn-primary {
        background-color: #6c5dd3;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #5a4cbe;
    }
</style>
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
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="doctor-avatar">
                            <img src="{{ asset('assets/doctor1.png') }}" alt="Doctor">
                        </div>
                        <div>
                            <div class="doctor-name">Dr. Juan Pérez</div>
                            <div class="doctor-specialty">Medicina General</div>
                        </div>
                    </div>
                </td>
                <td>Medicina General</td>
                <td>juan.perez@clinicamiel.com</td>
                <td>+52 55 1234 5678</td>
                <td><span class="status-badge status-active">Activo</span></td>
                <td>
                    <div class="doctor-actions">
                        <button class="action-btn edit-btn edit-doctor-btn" data-id="1">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete-btn delete-doctor-btn" data-id="1">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="doctor-avatar">
                            <img src="{{ asset('assets/doctor2.png') }}" alt="Doctor">
                        </div>
                        <div>
                            <div class="doctor-name">Dra. María Rodríguez</div>
                            <div class="doctor-specialty">Odontología</div>
                        </div>
                    </div>
                </td>
                <td>Odontología</td>
                <td>maria.rodriguez@clinicamiel.com</td>
                <td>+52 55 8765 4321</td>
                <td><span class="status-badge status-active">Activo</span></td>
                <td>
                    <div class="doctor-actions">
                        <button class="action-btn edit-btn edit-doctor-btn" data-id="2">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete-btn delete-doctor-btn" data-id="2">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="doctor-avatar">
                            <img src="{{ asset('assets/doctor3.png') }}" alt="Doctor">
                        </div>
                        <div>
                            <div class="doctor-name">Dr. Carlos López</div>
                            <div class="doctor-specialty">Cardiología</div>
                        </div>
                    </div>
                </td>
                <td>Cardiología</td>
                <td>carlos.lopez@clinicamiel.com</td>
                <td>+52 55 2468 1357</td>
                <td><span class="status-badge status-inactive">Inactivo</span></td>
                <td>
                    <div class="doctor-actions">
                        <button class="action-btn edit-btn edit-doctor-btn" data-id="3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete-btn delete-doctor-btn" data-id="3">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="doctor-avatar">
                            <img src="{{ asset('assets/doctor4.png') }}" alt="Doctor">
                        </div>
                        <div>
                            <div class="doctor-name">Dra. Ana Martínez</div>
                            <div class="doctor-specialty">Pediatría</div>
                        </div>
                    </div>
                </td>
                <td>Pediatría</td>
                <td>ana.martinez@clinicamiel.com</td>
                <td>+52 55 1357 2468</td>
                <td><span class="status-badge status-active">Activo</span></td>
                <td>
                    <div class="doctor-actions">
                        <button class="action-btn edit-btn edit-doctor-btn" data-id="4">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete-btn delete-doctor-btn" data-id="4">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
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
<div class="modal-overlay" id="doctorModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Agregar Doctor</h3>
            <button class="modal-close" id="closeModal">&times;</button>
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
            <button class="btn btn-secondary" id="cancelBtn">Cancelar</button>
            <button class="btn btn-primary" id="saveBtn">Guardar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection
