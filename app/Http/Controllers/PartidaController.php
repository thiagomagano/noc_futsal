<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartidaController extends Controller
{
    public function index()
    {
        return view('partidas.index');
    }

    public function create()
    {
        return view('partidas.create');
    }
}
