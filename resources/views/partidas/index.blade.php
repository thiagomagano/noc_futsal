@extends('layouts.app')

@section('title', 'Partidas - N.O.C.')

@section('breadcrumb')
    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li>Partidas</li>
@endsection

@section('header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-base-content">Partidas</h1>
            <p class="text-gray-600 mt-1">Gerencie as partidas do time!</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('partidas.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nova Partida
            </a>
        </div>
    </div>
@endsection

@section('content')

    <main>
        <h1 class="h1"> Lista de Partidas </h1>

    </main>
@endsection
