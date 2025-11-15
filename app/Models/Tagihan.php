<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';
    public $timestamps = true;

    protected $fillable = [
        'bulan',
        'tahun',
        'jumlah_meter',
        'tarif_per_kwh',
    ];
}
