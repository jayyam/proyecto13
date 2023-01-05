<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    //
    protected $fillable = ['title']; //para permitir asignacion masiva de datos a la tabla

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
