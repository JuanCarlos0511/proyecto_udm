/**
 * Dashboard.js
 * Handles all dashboard-specific functionality
 */

class Dashboard {
    constructor() {
        this.initCharts();
        this.initEventListeners();
    }

    /**
     * Initialize dashboard charts
     */
    initCharts() {
        // This will be called by the component directly
        // The chart initialization is now in the trends-chart component
    }

    /**
     * Initialize event listeners
     */
    initEventListeners() {
        // Add any dashboard-specific event listeners here
        this.initStatCardHover();
        this.initPatientListScroll();
    }

    /**
     * Initialize stat card hover effects
     */
    initStatCardHover() {
        const statCards = document.querySelectorAll('.stat-card');
        
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.12)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.08)';
            });
        });
    }

    /**
     * Initialize patient list scroll behavior
     */
    initPatientListScroll() {
        const patientList = document.querySelector('.patient-list');
        if (patientList) {
            // Add smooth scrolling
            patientList.style.scrollBehavior = 'smooth';
            
            // Add scroll to top button if needed
            if (patientList.scrollHeight > patientList.clientHeight) {
                const scrollTopBtn = document.createElement('button');
                scrollTopBtn.className = 'scroll-top-btn';
                scrollTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
                scrollTopBtn.style.display = 'none';
                
                patientList.parentNode.appendChild(scrollTopBtn);
                
                patientList.addEventListener('scroll', function() {
                    if (this.scrollTop > 100) {
                        scrollTopBtn.style.display = 'block';
                    } else {
                        scrollTopBtn.style.display = 'none';
                    }
                });
                
                scrollTopBtn.addEventListener('click', function() {
                    patientList.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        }
    }

    /**
     * Refresh dashboard data
     * @param {Object} data - New dashboard data
     */
    refreshData(data) {
        // Update stat cards
        if (data.stats) {
            this.updateStatCards(data.stats);
        }
        
        // Update appointments
        if (data.appointments) {
            this.updateAppointments(data.appointments);
        }
        
        // Update patients
        if (data.patients) {
            this.updatePatients(data.patients);
        }
    }

    /**
     * Update stat cards with new data
     * @param {Object} stats - New statistics data
     */
    updateStatCards(stats) {
        // Update income
        if (stats.income) {
            const incomeValue = document.querySelector('.stat-card.income .stat-value');
            const incomeChange = document.querySelector('.stat-card.income .stat-change');
            
            if (incomeValue) {
                incomeValue.textContent = '$' + stats.income.value.toLocaleString();
            }
            
            if (incomeChange) {
                incomeChange.className = 'stat-change ' + (stats.income.change >= 0 ? 'positive' : 'negative');
                incomeChange.innerHTML = (stats.income.change >= 0 ? '+' : '') + stats.income.change + '% <span>vs mes anterior</span>';
            }
        }
        
        // Update appointments
        if (stats.appointments) {
            const appointmentsValue = document.querySelector('.stat-card.appointments .stat-value');
            const appointmentsChange = document.querySelector('.stat-card.appointments .stat-change');
            
            if (appointmentsValue) {
                appointmentsValue.textContent = stats.appointments.value;
            }
            
            if (appointmentsChange) {
                appointmentsChange.className = 'stat-change ' + (stats.appointments.change >= 0 ? 'positive' : 'negative');
                appointmentsChange.innerHTML = (stats.appointments.change >= 0 ? '+' : '') + stats.appointments.change + '% <span>vs mes anterior</span>';
            }
        }
        
        // Update patients
        if (stats.patients) {
            const patientsValue = document.querySelector('.stat-card.patients .stat-value');
            const patientsChange = document.querySelector('.stat-card.patients .stat-change');
            
            if (patientsValue) {
                patientsValue.textContent = stats.patients.value;
            }
            
            if (patientsChange) {
                patientsChange.className = 'stat-change ' + (stats.patients.change >= 0 ? 'positive' : 'negative');
                patientsChange.innerHTML = (stats.patients.change >= 0 ? '+' : '') + stats.patients.change + '% <span>vs mes anterior</span>';
            }
        }
        
        // Update treatments
        if (stats.treatments) {
            const treatmentsValue = document.querySelector('.stat-card.treatments .stat-value');
            const treatmentsChange = document.querySelector('.stat-card.treatments .stat-change');
            
            if (treatmentsValue) {
                treatmentsValue.textContent = stats.treatments.value;
            }
            
            if (treatmentsChange) {
                treatmentsChange.className = 'stat-change ' + (stats.treatments.change >= 0 ? 'positive' : 'negative');
                treatmentsChange.innerHTML = (stats.treatments.change >= 0 ? '+' : '') + stats.treatments.change + '% <span>este mes</span>';
            }
        }
    }

    /**
     * Update appointments table with new data
     * @param {Array} appointments - New appointments data
     */
    updateAppointments(appointments) {
        const tbody = document.querySelector('.appointments-card .admin-table tbody');
        
        if (tbody) {
            // Clear existing rows
            tbody.innerHTML = '';
            
            if (appointments.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="5" class="text-center">No hay citas próximas</td>';
                tbody.appendChild(tr);
            } else {
                // Add new rows
                appointments.forEach(appointment => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${appointment.patient_name}</td>
                        <td>${appointment.formatted_date}</td>
                        <td>${appointment.doctor_name}</td>
                        <td><span class="status-badge ${appointment.status_class}">${appointment.status_text}</span></td>
                        <td>
                            <div class="actions">
                                <a href="/admin/appointments/${appointment.id}" class="action-btn" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        }
    }

    /**
     * Update patients list with new data
     * @param {Array} patients - New patients data
     */
    updatePatients(patients) {
        const patientList = document.querySelector('.patient-list');
        
        if (patientList) {
            // Clear existing items
            patientList.innerHTML = '';
            
            if (patients.length === 0) {
                const item = document.createElement('div');
                item.className = 'patient-item';
                item.innerHTML = '<p class="text-center">No hay pacientes en seguimiento</p>';
                patientList.appendChild(item);
            } else {
                // Add new items
                patients.forEach(patient => {
                    const item = document.createElement('div');
                    item.className = 'patient-item';
                    item.innerHTML = `
                        <div class="patient-avatar">${patient.initials}</div>
                        <div class="patient-info">
                            <div class="patient-name">${patient.name}</div>
                            <div class="patient-details">Última visita: ${patient.last_visit_date} • Tratamiento: ${patient.treatment}</div>
                        </div>
                        <span class="patient-status status-${patient.status}">${patient.status_text}</span>
                    `;
                    patientList.appendChild(item);
                });
            }
        }
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.dashboardApp = new Dashboard();
});
