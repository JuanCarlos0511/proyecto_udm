<div class="graph-container">
    <div class="graph-header">
        <div class="graph-title">Análisis de Tendencias</div>
        <div class="graph-filters">
            <button class="filter-btn active" data-type="income">Ingresos</button>
            <button class="filter-btn" data-type="appointments">Citas</button>
            <button class="filter-btn" data-type="patients">Pacientes</button>
        </div>
    </div>
    <div class="graph-time-filter">
        <button class="filter-btn active" data-period="week">Semana</button>
        <button class="filter-btn" data-period="month">Mes</button>
        <button class="filter-btn" data-period="quarter">Trimestre</button>
        <button class="filter-btn" data-period="year">Año</button>
    </div>
    <div class="graph-body">
        <canvas id="trendsChart" height="300"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart initialization
        const ctx = document.getElementById('trendsChart').getContext('2d');
        
        // Data from controller
        const incomeData = @json($incomeData);
        const appointmentsData = @json($appointmentsData);
        const patientsData = @json($patientsData);
        const labels = @json($chartLabels);
        
        // Default settings
        let currentType = 'income';
        let currentPeriod = 'week';
        
        // Create chart
        const trendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels[currentPeriod],
                datasets: [{
                    label: 'Ingresos',
                    data: incomeData[currentPeriod],
                    borderColor: '#4a6cf7',
                    backgroundColor: 'rgba(74, 108, 247, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (currentType === 'income') {
                                    label += '$' + context.parsed.y.toLocaleString();
                                } else {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (currentType === 'income') {
                                    return '$' + value.toLocaleString();
                                }
                                return value;
                            }
                        }
                    }
                }
            }
        });
        
        // Handle data type filter buttons
        document.querySelectorAll('.graph-filters .filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.graph-filters .filter-btn').forEach(b => {
                    b.classList.remove('active');
                });
                this.classList.add('active');
                
                // Update chart data
                currentType = this.getAttribute('data-type');
                updateChart();
            });
        });
        
        // Handle time period filter buttons
        document.querySelectorAll('.graph-time-filter .filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.graph-time-filter .filter-btn').forEach(b => {
                    b.classList.remove('active');
                });
                this.classList.add('active');
                
                // Update chart data
                currentPeriod = this.getAttribute('data-period');
                updateChart();
            });
        });
        
        // Function to update chart based on current filters
        function updateChart() {
            let data;
            let chartLabel;
            
            switch(currentType) {
                case 'income':
                    data = incomeData[currentPeriod];
                    chartLabel = 'Ingresos';
                    break;
                case 'appointments':
                    data = appointmentsData[currentPeriod];
                    chartLabel = 'Citas';
                    break;
                case 'patients':
                    data = patientsData[currentPeriod];
                    chartLabel = 'Pacientes';
                    break;
            }
            
            trendsChart.data.labels = labels[currentPeriod];
            trendsChart.data.datasets[0].label = chartLabel;
            trendsChart.data.datasets[0].data = data;
            
            trendsChart.update();
        }
    });
</script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart initialization
        const ctx = document.getElementById('trendsChart').getContext('2d');
        
        // Chart data from backend
        const incomeData = @json($incomeData);
        const appointmentsData = @json($appointmentsData);
        const patientsData = @json($patientsData);
        
        // Labels for different time periods
        const labels = @json($chartLabels);
        
        // Default settings
        let currentType = 'income';
        let currentPeriod = 'week';
        
        // Create chart
        const trendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels[currentPeriod],
                datasets: [{
                    label: 'Ingresos',
                    data: incomeData[currentPeriod],
                    borderColor: '#4a6cf7',
                    backgroundColor: 'rgba(74, 108, 247, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (currentType === 'income') {
                                    label += '$' + context.parsed.y.toLocaleString();
                                } else {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (currentType === 'income') {
                                    return '$' + value.toLocaleString();
                                }
                                return value;
                            }
                        }
                    }
                }
            }
        });
        
        // Handle data type filter buttons
        document.querySelectorAll('.graph-filters .filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.graph-filters .filter-btn').forEach(b => {
                    b.classList.remove('active');
                });
                this.classList.add('active');
                
                // Update chart data
                currentType = this.getAttribute('data-type');
                updateChart();
            });
        });
        
        // Handle time period filter buttons
        document.querySelectorAll('.graph-time-filter .filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.graph-time-filter .filter-btn').forEach(b => {
                    b.classList.remove('active');
                });
                this.classList.add('active');
                
                // Update chart data
                currentPeriod = this.getAttribute('data-period');
                updateChart();
            });
        });
        
        // Function to update chart based on current filters
        function updateChart() {
            let data;
            let chartLabel;
            
            switch(currentType) {
                case 'income':
                    data = incomeData[currentPeriod];
                    chartLabel = 'Ingresos';
                    break;
                case 'appointments':
                    data = appointmentsData[currentPeriod];
                    chartLabel = 'Citas';
                    break;
                case 'patients':
                    data = patientsData[currentPeriod];
                    chartLabel = 'Pacientes';
                    break;
            }
            
            trendsChart.data.labels = labels[currentPeriod];
            trendsChart.data.datasets[0].label = chartLabel;
            trendsChart.data.datasets[0].data = data;
            
            trendsChart.update();
        }
    });
</script>
@endpush
