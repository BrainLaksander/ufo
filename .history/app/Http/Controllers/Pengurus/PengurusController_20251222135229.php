<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;

class PengurusController extends Controller
{
    public function index()
    {
        return view('dashboard.pengurus');
    }
}
