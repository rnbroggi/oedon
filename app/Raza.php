<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Raza extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'razas';
    protected $guarded = ['id'];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }
}
