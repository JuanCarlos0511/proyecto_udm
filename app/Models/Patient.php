<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;
    
    protected $fillable = [
        'name',
        'age',
        'phone',
        'address'
    ];
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}