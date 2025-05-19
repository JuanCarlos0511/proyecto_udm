<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Appointment extends Model
{
    use HasFactory;

    /**
     * Constantes para los estados de las citas
     */
    const STATUS_REQUESTED = 'Solicitado';
    const STATUS_SCHEDULED = 'Agendado';
    const STATUS_COMPLETED = 'Completado';
    const STATUS_CANCELLED = 'Cancelado';

    /**
     * Lista de estados válidos para las citas
     */
    public static $validStatuses = [
        self::STATUS_REQUESTED,
        self::STATUS_SCHEDULED,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'user_id',
        'subject',
        'status',
        'modality',
        'price',
        'diagnosis',
        'notes',
        'referred_by',
        'contact_name',
        'contact_relationship',
        'appointment_group_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',  // Cambiado a datetime para preservar la hora
        'price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the appointment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Esta relación ya no se usa, pues no existe la columna doctor_id en la tabla.
     * En su lugar, se crean citas separadas para pacientes y doctores y se relacionan
     * a través del appointment_group_id.
     */
    // Comentado para evitar errores porque no existe la columna doctor_id
    // public function doctor()
    // {
    //     return $this->belongsTo(User::class, 'doctor_id');
    // }
    
    /**
     * Obtener el grupo de citas al que pertenece esta cita.
     */
    public function appointmentGroup()
    {
        return $this->belongsTo(AppointmentGroup::class);
    }
    
    /**
     * Obtener las citas relacionadas que pertenecen al mismo grupo de citas.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function relatedAppointments()
    {
        return $this->hasMany(Appointment::class, 'appointment_group_id', 'appointment_group_id')
            ->where('id', '!=', $this->id); // Excluir la propia cita
    }
    
    /**
     * Obtener la cita relacionada (para compatibilidad con código existente).
     * Este método no debe usarse con eager loading (with()).
     * 
     * @return Appointment|null
     */
    public function getRelatedAppointment()
    {
        if (!$this->appointment_group_id) {
            return null;
        }
        
        // Si la relación user no está cargada, cargarla
        if (!$this->relationLoaded('user')) {
            $this->load('user');
        }
        
        // Determinar el rol objetivo basado en el rol del usuario actual
        $currentUserRole = $this->user->role;
        $targetRole = ($currentUserRole === 'paciente') ? 'doctor' : 'paciente';
        
        return Appointment::where('appointment_group_id', $this->appointment_group_id)
            ->where('id', '!=', $this->id)
            ->whereHas('user', function($query) use ($targetRole) {
                $query->where('role', $targetRole);
            })
            ->first();
    }

    /**
     * Check if the appointment can be accepted.
     *
     * @return bool
     */
    public function canBeAccepted()
    {
        return $this->status === self::STATUS_REQUESTED;
    }

    /**
     * Check if the appointment can be cancelled.
     *
     * @return bool
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [
            self::STATUS_REQUESTED,
            self::STATUS_SCHEDULED
        ]);
    }

    /**
     * Accept the appointment.
     *
     * @return bool
     */
    public function accept()
    {
        if (!$this->canBeAccepted()) {
            return false;
        }

        $this->status = self::STATUS_SCHEDULED;
        return $this->save();
    }

    /**
     * Cancel the appointment.
     *
     * @return bool
     */
    public function cancel()
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->status = self::STATUS_CANCELLED;
        return $this->save();
    }
}