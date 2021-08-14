<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veterinaria extends Model
{
    use SoftDeletes;

    protected $table = 'veterinarias';
    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany(User::class, 'Veterinaria_id', 'id');
    }
}
