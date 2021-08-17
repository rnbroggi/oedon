<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable implements Auditable, HasMedia
{
    use Notifiable, HasRoles;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasMediaTrait;
    use Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'telefono', 'password', 'veterinaria_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canImpersonate()
    {
        return $this->hasRole('superadmin');
    }

    public function canBeImpersonated()
    {
        return !$this->hasRole('superadmin');
    }

    public function veterinaria()
    {
        return $this->belongsTo(Veterinaria::class, 'veterinaria_id');
    }

    public function scopeByVeterinaria($query)
    {
        $logged_user = Auth::user();
        if($logged_user->hasRole('superadmin')) return;

        return $query->where('veterinaria_id', $logged_user->veterinaria_id);
    }
}
