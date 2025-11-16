<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';
    public $timestamps = true;
    protected $keyType = 'string';

    protected $fillable = [
        'id_tagihan',
        'bulan',
        'tahun',
        'jumlah_meter',
        'tarif_per_kwh',
    ];

    public function totalTagihan()
    {
        return $this->hasOne(TotalTagihan::class, 'id_tagihan', 'id_tagihan');
    }
}
