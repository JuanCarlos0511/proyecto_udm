@extends('layouts.admin')

@section('title', 'Generar Factura')

@section('page-title', 'Generar Factura')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Generar Factura</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/billing.css') }}">
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
    <script src="{{ asset('js/admin/billing.js') }}"></script>
@endsection
