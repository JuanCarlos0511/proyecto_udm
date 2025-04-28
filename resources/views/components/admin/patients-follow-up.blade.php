<div class="patient-follow-up">
    <div class="follow-up-header">
        <div class="follow-up-title">Pacientes en Seguimiento</div>
        <a href="{{ url('admin/tablero/seguimiento-todos') }}" class="view-all">Ver todos</a>
    </div>
    <div class="patient-list">
        @forelse ($patients as $patient)
        <div class="patient-item">
            <div class="patient-avatar">{{ $patient->initials }}</div>
            <div class="patient-info">
                <div class="patient-name">{{ $patient->name }}</div>
                <div class="patient-details">Última visita: {{ $patient->last_visit_date }} • Tratamiento: {{ $patient->treatment }}</div>
            </div>
            <span class="patient-status status-{{ $patient->status }}">{{ $patient->status_text }}</span>
        </div>
        @empty
        <div class="patient-item">
            <p class="text-center">No hay pacientes en seguimiento</p>
        </div>
        @endforelse
    </div>
</div>
