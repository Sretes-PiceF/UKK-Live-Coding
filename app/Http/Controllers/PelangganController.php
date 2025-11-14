<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PelangganController extends Controller
{

    public function Tagihan()
    {
        return view('Pelanggan.tagihan');
    }

    public function Total()
    {
        return view('Pelanggan.total');
    }
}
