<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{

    public function Tagihan()
    {
        // Ambil semua tagihan (tanpa filter pelanggan)
        $tagihan = Tagihan::all();

        return view('pelanggan.tagihan', compact('tagihan'));
    }

    public function Total()
    {
        return view('Pelanggan.total');
    }
}
