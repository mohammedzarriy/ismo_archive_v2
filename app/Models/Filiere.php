<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    protected $fillable = ['secteur_id', 'code_filiere', 'nom_filiere', 'niveau'];

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function trainees()
    {
        return $this->hasMany(Trainee::class);
    }
}