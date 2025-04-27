@extends('layouts.admin')

@section('title', 'Generar Factura')

@section('page-title', 'Generar Factura')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Generar Factura</span>
@endsection

@section('styles')
<style>
    /* Scoping all styles to prevent affecting sidebar */
    .admin-content .invoice-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .admin-content .invoice-form {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 20px;
    }
    
    .admin-content .form-header {
        margin-bottom: 30px;
    }
    
    .admin-content .form-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }
    
    .admin-content .form-description {
        font-size: 14px;
        color: #666;
    }
    
    .admin-content .form-section {
        margin-bottom: 25px;
    }
    
    .admin-content .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
    }
    
    .admin-content .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }
    
    .admin-content .form-group {
        flex: 1;
    }
    
    .admin-content .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }
    
    .admin-content .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        color: #333;
        transition: border-color 0.2s;
    }
    
    .admin-content .form-control:focus {
        border-color: #6c5dd3;
        outline: none;
    }
    
    .admin-content .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        color: #333;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 12px;
    }
    
    .admin-content .form-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 5px;
    }
    
    .admin-content .checkbox-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .admin-content .checkbox-label {
        font-size: 14px;
        color: #333;
        cursor: pointer;
    }
    
    .admin-content .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
    }
    
    .btn {
        padding: 12px 25px;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary {
        background-color: #6c5dd3;
        color: white;
        box-shadow: 0 4px 12px rgba(108, 93, 211, 0.2);
    }
    
    .btn-primary:hover {
        background-color: #5a4cbe;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(108, 93, 211, 0.3);
    }
    
    .btn-primary:active {
        transform: translateY(0);
    }
    
    .btn-danger {
        background-color: #f5f6fa;
        color: #ea4335;
    }
    
    .btn-danger:hover {
        background-color: #ffebee;
    }
    
    .btn-icon {
        font-size: 16px;
    }
    
    .btn-large {
        padding: 15px 30px;
        font-size: 16px;
    }
    
    .required-field::after {
        content: '*';
        color: #ea4335;
        margin-left: 4px;
    }
    
    .patient-search-results {
        position: absolute;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 0 0 6px 6px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
        display: none;
    }
    
    .patient-search-results.show {
        display: block;
    }
    
    .patient-result-item {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }
    
    .patient-result-item:last-child {
        border-bottom: none;
    }
    
    .patient-result-item:hover {
        background-color: #f5f6fa;
    }
    
    .patient-name {
        font-weight: 500;
        color: #333;
    }
    
    .patient-email {
        font-size: 12px;
        color: #666;
    }
    
    /* Estilos para la tarjeta de paciente seleccionado */
    .selected-patient-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #f5f8ff;
        border: 1px solid #d0e1fd;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }
    
    .patient-card-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .patient-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #e0e0e0;
    }
    
    .patient-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .patient-info {
        display: flex;
        flex-direction: column;
    }
    
    .patient-name {
        font-weight: 600;
        font-size: 16px;
        color: #333;
        margin-bottom: 4px;
    }
    
    .patient-email {
        font-size: 14px;
        color: #666;
    }
    
    .change-patient-btn {
        background-color: transparent;
        border: none;
        color: #6c5dd3;
        cursor: pointer;
        font-size: 16px;
        padding: 5px 10px;
        border-radius: 4px;
    }
    
    .change-patient-btn:hover {
        background-color: #f0edff;
    }
</style>
@endsection

