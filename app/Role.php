<?php

namespace App;

use Spatie\Permission\Models\Role as SpatieRole;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Role extends SpatieRole implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $guard_name = 'web';

    public function scopeByRole($query)
    {
        $user = Auth::user();

        if ($user->hasRole('administrativo'))
            return $query->whereIn('name', ['administrativo', 'veterinario', 'cliente']);

        if ($user->hasRole('veterinario'))
            return $query->where('name', 'cliente');
    }
}
