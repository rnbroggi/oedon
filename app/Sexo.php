<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sexo extends Model
{
    protected $table = 'sexos';

    const MACHO       = 1;
    const HEMBRA      = 2;
    const INDEFINIDO  = 3;
    const DESCONOCIDO = 4;
}