@section('content')
    <div class="invoice-container">
        <form id="invoiceForm" class="invoice-form">
            <div class="form-header">
                <h2 class="form-title">Información de Facturación</h2>
                <p class="form-description">Complete los siguientes campos para generar una factura.</p>
            </div>
            
            <div class="form-section">
                <h3 class="section-title">Información del Paciente</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="patientSearch" class="form-label required-field">Paciente</label>
                        <div class="patient-search-container">
                            <input type="text" id="patientSearch" class="form-control" placeholder="Buscar por nombre o correo...">
                            <div class="patient-search-results" id="patientSearchResults"></div>
                            <input type="hidden" id="userId" name="user_id">
                        </div>
                    </div>
                </div>
                
                <!-- Tarjeta de información del paciente seleccionado -->
                <div id="selectedPatientCard" class="selected-patient-card" style="display: none;">
                    <div class="patient-card-content">
                        <div class="patient-avatar">
                            <img id="patientAvatar" src="" alt="Foto de perfil">
                        </div>
                        <div class="patient-info">
                            <div class="patient-name" id="patientName"></div>
                            <div class="patient-email" id="patientEmail"></div>
                        </div>
                    </div>
                    <button type="button" id="changePatientBtn" class="change-patient-btn">
                        <i class="fas fa-exchange-alt"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="section-title">Datos Fiscales</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="rfc" class="form-label required-field">RFC</label>
                        <input type="text" id="rfc" name="rfc" class="form-control" placeholder="Ej. XAXX010101000">
                    </div>
                    <div class="form-group">
                        <label for="codigoPostal" class="form-label required-field">Código Postal</label>
                        <input type="text" id="codigoPostal" name="codigo_postal" class="form-control" placeholder="Ej. 06700">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="regimenFiscal" class="form-label required-field">Régimen Fiscal</label>
                        <select id="regimenFiscal" name="regimen_fiscal" class="form-select">
                            <option value="">Seleccionar régimen fiscal</option>
                            <option value="601">601 - General de Ley Personas Morales</option>
                            <option value="603">603 - Personas Morales con Fines no Lucrativos</option>
                            <option value="605">605 - Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
                            <option value="606">606 - Arrendamiento</option>
                            <option value="608">608 - Demás ingresos</option>
                            <option value="609">609 - Consolidación</option>
                            <option value="610">610 - Residentes en el Extranjero sin Establecimiento Permanente en México</option>
                            <option value="611">611 - Ingresos por Dividendos (socios y accionistas)</option>
                            <option value="612">612 - Personas Físicas con Actividades Empresariales y Profesionales</option>
                            <option value="614">614 - Ingresos por intereses</option>
                            <option value="616">616 - Sin obligaciones fiscales</option>
                            <option value="620">620 - Sociedades Cooperativas de Producción que optan por diferir sus ingresos</option>
                            <option value="621">621 - Incorporación Fiscal</option>
                            <option value="622">622 - Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
                            <option value="623">623 - Opcional para Grupos de Sociedades</option>
                            <option value="624">624 - Coordinados</option>
                            <option value="625">625 - Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas</option>
                            <option value="626">626 - Régimen Simplificado de Confianza</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cfdi" class="form-label required-field">Uso de CFDI</label>
                        <select id="cfdi" name="cfdi" class="form-select">
                            <option value="">Seleccionar uso de CFDI</option>
                            <option value="G01">G01 - Adquisición de mercancías</option>
                            <option value="G02">G02 - Devoluciones, descuentos o bonificaciones</option>
                            <option value="G03">G03 - Gastos en general</option>
                            <option value="I01">I01 - Construcciones</option>
                            <option value="I02">I02 - Mobiliario y equipo de oficina por inversiones</option>
                            <option value="I03">I03 - Equipo de transporte</option>
                            <option value="I04">I04 - Equipo de cómputo y accesorios</option>
                            <option value="I05">I05 - Dados, troqueles, moldes, matrices y herramental</option>
                            <option value="I06">I06 - Comunicaciones telefónicas</option>
                            <option value="I07">I07 - Comunicaciones satelitales</option>
                            <option value="I08">I08 - Otra maquinaria y equipo</option>
                            <option value="D01">D01 - Honorarios médicos, dentales y gastos hospitalarios</option>
                            <option value="D02">D02 - Gastos médicos por incapacidad o discapacidad</option>
                            <option value="D03">D03 - Gastos funerales</option>
                            <option value="D04">D04 - Donativos</option>
                            <option value="D05">D05 - Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación)</option>
                            <option value="D06">D06 - Aportaciones voluntarias al SAR</option>
                            <option value="D07">D07 - Primas por seguros de gastos médicos</option>
                            <option value="D08">D08 - Gastos de transportación escolar obligatoria</option>
                            <option value="D09">D09 - Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones</option>
                            <option value="D10">D10 - Pagos por servicios educativos (colegiaturas)</option>
                            <option value="P01">P01 - Por definir</option>
                            <option value="S01">S01 - Sin efectos fiscales</option>
                            <option value="CP01">CP01 - Pagos</option>
                            <option value="CN01">CN01 - Nómina</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <div class="form-checkbox">
                            <input type="checkbox" id="cuentaConSeguro" name="cuenta_con_seguro" class="checkbox-input">
                            <label for="cuentaConSeguro" class="checkbox-label">El paciente cuenta con seguro médico</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" id="clearForm" class="btn btn-danger">
                    <i class="fas fa-trash-alt btn-icon"></i>
                    Limpiar
                </button>
                <button type="submit" class="btn btn-primary btn-large">
                    <i class="fas fa-save btn-icon"></i>
                    Guardar
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const invoiceForm = document.getElementById('invoiceForm');
        const patientSearch = document.getElementById('patientSearch');
        const patientSearchResults = document.getElementById('patientSearchResults');
        const clearFormButton = document.getElementById('clearFormButton');
        const userIdInput = document.getElementById('userId');
        const selectedPatientCard = document.getElementById('selectedPatientCard');
        const patientAvatar = document.getElementById('patientAvatar');
        const patientName = document.getElementById('patientName');
        const patientEmail = document.getElementById('patientEmail');
        const changePatientBtn = document.getElementById('changePatientBtn');
        
        // Datos de pacientes de ejemplo para demostración
        const patients = [
            { id: 1, name: 'María González', email: 'maria.gonzalez@example.com', avatar: '{{ asset("assets/avatar1.png") }}' },
            { id: 2, name: 'Carlos Rodríguez', email: 'carlos.rodriguez@example.com', avatar: '{{ asset("assets/avatar2.png") }}' },
            { id: 3, name: 'Ana Martínez', email: 'ana.martinez@example.com', avatar: '{{ asset("assets/avatar3.png") }}' },
            { id: 4, name: 'José López', email: 'jose.lopez@example.com', avatar: '{{ asset("assets/avatar4.png") }}' },
            { id: 5, name: 'Laura Sánchez', email: 'laura.sanchez@example.com', avatar: '{{ asset("assets/avatar5.png") }}' }
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
</script>
@endsection
