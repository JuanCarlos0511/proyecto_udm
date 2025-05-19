// Variable global para verificar si la página ya está cargada
let pageLoaded = false;

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando historial de citas...');
    // Inicializar fechas
    const dateFromInput = document.querySelector('input[name="date_from"]');
    const dateToInput = document.querySelector('input[name="date_to"]');
    
    // Cargar datos al iniciar
    loadAppointmentData();
    
    // Manejar el envío del formulario
    const filterForm = document.querySelector('.date-filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            loadAppointmentData();
        });
    }
    
    // Función para cargar los datos de citas
    function loadAppointmentData() {
        // Mostrar indicador de carga
        document.querySelector('.loading').style.display = 'block';
        document.querySelector('.appointments-table').style.display = 'none';
        
        // Obtener fechas seleccionadas
        const dateFrom = dateFromInput.value;
        const dateTo = dateToInput.value;
        
        console.log(`Cargando citas desde ${dateFrom} hasta ${dateTo}`);
        
        // Obtener token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Realizar petición AJAX
        fetch('/admin/api/historial-citas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                date_from: dateFrom,
                date_to: dateTo
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos:', data);
            
            if (data.success) {
                // Actualizar la tabla con los datos
                updateAppointmentsTable(data.data.appointments);
                
                // Actualizar estadísticas
                if (data.data.stats) {
                    document.getElementById('total-month').textContent = data.data.stats.total_month;
                    document.getElementById('pending-appointments').textContent = data.data.stats.pending;
                }
                
                // Ocultar indicador de carga
                document.querySelector('.loading').style.display = 'none';
                document.querySelector('.appointments-table').style.display = 'table';
            } else {
                console.error('Error al cargar datos:', data.message);
                showError('Error al cargar los datos');
                document.querySelector('.loading').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            showError('Error de conexión al cargar las citas');
            document.querySelector('.loading').style.display = 'none';
        });
    }
    
    // Función para actualizar la tabla de citas
    function updateAppointmentsTable(appointments) {
        console.log('Actualizando tabla con datos:', appointments);
        
        // Buscar el tbody de la tabla por ID específico
        let tableBody = document.getElementById('appointmentsTableBody');
        
        if (!tableBody) {
            console.error('No se encontró el tbody de la tabla');
            // Intentar encontrar la tabla y crear tbody si no existe
            const table = document.querySelector('.appointments-table');
            if (table) {
                console.log('Tabla encontrada, creando tbody');
                const newTbody = document.createElement('tbody');
                table.appendChild(newTbody);
                tableBody = newTbody;
            } else {
                console.error('No se encontró la tabla .appointments-table');
                return;
            }
        }
        
        // Limpiar contenido existente
        tableBody.innerHTML = '';
        
        console.log(`Se encontraron ${appointments.length} citas para mostrar`);
        
        if (!appointments || appointments.length === 0) {
            console.log('No hay citas para mostrar');
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `<td colspan="8" class="text-center">No hay citas para mostrar</td>`;
            tableBody.appendChild(emptyRow);
            return;
        }
        
        appointments.forEach(appointment => {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>${appointment.id}</td>
                <td>${appointment.patient_name}</td>
                <td>${appointment.date}</td>
                <td>${appointment.subject}</td>
                <td><span class="appointment-status status-${appointment.status.toLowerCase()}">${appointment.status}</span></td>
                <td>${appointment.modality}</td>
                <td>$${appointment.price}</td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Agregar eventos a los botones de acción
        document.querySelectorAll('.view-appointment').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                console.log('Ver cita:', id);
                // Aquí iría el código para ver la cita
            });
        });
        
        document.querySelectorAll('.edit-appointment').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                console.log('Editar cita:', id);
                // Aquí iría el código para editar la cita
            });
        });
    }
    
    // Función para mostrar errores
    function showError(message) {
        const tableBody = document.querySelector('#appointmentsTable tbody');
        if (tableBody) {
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">${message}</td></tr>`;
            document.querySelector('.appointments-table').style.display = 'table';
        }
    }
});
