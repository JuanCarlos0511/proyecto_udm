<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentGroup extends Model
{
    use HasFactory;
    
    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];
    
    /**
     * Obtener todas las citas asociadas a este grupo.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
    
    /**
     * Obtener todas las citas de pacientes en este grupo.
     */
    public function patientAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class)
            ->whereHas('user', function($query) {
                $query->where('role', 'paciente');
            });
    }
    
    /**
     * Obtener todas las citas de doctores en este grupo.
     */
    public function doctorAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class)
            ->whereHas('user', function($query) {
                $query->where('role', 'doctor');
            });
    }
}
