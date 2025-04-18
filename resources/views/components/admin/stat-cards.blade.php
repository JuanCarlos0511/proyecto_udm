<div class="dashboard-stats">
    <div class="stat-card income">
        <div class="stat-icon">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-info">
            <h3>Ingreso Mensual</h3>
            <p class="stat-value">${{ number_format($incomeValue, 0, '.', ',') }}</p>
            <p class="stat-change {{ $incomeChange >= 0 ? 'positive' : 'negative' }}">{{ $incomeChange >= 0 ? '+' : '' }}{{ $incomeChange }}% <span>vs mes anterior</span></p>
        </div>
    </div>
    
    <div class="stat-card appointments">
        <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <h3>Citas este Mes</h3>
            <p class="stat-value">{{ $appointmentsCount }}</p>
            <p class="stat-change {{ $appointmentsChange >= 0 ? 'positive' : 'negative' }}">{{ $appointmentsChange >= 0 ? '+' : '' }}{{ $appointmentsChange }}% <span>vs mes anterior</span></p>
        </div>
    </div>
    
    <div class="stat-card patients">
        <div class="stat-icon">
            <i class="fas fa-user-injured"></i>
        </div>
        <div class="stat-info">
            <h3>Pacientes en Seguimiento</h3>
            <p class="stat-value">{{ $patientsCount }}</p>
            <p class="stat-change {{ $patientsChange >= 0 ? 'positive' : 'negative' }}">{{ $patientsChange >= 0 ? '+' : '' }}{{ $patientsChange }}% <span>vs mes anterior</span></p>
        </div>
    </div>
    
    <div class="stat-card treatments">
        <div class="stat-icon">
            <i class="fas fa-procedures"></i>
        </div>
        <div class="stat-info">
            <h3>Tratamientos Activos</h3>
            <p class="stat-value">{{ $treatmentsCount }}</p>
            <p class="stat-change {{ $treatmentsChange >= 0 ? 'positive' : 'negative' }}">{{ $treatmentsChange >= 0 ? '+' : '' }}{{ $treatmentsChange }}% <span>este mes</span></p>
        </div>
    </div>
</div>
