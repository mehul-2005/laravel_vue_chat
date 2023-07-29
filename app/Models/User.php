<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'online_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = ['roleslist', 'permissionslist'];

    public function roles()
    {
        //    return $this->hasOneThrough(Role::class, Role_user::class,'role_id','id');
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
    }
    public function getRolesListAttribute()
    {
        return $this->roles->pluck('role')->implode(',');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_users', 'user_id', 'permission_id');
    }
    public function getPermissionsListAttribute()
    {
        return $this->permissions->pluck('permission')->implode(',');
    }

    public function getOnlineStatusAttribute($value)
    {
        if ($value === 'online') {
            return 'Online';
        } else {
            return date('d-m-Y') === date('d-m-Y', strtotime($value)) ? 'Last seen today ' . date('h:i a', strtotime($value)) : date('d-m-Y h:i a', strtotime($value));
        }
    }
}
