<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'trainee_id', 'type', 'level_year',
        'status', 'reference_number'
    ];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}