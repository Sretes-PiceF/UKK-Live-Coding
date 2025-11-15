<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;

class Pelanggan extends Model implements AuthenticatableContract
{
    use Authenticatable;

    use HasFactory, Notifiable;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    public $timestamps = true;
    protected $fillable = [
        'id_pelanggan',
        'nama_pelanggan',
        'alamat',
        'no_kwh',
        'jumlah_meter',
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

    public function totalTagihan()
    {
        return $this->hasMany(TotalTagihan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
