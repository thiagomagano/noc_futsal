@extends('layouts.app')

@section('title', 'Nova Partida - Futsal Manager')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('partidas.index') }}">Partidas</a></li>
    <li>Nova Partida</li>
@endsection

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-base-content">Nova Partida</h1>
            <p class="text-gray-600 mt-1">Cadastre uma nova partida no sistema</p>
        </div>
    </div>
@endsection

@section('content')

    <main>
        <h1 class="h1"> Form para nova partida </h1>

    </main>
@endsection
