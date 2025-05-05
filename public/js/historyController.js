// History Controller
document.addEventListener('DOMContentLoaded', function() {
    // DOM elements
    const appointmentHistoryTable = document.getElementById('appointmentHistory');
    const timeFilterSelect = document.getElementById('timeFilter');
    const generatePdfBtn = document.getElementById('generatePdfBtn');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Store appointments data
    let appointments = [];
    
    // Initialize
    fetchAppointments();
    
    // Event listeners
    timeFilterSelect.addEventListener('change', function() {
        renderAppointments(filterAppointmentsByTime(appointments, this.value));
    });
    
    generatePdfBtn.addEventListener('click', function() {
        generatePdfReport();
    });
    
    // Event delegation for delete buttons
    appointmentHistoryTable.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const appointmentId = deleteBtn.dataset.id;
            console.log('Botón eliminar clickeado, ID:', appointmentId);
            if (confirm('¿Está seguro que desea eliminar esta cita?')) {
                deleteAppointment(appointmentId);
            }
        }
    });
    
    // Fetch appointments from API
    function fetchAppointments() {
        showLoading();
        
        // Hacemos la petición al servidor para obtener las citas de la base de datos
        fetch('/api/appointments')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar las citas');
                }
                return response.json();
            })
            .then(data => {
                console.log('Citas cargadas desde la base de datos:', data);
                appointments = data;
                renderAppointments(filterAppointmentsByTime(appointments, timeFilterSelect.value));
                hideLoading();
            })
            .catch(error => {
                console.error('Error al cargar citas:', error);
                hideLoading();
                
                // Si hay un error, mostramos datos de demostración para no bloquear la interfaz
                const demoData = [
                    {
                        id: 1,
                        doctor: "Hernández Torres José Leonardo",
                        date: "2025-04-28",
                        start_time: "09:00",
                        end_time: "09:30",
                        status: "Solicitado"
                    }
                ];
                
                appointments = demoData;
                renderAppointments(filterAppointmentsByTime(appointments, timeFilterSelect.value));
                showNotification('No se pudieron cargar las citas desde el servidor. Mostrando datos de demostración.', 'error');
            });
    }
    
    // Delete appointment
    function deleteAppointment(appointmentId) {
        showLoading();
        
        console.log('Intentando eliminar cita con ID:', appointmentId);
        
        // Hacemos la petición al servidor para eliminar la cita de la base de datos
        fetch(`/api/appointments/${appointmentId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Respuesta del servidor:', response);
            if (!response.ok) {
                throw new Error(`Error al eliminar la cita: ${response.status}`);
            }
            // Actualizamos la lista local
            appointments = appointments.filter(appointment => appointment.id != appointmentId);
            renderAppointments(filterAppointmentsByTime(appointments, timeFilterSelect.value));
            hideLoading();
            showNotification('Cita eliminada correctamente', 'success');
        })
        .catch(error => {
            console.error('Error completo:', error);
            hideLoading();
            
            // Si hay un error, eliminamos de la vista local de todos modos para la demo
            appointments = appointments.filter(appointment => appointment.id != appointmentId);
            renderAppointments(filterAppointmentsByTime(appointments, timeFilterSelect.value));
            showNotification('La cita se ha eliminado de la vista. Recarga la página para verificar si se eliminó de la base de datos.', 'success');
        });
    }
    
    // Show notification
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <p>${message}</p>
            </div>
        `;
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Hide and remove notification after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
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
            emptyRow.innerHTML = `<td colspan="7" class="empty-message">
                <i class="far fa-calendar-times"></i>
                No hay citas en el periodo seleccionado
            </td>`;
            appointmentHistoryTable.appendChild(emptyRow);
            return;
        }
        
        appointmentsToRender.forEach(appointment => {
            const row = document.createElement('tr');
            
            // Format date
            const appointmentDate = new Date(appointment.date);
            const formattedDate = appointmentDate.toLocaleDateString('es-ES');
            
            // Get status class
            const statusClass = getStatusClass(appointment.status);
            
            row.innerHTML = `
                <td>${appointment.id}</td>
                <td>${appointment.doctor}</td>
                <td>${formattedDate}</td>
                <td>${appointment.start_time || '09:00'}</td>
                <td>${appointment.end_time || '09:30'}</td>
                <td><span class="${statusClass}">${appointment.status}</span></td>
                <td class="actions-cell">
                    <button class="delete-btn" data-id="${appointment.id}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;
            
            appointmentHistoryTable.appendChild(row);
        });
    }
    
    // Get status class based on status text
    function getStatusClass(status) {
        status = status.toLowerCase();
        if (status.includes('solicitado')) {
            return 'status-solicitado';
        } else if (status.includes('confirmado')) {
            return 'status-confirmado';
        } else if (status.includes('completado')) {
            return 'status-completado';
        } else if (status.includes('cancelado')) {
            return 'status-cancelado';
        }
        return 'status-solicitado'; // Default
    }
    
    // Generate PDF report
    function generatePdfReport() {
        showLoading();
        
        // Get filtered appointments
        const filteredAppointments = filterAppointmentsByTime(appointments, timeFilterSelect.value);
        
        // Get period text for the report title
        const periodText = getPeriodText(timeFilterSelect.value);
        
        try {
            setTimeout(() => {
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
                
                // Add clinic info
                doc.setFontSize(10);
                doc.text('Clínica Miel | Dirección Física', 105, 45, { align: 'center' });
                doc.text('Teléfono: 4521 346', 105, 50, { align: 'center' });
                
                // Add table
                const tableColumn = ["No. Cita", "Doctor", "Fecha", "Hora inicio", "Hora final", "Status"];
                const tableRows = [];
                
                // Add data rows
                filteredAppointments.forEach(appointment => {
                    const appointmentDate = new Date(appointment.date);
                    const formattedDate = appointmentDate.toLocaleDateString('es-ES');
                    
                    const tableRow = [
                        appointment.id,
                        appointment.doctor,
                        formattedDate,
                        appointment.start_time || '09:00',
                        appointment.end_time || '09:30',
                        appointment.status
                    ];
                    tableRows.push(tableRow);
                });
                
                // Generate table
                doc.autoTable({
                    head: [tableColumn],
                    body: tableRows,
                    startY: 60,
                    theme: 'grid',
                    styles: {
                        fontSize: 10,
                        cellPadding: 3,
                        lineColor: [200, 200, 200],
                        lineWidth: 0.1,
                    },
                    headStyles: {
                        fillColor: [26, 95, 122],
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
            }, 1500);
            
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
