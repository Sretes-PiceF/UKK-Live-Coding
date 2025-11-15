<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalTagihan extends Model
{
    protected $table = 'total_tagihan';
    protected $primaryKey = 'id_total_tagihan';
    public $timestamps = true;
    protected $keyType = 'string';

    protected $fillable = [
        'id_total_tagihan',
        'id_tagihan',
        'id_pelanggan',
        'tanggal_bayar',
        'biaya_admin',
        'total_bayar',
        'status_pembayaran'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'id_tagihan', 'id_tagihan');
    }
}
