<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    protected $fillable = [
        'filiere_id', 'cin', 'first_name',
        'last_name', 'image_profile', 'group', 'graduation_year'
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}