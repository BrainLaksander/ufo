<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TentangController extends Controller
{
    /**
     * Display the about page.
     */
    public function index(Request $request)
    {
        $menuItems = null;

        return view('mahasiswa.tentang.index', compact('menuItems'));
    }
}
