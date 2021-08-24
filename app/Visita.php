<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $table = 'visitas';
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'fecha'];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }

    public function veterinaria()
    {
        return $this->belongsTo(Veterinaria::class, 'veterinaria_id');
    }

    public function veterinario()
    {
        return $this->belongsTo(User::class, 'user_veterinario_id');
    }
}
