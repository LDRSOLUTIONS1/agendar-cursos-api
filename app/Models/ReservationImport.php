<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationImport extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reservation_imports';

    protected $fillable = [
        'reservation_id',
        'file_name',
        'file_path',
        'total_participants',
        'user_id',
        'status',
    ];

    protected $casts = [
        'total_participants' => 'integer',
        'status' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    // Relación con reservación
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    // Usuario que realizó la importación
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Participantes importados
    public function participants()
    {
        return $this->hasMany(ReservationParticipant::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeProcessed($query)
    {
        return $query->where('status', 1);
    }

    public function scopeWithErrors($query)
    {
        return $query->where('status', 2);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            1 => 'Procesado',
            2 => 'Error',
            default => 'Desconocido',
        };
    }
}
