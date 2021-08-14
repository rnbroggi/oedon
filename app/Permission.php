<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends SpatiePermission implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
}
