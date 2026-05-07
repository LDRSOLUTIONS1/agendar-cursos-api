<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reservation_participants';

    protected $fillable = [
        'reservation_id',
        'reservation_import_id',
        'full_name',
        'email',
        'phone',
        'company',
        'position',
        'evaluation_status',
        'attendance',
        'grade',
        'certificate_generated',
        'notes',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'evaluation_status' => 'integer',
        'attendance' => 'boolean',
        'grade' => 'decimal:2',
        'certificate_generated' => 'boolean',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function reservationImport()
    {
        return $this->belongsTo(ReservationImport::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('evaluation_status', 0);
    }

    public function scopeSent($query)
    {
        return $query->where('evaluation_status', 1);
    }

    public function scopeAnswered($query)
    {
        return $query->where('evaluation_status', 2);
    }

    public function scopeWithAttendance($query)
    {
        return $query->where('attendance', true);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getEvaluationStatusTextAttribute()
    {
        return match ($this->evaluation_status) {
            0 => 'Pendiente',
            1 => 'Enviada',
            2 => 'Respondida',
            default => 'Desconocido',
        };
    }
}
