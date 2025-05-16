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
        'referred_by',
        'contact_name',
        'contact_relationship'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
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
     * Get the doctor associated with the appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
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