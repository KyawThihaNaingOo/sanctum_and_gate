<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    private const ROLES = [
        0 => 'user',
        11 => 'editor',
        22 => 'admin'
    ];
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at', 'created_at',
    ];

    private function getRoleID($roleName)
    {
        switch ($roleName) {
            case "admin":
                return 22;
                break;
            case "editor":
                return 11;
                break;
            case "user":
                return 0;
                break;
        }
    }

    public function isAdmin()
    {
        return $this->role_id === 22;
    }

    public function isEditor()
    {
        return $this->role_id === 11;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
}
