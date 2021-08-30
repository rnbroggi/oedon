<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Veterinaria extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'veterinarias';
    protected $guarded = ['id'];

    // Delete en cascada para usuarios de la veterinaria
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($veterinaria) {
            foreach ($veterinaria->users as $user) {
                $user->active = false;
                $user->save();

                foreach ($user->mascotas as $mascota) {
                    $mascota->visitas()->delete();
                    $mascota->delete();
                }
            }
        });
    }

    public function users()
    {
        return $this->hasMany(User::class, 'veterinaria_id', 'id');
    }
}
