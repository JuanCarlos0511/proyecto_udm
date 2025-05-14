<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'role',
        'email',
        'google_id',
        'avatar',
        'password',
        'adress',
        'status',
        'phoneNumber',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'age' => 'integer',
    ];

    /**
     * Get the appointments for the user.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    
    /**
     * Get the bills for the user.
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
    
    /**
     * Obtener los seguimientos donde el usuario es doctor.
     */
    public function doctorFollowUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'doctor_id');
    }
    
    /**
     * Obtener los seguimientos donde el usuario es paciente.
     */
    public function patientFollowUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'patient_id');
    }
    
    /**
     * Obtener los doctores que siguen a este paciente.
     */
    public function followingDoctors()
    {
        return User::whereIn('id', $this->patientFollowUps()->where('status', 'active')->pluck('doctor_id'));
    }
    
    /**
     * Obtener los pacientes que este doctor sigue.
     */
    public function followedPatients()
    {
        return User::whereIn('id', $this->doctorFollowUps()->where('status', 'active')->pluck('patient_id'));
    }
}
