<div class="dashboard-card appointments-card">
    <div class="card-header">
        <h2>Citas Próximas</h2>
        <a href="{{ url('admin/tablero/citas-todas') }}" class="view-all">Ver todas</a>
    </div>
    <div class="card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Tratamiento</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->formatted_date }}</td>
                    <td>{{ $appointment->patient_name }}</td>
                    <td>{{ $appointment->treatment }}</td>
                    <td>{{ $appointment->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No hay citas próximas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
