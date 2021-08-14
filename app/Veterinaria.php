<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veterinaria extends Model
{
    use SoftDeletes;

    protected $table = 'veterinarias';
    protected $guarded = ['id'];

    // Delete en cascada para usuarios de la veterinaria
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($veterinaria) {
            $veterinaria->users()->delete();
        });
    }

    public function users()
    {
        return $this->hasMany(User::class, 'Veterinaria_id', 'id');
    }
}
