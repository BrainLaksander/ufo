<?php

namespace App\Http\Controllers;

class PublicController extends Controller
{
    public function home()
    {
        return view('mahasiswa.beranda');
    }

    public function organisasi()
    {
        return view('mahasiswa.organisasi');
    }

    public function kegiatan()
    {
        return view('mahasiswa.kegiatan');
    }
}
