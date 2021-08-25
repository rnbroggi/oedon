<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Mascota extends Model implements Auditable, HasMedia
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasMediaTrait;

    protected $table = 'mascotas';
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'fecha_nacimiento'];

    // Delete en cascada de visitas de la mascota
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($mascota) {
            $mascota->visitas()->delete();
        });
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('profile')
            ->width(350)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('foto');
    }

    public function getEdadAttribute()
    {
        if ($this->fecha_nacimiento) {
            $period = date_diff(date_create($this->fecha_nacimiento), date_create('now'))->y;
            $text = $period < 2 ? 'año' : 'años';

            if ($period < 1) {
                $period = date_diff(date_create($this->fecha_nacimiento), date_create('now'))->m;
                $text = $period < 2 ? 'mes' : 'meses';
            }

            return "$period $text";
        }
    }

    public function raza()
    {
        return $this->belongsTo(Raza::class, 'raza_id');
    }

    public function sexo()
    {
        return $this->belongsTo(Sexo::class, 'sexo_id');
    }

    public function cliente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'mascota_id', 'id')->with('veterinario:id,name')->orderBy('id', 'DESC');
    }

    public function scopeByVeterinaria($query, $mascota_id)
    {
        if($mascota_id)
            $query = $query->where('id', $mascota_id);

        $logged_user = Auth::user();
        if ($logged_user->hasRole('superadmin')) return;

        $query = $query->whereHas('cliente', function ($q) use ($logged_user) {
            $q->where('veterinaria_id', $logged_user->veterinaria_id);
        });

        return $query;
    }
}
