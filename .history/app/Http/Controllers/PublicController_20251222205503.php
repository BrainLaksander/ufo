<?php

namespace App\Http\Controllers;

class PublicController extends Controller
{
    public function home()
    {
        return view('pages.public.home');
    }

    public function organisasi()
    {
        return view('pages.public.organisasi');
    }

    public function kegiatan()
    {
        return view('pages.public.kegiatan');
    }
}
