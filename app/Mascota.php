<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
}
