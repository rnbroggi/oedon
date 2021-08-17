<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Mascota extends Model implements Auditable, HasMedia
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasMediaTrait;

    protected $table = 'mascotas';
    protected $guarded = ['id'];

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
        return $this->belongsTo(User::class, 'cliente');
    }
}
