<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    protected $fillable = ['nom_secteur'];

    public function filieres()
    {
        return $this->hasMany(Filiere::class);
    }
}