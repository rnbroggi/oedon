<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Visita extends Model implements Auditable, HasMedia
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasMediaTrait;
    
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

    public function scopeByVeterinaria($query)
    {
        $logged_user = Auth::user();
        if ($logged_user->hasRole('superadmin')) return;

        return $query->where('veterinaria_id', $logged_user->veterinaria_id);
    }

}
