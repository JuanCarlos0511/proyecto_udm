@extends('layouts.admin')

@section('title', 'Generar Reporte')

@section('page-title', 'Generar Reporte')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span>Generar Reporte</span>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/reports-fixed.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .report-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .report-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .date-section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .date-label {
            font-weight: 500;
            margin-right: 10px;
            color: #555;
            width: 60px;
            text-align: right;
        }
        
        .date-picker {
            display: inline-flex;
            align-items: center;
            background-color: #f5f7fb;
            border-radius: 6px;
            padding: 8px 15px;
            cursor: pointer;
            margin: 0 15px;
            position: relative;
        }
        
        /* Flatpickr customizations */
        .flatpickr-calendar {
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        .flatpickr-day.selected {
            background: #6366f1;
            border-color: #6366f1;
        }
        
        .flatpickr-day.selected:hover {
            background: #4f46e5;
            border-color: #4f46e5;
        }
        
        .date-picker i {
            color: #6366f1;
            margin-right: 10px;
        }
        
        .report-description {
            text-align: center;
            color: #666;
            margin: 30px 0;
            line-height: 1.6;
            font-size: 14px;
        }
        
        .download-btn {
            background-color: #6366f1;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            margin: 30px auto 0;
            width: 200px;
        }
        
        .download-btn:hover {
            background-color: #4f46e5;
        }
        
        .download-btn i {
            margin-right: 10px;
        }
        
        /* Preview table styles */
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            display: none;
        }
        
        .appointments-table th {
            background-color: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .appointments-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
        }
        
        .appointments-table tr:hover td {
            background-color: #f9fafb;
        }
        
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            display: none;
        }
        
        .stat-item {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            flex: 1;
            margin: 0 10px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #4f46e5;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6b7280;
        }
        
        .loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }
        
        .loading i {
            color: #6366f1;
            font-size: 24px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endsection

@section('content')
    <div class="report-container">
        <h2 class="report-title">Generar Reporte</h2>
        
        <div class="date-sections">
            <div class="date-section">
                <div class="date-label">Desde</div>
                <div class="date-picker">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="startDateValue">Seleccionar fecha</span>
                    <input type="text" id="startDate" class="flatpickr-input" value="{{ $startDate }}" style="position: absolute; opacity: 0; width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;">
                </div>
            </div>
            
            <div class="date-section">
                <div class="date-label">Hasta</div>
                <div class="date-picker">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="endDateValue">Seleccionar fecha</span>
                    <input type="text" id="endDate" class="flatpickr-input" value="{{ $endDate }}" style="position: absolute; opacity: 0; width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;">
                </div>
            </div>
        </div>
        
        <p class="report-description">
            Seleccione el rango de fechas para generar un reporte de citas.<br>
            El reporte se descargará en formato PDF con todos los datos del período seleccionado.
        </p>
        
        <div class="loading">
            <i class="fas fa-spinner"></i> Cargando datos...
        </div>
        
        <div class="stats-container" id="statsContainer">
            <div class="stat-item">
                <div class="stat-value" id="totalAppointments">0</div>
                <div class="stat-label">Citas Totales</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="completedAppointments">0</div>
                <div class="stat-label">Completadas</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="pendingAppointments">0</div>
                <div class="stat-label">Pendientes</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="totalIncome">$0</div>
                <div class="stat-label">Ingresos</div>
            </div>
        </div>
        
        <table class="appointments-table" id="appointmentsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Fecha</th>
                    <th>Asunto</th>
                    <th>Estado</th>
                    <th>Modalidad</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody id="appointmentsTableBody">
                <!-- This will be populated dynamically by JavaScript -->
            </tbody>
        </table>
        
        <button type="button" id="generatePdfBtn" class="download-btn">
            <i class="fas fa-download"></i>
            <span>Descargar Reporte</span>
        </button>
    </div>
@endsection

@section('scripts')
    <!-- Add jsPDF and jspdf-autotable from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date pickers
            const startDatePicker = flatpickr("#startDate", {
                dateFormat: "Y-m-d",
                defaultDate: document.getElementById('startDate').value,
                locale: "es",
                onChange: function(selectedDates, dateStr) {
                    document.getElementById('startDateValue').textContent = selectedDates[0].toLocaleDateString('es-ES');
                    loadAppointmentData();
                }
            });
            
            const endDatePicker = flatpickr("#endDate", {
                dateFormat: "Y-m-d",
                defaultDate: document.getElementById('endDate').value,
                locale: "es",
                onChange: function(selectedDates, dateStr) {
                    document.getElementById('endDateValue').textContent = selectedDates[0].toLocaleDateString('es-ES');
                    loadAppointmentData();
                }
            });
            
            // Set initial date display values
            if (document.getElementById('startDate').value) {
                document.getElementById('startDateValue').textContent = new Date(document.getElementById('startDate').value).toLocaleDateString('es-ES');
            }
            
            if (document.getElementById('endDate').value) {
                document.getElementById('endDateValue').textContent = new Date(document.getElementById('endDate').value).toLocaleDateString('es-ES');
            }
            
            // Load initial data
            loadAppointmentData();
            
            // Generate PDF button click handler
            document.getElementById('generatePdfBtn').addEventListener('click', generatePDF);
            
            // Function to load appointment data
            function loadAppointmentData() {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                
                // Show loading indicator
                document.querySelector('.loading').style.display = 'block';
                document.getElementById('statsContainer').style.display = 'none';
                document.getElementById('appointmentsTable').style.display = 'none';
                
                // Make AJAX request to get appointment data
                fetch('{{ route("admin.reports.data") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        startDate: startDate,
                        endDate: endDate
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update stats
                        document.getElementById('totalAppointments').textContent = data.data.stats.total;
                        document.getElementById('completedAppointments').textContent = data.data.stats.completed;
                        document.getElementById('pendingAppointments').textContent = data.data.stats.pending;
                        document.getElementById('totalIncome').textContent = '$' + data.data.stats.totalIncome;
                        
                        // Populate table
                        const tableBody = document.getElementById('appointmentsTableBody');
                        tableBody.innerHTML = '';
                        
                        data.data.appointments.forEach(appointment => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${appointment.id}</td>
                                <td>${appointment.patient_name}</td>
                                <td>${appointment.date}</td>
                                <td>${appointment.subject}</td>
                                <td>${appointment.status}</td>
                                <td>${appointment.modality}</td>
                                <td>$${appointment.price}</td>
                            `;
                            tableBody.appendChild(row);
                        });
                        
                        // Show stats and table
                        document.getElementById('statsContainer').style.display = 'flex';
                        document.getElementById('appointmentsTable').style.display = 'table';
                    } else {
                        console.error('Error loading appointment data:', data.errors);
                    }
                    
                    // Hide loading indicator
                    document.querySelector('.loading').style.display = 'none';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.querySelector('.loading').style.display = 'none';
                });
            }
            
            // Function to generate PDF
            function generatePDF() {
                // Get date range
                const startDate = document.getElementById('startDateValue').textContent;
                const endDate = document.getElementById('endDateValue').textContent;
                
                // Get stats
                const totalAppointments = document.getElementById('totalAppointments').textContent;
                const completedAppointments = document.getElementById('completedAppointments').textContent;
                const pendingAppointments = document.getElementById('pendingAppointments').textContent;
                const totalIncome = document.getElementById('totalIncome').textContent;
                
                // Get table data
                const tableRows = document.querySelectorAll('#appointmentsTableBody tr');
                const tableData = [];
                
                tableRows.forEach(row => {
                    const rowData = [];
                    row.querySelectorAll('td').forEach(cell => {
                        rowData.push(cell.textContent);
                    });
                    tableData.push(rowData);
                });
                
                // Create PDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('landscape');
                
                // Add title
                doc.setFontSize(18);
                doc.text('Reporte de Citas', 150, 20, { align: 'center' });
                
                // Add date range
                doc.setFontSize(12);
                doc.text(`Período: ${startDate} - ${endDate}`, 150, 30, { align: 'center' });
                
                // Add stats
                doc.setFontSize(14);
                doc.text('Resumen', 20, 45);
                
                doc.setFontSize(12);
                doc.text(`Citas Totales: ${totalAppointments}`, 20, 55);
                doc.text(`Citas Completadas: ${completedAppointments}`, 20, 65);
                doc.text(`Citas Pendientes: ${pendingAppointments}`, 20, 75);
                doc.text(`Ingresos Totales: ${totalIncome}`, 20, 85);
                
                // Add table
                doc.autoTable({
                    head: [['ID', 'Paciente', 'Fecha', 'Asunto', 'Estado', 'Modalidad', 'Precio']],
                    body: tableData,
                    startY: 100,
                    theme: 'grid',
                    styles: {
                        fontSize: 10,
                        cellPadding: 3,
                        lineColor: [200, 200, 200]
                    },
                    headStyles: {
                        fillColor: [100, 102, 241],
                        textColor: 255,
                        fontStyle: 'bold'
                    },
                    alternateRowStyles: {
                        fillColor: [245, 247, 250]
                    }
                });
                
                // Add footer
                const pageCount = doc.internal.getNumberOfPages();
                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(10);
                    doc.text(`Página ${i} de ${pageCount}`, 290, 200, { align: 'right' });
                    doc.text(`Generado el ${new Date().toLocaleDateString('es-ES')}`, 20, 200);
                }
                
                // Save PDF
                doc.save(`reporte_citas_${startDate.replace(/\s/g, '_')}_${endDate.replace(/\s/g, '_')}.pdf`);
            }
        });
    </script>
@endsection
