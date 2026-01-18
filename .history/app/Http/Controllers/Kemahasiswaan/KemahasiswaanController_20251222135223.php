<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Http\Controllers\Controller;

class KemahasiswaanController extends Controller
{
    public function index()
    {
        return view('dashboard.kemahasiswaan');
    }
}
