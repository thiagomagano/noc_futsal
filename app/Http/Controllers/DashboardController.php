<?php

namespace App\Http\Controllers;

use App\Models\Atleta;
use App\Models\Partida;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {


        $ultimosAtletas = Atleta::latest()
            ->limit(5)
            ->get();

        $ultimasPartidas = Partida::latest()
            ->limit(5)
            ->get();


        return view('dashboard', compact(

            'ultimosAtletas',
            'ultimasPartidas',
        ));
    }
}
