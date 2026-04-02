@extends('layouts.panel')
@section('title', 'Inicio')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="row justify-content-sm-center text-center py-10">
                <div class="col-sm-7 col-md-5">
                    <img class="img-fluid mb-5" src="{{ asset('dist/img/Logotipo.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="img-fluid mb-5" src="{{ asset('dist/img/Logotipo-white.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <h1>Bienvenido al sistema de Registro y Control de Pacientes</h1>
                    <p>Potenciando el cuidado de la salud a través de la tecnología. Todo el control clínico en tus manos para que tu única prioridad sea el bienestar del paciente.</p>
                </div>
            </div>
        </div>
    </main>
@endsection