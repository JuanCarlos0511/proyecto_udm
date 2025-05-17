<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'follow_ups';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'follow_up_group_id',
        'user_id',
        'notes',
        'status',
        'start_date',
        'end_date',
    ];
    
    /**
     * Indicar que la tabla no tiene una clave primaria autoincremental
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * La clave primaria compuesta del modelo.
     *
     * @var array<int, string>
     */
    protected $primaryKey = ['follow_up_group_id', 'user_id'];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Obtiene el usuario asociado al seguimiento.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Obtiene otros usuarios en el mismo grupo de seguimiento.
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function followUpGroupMembers()
    {
        return FollowUp::where('follow_up_group_id', $this->follow_up_group_id)
            ->where('user_id', '!=', $this->user_id)
            ->with('user');
    }

    /**
     * Scope para filtrar seguimientos activos.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para filtrar por usuario (doctor o paciente).
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    /**
     * Scope para filtrar por grupo de seguimiento.
     */
    public function scopeByGroup($query, $groupId)
    {
        return $query->where('follow_up_group_id', $groupId);
    }
    
    /**
     * Scope para filtrar seguimientos donde el usuario tiene rol específico.
     */
    public function scopeByUserRole($query, $role)
    {
        return $query->whereHas('user', function($q) use ($role) {
            $q->where('role', $role);
        });
    }
}
