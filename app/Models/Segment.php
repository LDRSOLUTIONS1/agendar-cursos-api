<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Segment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'estado'
    ];

    public function models()
    {
        return $this->hasMany(Models::class);
    }
}
