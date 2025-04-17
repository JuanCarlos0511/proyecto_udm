// History Controller
document.addEventListener('DOMContentLoaded', function() {
    // DOM elements
    const appointmentHistoryTable = document.getElementById('appointmentHistory');
    const timeFilterSelect = document.getElementById('timeFilter');
    const generatePdfBtn = document.getElementById('generatePdfBtn');
    
    // Store appointments data
    let appointments = [];
    
    // Create loading overlay
    const loadingOverlay = document.createElement('div');
    loadingOverlay.className = 'loading-overlay';
    loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
    document.body.appendChild(loadingOverlay);
    
    // Initialize
    fetchAppointments();
    
    // Event listeners
    timeFilterSelect.addEventListener('change', function() {
        renderAppointments(filterAppointmentsByTime(appointments, this.value));
    });
    
    generatePdfBtn.addEventListener('click', function() {
        generatePdfReport();
    });
    
    // Fetch appointments from API
    function fetchAppointments() {
        showLoading();
        
        fetch('/api/appointments')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar las citas');
                }
                return response.json();
            })
            .then(data => {
                appointments = data;
                renderAppointments(filterAppointmentsByTime(appointments, timeFilterSelect.value));
                hideLoading();
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoading();
                alert('Error al cargar el historial de citas. Por favor, intente nuevamente.');
            });
    }
    
    // Filter appointments by time period
    function filterAppointmentsByTime(appointments, timePeriod) {
        const now = new Date();
        let startDate = new Date();
        
        switch (timePeriod) {
            case 'day':
                startDate.setDate(now.getDate() - 1);
                break;
            case 'week':
                startDate.setDate(now.getDate() - 7);
                break;
            case 'month':
                startDate.setMonth(now.getMonth() - 1);
                break;
            case 'year':
                startDate.setFullYear(now.getFullYear() - 1);
                break;
            case 'full':
            default:
                return appointments; // Return all appointments
        }
        
        return appointments.filter(appointment => {
            const appointmentDate = new Date(appointment.date);
            return appointmentDate >= startDate && appointmentDate <= now;
        });
    }
    
    // Render appointments to table
    function renderAppointments(appointmentsToRender) {
        appointmentHistoryTable.innerHTML = '';
        
        if (appointmentsToRender.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = '<td colspan="6" class="text-center">No hay citas en el periodo seleccionado</td>';
            appointmentHistoryTable.appendChild(emptyRow);
            return;
        }
        
        appointmentsToRender.forEach(appointment => {
            const row = document.createElement('tr');
            
            // Format date
            const appointmentDate = new Date(appointment.date);
            const formattedDate = appointmentDate.toLocaleDateString('es-ES');
            
            // Create start and end times (this is a placeholder - adjust according to your data structure)
            const startTime = '09:00';
            const endTime = '09:30';
            
            row.innerHTML = `
                <td>${appointment.id}</td>
                <td>${appointment.user ? appointment.user.name : 'N/A'}</td>
                <td>${formattedDate}</td>
                <td>${startTime}</td>
                <td>${endTime}</td>
                <td>${appointment.status}</td>
            `;
            
            appointmentHistoryTable.appendChild(row);
        });
    }
    
    // Generate PDF report
    function generatePdfReport() {
        showLoading();
        
        // Get filtered appointments
        const filteredAppointments = filterAppointmentsByTime(appointments, timeFilterSelect.value);
        
        // Get period text for the report title
        const periodText = getPeriodText(timeFilterSelect.value);
        
        try {
            // Initialize jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Add title
            doc.setFontSize(18);
            doc.text('Reporte de Historial de Citas', 105, 15, { align: 'center' });
            
            // Add period subtitle
            doc.setFontSize(12);
            doc.text(`Periodo: ${periodText}`, 105, 25, { align: 'center' });
            
            // Add date
            const today = new Date();
            const formattedDate = today.toLocaleDateString('es-ES');
            doc.text(`Fecha de generación: ${formattedDate}`, 105, 35, { align: 'center' });
            
            // Add table
            const tableColumn = ["No. Cita", "Doctor", "Fecha", "Hora inicio", "Hora final", "Status"];
            const tableRows = [];
            
            // Add data rows
            filteredAppointments.forEach(appointment => {
                const appointmentDate = new Date(appointment.date);
                const formattedDate = appointmentDate.toLocaleDateString('es-ES');
                const startTime = '09:00';
                const endTime = '09:30';
                
                const tableRow = [
                    appointment.id,
                    appointment.user ? appointment.user.name : 'N/A',
                    formattedDate,
                    startTime,
                    endTime,
                    appointment.status
                ];
                tableRows.push(tableRow);
            });
            
            // Generate table
            doc.autoTable({
                head: [tableColumn],
                body: tableRows,
                startY: 45,
                theme: 'grid',
                styles: {
                    fontSize: 10,
                    cellPadding: 3,
                    lineColor: [200, 200, 200],
                    lineWidth: 0.1,
                },
                headStyles: {
                    fillColor: [220, 53, 69],
                    textColor: 255,
                    fontStyle: 'bold',
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245],
                },
            });
            
            // Add footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(10);
                doc.text(`Página ${i} de ${pageCount}`, 105, doc.internal.pageSize.height - 10, { align: 'center' });
            }
            
            // Save the PDF
            doc.save(`Reporte_Citas_${periodText.replace(/\s+/g, '_')}_${formattedDate.replace(/\//g, '-')}.pdf`);
            
            hideLoading();
        } catch (error) {
            console.error('Error generating PDF:', error);
            hideLoading();
            alert('Error al generar el reporte PDF. Por favor, intente nuevamente.');
        }
    }
    
    // Get period text based on selected time filter
    function getPeriodText(timePeriod) {
        switch (timePeriod) {
            case 'day':
                return 'Último día';
            case 'week':
                return 'Última semana';
            case 'month':
                return 'Último mes';
            case 'year':
                return 'Último año';
            case 'full':
            default:
                return 'Historial completo';
        }
    }
    
    // Show loading overlay
    function showLoading() {
        loadingOverlay.classList.add('active');
    }
    
    // Hide loading overlay
    function hideLoading() {
        loadingOverlay.classList.remove('active');
    }
});
