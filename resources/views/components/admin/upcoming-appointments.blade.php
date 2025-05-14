<div class="dashboard-card appointments-card">
    <div class="card-header">
        <h2>Citas Próximas</h2>
        <a href="{{ route('admin.appointments.index') }}" class="view-all">Ver todas</a>
    </div>
    <div class="card-body">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Fecha</th>
                    <th>Doctor</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->patient_name }}</td>
                    <td>{{ $appointment->formatted_date }}</td>
                    <td>{{ $appointment->doctor_name }}</td>
                    <td><span class="status-badge {{ $appointment->status_class }}">{{ $appointment->status_text }}</span></td>
                    <td>
                        <div class="actions">
                            <a href="{{ url('admin/appointments/' . $appointment->id) }}" class="action-btn" title="Ver"><i class="fas fa-eye"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No hay citas próximas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
