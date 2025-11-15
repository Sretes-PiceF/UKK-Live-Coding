<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    // Definisikan nama tabel secara eksplisit
    protected $table = 'pengaturan';

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'key',
        'value'
    ];

    // Karena kolom 'value' akan menyimpan nilai biaya admin (angka),
    // kita bisa menggunakan casts untuk otomatis mengubahnya menjadi integer saat diambil.
    protected $casts = [
        'value' => 'integer',
    ];
}
