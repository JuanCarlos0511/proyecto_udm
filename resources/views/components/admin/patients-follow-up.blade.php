<div class="dashboard-card patients-card">
    <div class="card-header">
        <h2>Pacientes en Seguimiento</h2>
        <a href="{{ url('admin/tablero/seguimiento-todos') }}" class="view-all">Ver todos</a>
    </div>
    <div class="card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Paciente</th>
                    <th>Tratamiento</th>
                    <th>Próxima Cita</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($patients as $patient)
                <tr>
                    <td>{{ $patient->doctor }}</td>
                    <td>{{ $patient->patient }}</td>
                    <td>{{ $patient->treatment }}</td>
                    <td>{{ $patient->next_appointment }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No hay pacientes en seguimiento</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
