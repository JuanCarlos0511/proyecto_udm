// Appointment History Controller
document.addEventListener('DOMContentLoaded', function() {
    // DOM elements
    const appointmentHistoryTable = document.getElementById('appointmentHistory');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const searchInput = document.querySelector('.search-box input');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    // Store appointments data
    let appointments = [];
    let currentFilter = 'all';
    let currentPage = 1;
    const itemsPerPage = 10; // Changed from 10 to 1 for testing
    
    // Initialize
    fetchAppointments();
    
    // Event listeners
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Update filter and render
            currentFilter = this.dataset.filter;
            renderAppointments(filterAppointments(appointments, currentFilter));
        });
    });
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const filteredData = filterAppointments(appointments, currentFilter).filter(item => {
            return item.id.toString().includes(searchTerm) || 
                   item.subject.toLowerCase().includes(searchTerm) || 
                   (item.patient && item.patient.toLowerCase().includes(searchTerm));
        });
        renderAppointments(filteredData);
    });
    
    prevBtn.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
            renderAppointments(filterAppointments(appointments, currentFilter));
        }
    });
    
    nextBtn.addEventListener('click', function() {
        const filteredData = filterAppointments(appointments, currentFilter);
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
            renderAppointments(filteredData);
        }
    });
    
    // Fetch appointments data
    function fetchAppointments() {
        // Show loading state
        appointmentHistoryTable.innerHTML = '<tr><td colspan="8" class="loading-message">Cargando citas...</td></tr>';
        
        // Get CSRF token for the request
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Fetch appointments from the API
        fetch('/api/appointments', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar las citas');
            }
            return response.json();
        })
        .then(data => {
            console.log('Citas cargadas desde la base de datos:', data);
            
            // Process the appointment data
            appointments = data.map(appointment => {
                return {
                    id: appointment.id,
                    date: appointment.date,
                    patient: appointment.user ? appointment.user.name : 'Usuario desconocido',
                    subject: appointment.subject,
                    modality: appointment.modality,
                    status: appointment.status,
                    price: parseFloat(appointment.price)
                };
            });
            
            renderAppointments(filterAppointments(appointments, currentFilter));
            updatePagination();
        })
        .catch(error => {
            console.error('Error al cargar citas:', error);
            
            // Show error message
            appointmentHistoryTable.innerHTML = `
                <tr>
                    <td colspan="8" class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        Error al cargar las citas. Por favor, intenta de nuevo más tarde.
                    </td>
                </tr>
            `;
        });
    }
    
    // Filter appointments by status
    function filterAppointments(data, filter) {
        if (filter === 'all') {
            return data;
        }
        
        return data.filter(item => item.status.toLowerCase() === filter.toLowerCase());
    }
    
    // Render appointments to table
    function renderAppointments(data) {
        appointmentHistoryTable.innerHTML = '';
        
        if (data.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `<td colspan="8" class="empty-message">
                <i class="far fa-calendar-times"></i>
                No hay citas en el periodo seleccionado
            </td>`;
            appointmentHistoryTable.appendChild(emptyRow);
            return;
        }
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedData = data.slice(startIndex, endIndex);
        
        paginatedData.forEach(appointment => {
            const row = document.createElement('tr');
            
            // Format date
            const appointmentDate = new Date(appointment.date);
            const formattedDate = appointmentDate.toLocaleDateString('es-ES');
            
            // Get status class
            const statusClass = getStatusClass(appointment.status);
            
            // Format price
            const formattedPrice = new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN'
            }).format(appointment.price);
            
            row.innerHTML = `
                <td>${appointment.id}</td>
                <td>${formattedDate}</td>
                <td>${appointment.patient}</td>
                <td>${appointment.subject}</td>
                <td>${appointment.modality}</td>
                <td><span class="${statusClass}">${appointment.status}</span></td>
                <td>${formattedPrice}</td>
                <td><button class="view-details-btn">Ver detalles</button></td>
            `;
            
            appointmentHistoryTable.appendChild(row);
        });
    }
    
    // Update pagination display
    function updatePagination() {
        const filteredData = filterAppointments(appointments, currentFilter);
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        
        document.querySelector('.pagination span').textContent = `Página ${currentPage} de ${totalPages}`;
        
        // Update button states
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
    }
    
    // Get status class based on status text
    function getStatusClass(status) {
        status = status.toLowerCase();
        if (status === 'solicitado') {
            return 'status-solicitado';
        } else if (status === 'agendado') {
            return 'status-agendado';
        } else if (status === 'completado') {
            return 'status-completado';
        } else if (status === 'cancelado') {
            return 'status-cancelado';
        }
        return ''; // Default
    }
});
