<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory;
    
    protected $fillable = [
        'name',
        'specialty',
        'phone',
        'email',
        'status'
    ];
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}