@extends('layouts.panel')

@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="row justify-content-sm-center text-center py-10">
                <div class="col-sm-7 col-md-5">
                    <img class="img-fluid mb-5" src="{{ asset('img/logotipo.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="img-fluid mb-5" src="{{ asset('img/logotipo-white.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
<<<<<<< Updated upstream
                    
                    <h1 class="mb-3">Bienvenido, {{ Auth::user()->name }}</h1>

                    <p class="text-muted fs-5"> Al sistema {{ config('app.name') }} - Sistema de Registro de Pacientes.</p>

                    <p class="mt-3">Este sistema te permitirá registrar y dar seguimiento a la atención de los pacientes, desde la valoración inicial por enfermería, la atención médica, hasta la hospitalización y control del paciente.</p>
=======
                    <h1>Bienvenido al sistema de Gestión de Pacientes</h1>
                    <p>Este sistema te permitirá gestionar los pacientes, Expedientes Clínicos, citas médicas y mucho más</p>
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </main>
@endsection