<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;

class Admin extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use HasFactory, Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = true;
    protected $fillable = [
        'id_admin',
        'username',
        'password',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
