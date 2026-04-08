@extends('layouts.panel')
@section('title', 'Crear País')
@section('content')
<main id="content" role="main" class="main">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Mantenimiento</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('maintenance.countries.index') }}">Países</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Crear país</h1>
                </div>
                <div class="col-auto">
                    <a href="{{ route('maintenance.countries.index') }}" class="btn btn-primary">
                        <i class="bi-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
        </div>
@if ($errors->any())
                <div class="alert alert-danger text-white mb-4" role="alert">
                    <strong>¡Ups! Ha ocurrido un problema:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



        <div class="row justify-content-lg-center">
            <div class="col-lg-9">

            <form action="{{ route('maintenance.countries.store') }}" method="POST" id="countryForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="card card-lg mb-3 mb-lg-5">
                        <div class="card-header">
                            <h4 class="card-header-title">Detalles del país</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="nameLabel" class="form-label">Nombre del país</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend input-group-text">
                                        <i class="bi-briefcase"></i>
                                    </div>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="nameLabel" placeholder="Ej. Guatemala" value="{{ old('name') }}" required minlength="5" autocomplete="off">
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('maintenance.countries.index') }}" class="btn btn-white">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('#countryForm');

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                const preloader = document.getElementById('loading-spinner');
                if (preloader) {
                    preloader.style.setProperty('display', 'none', 'important');
                    preloader.style.opacity = '0';
                }

            }
            form.classList.add('was-validated');
        }, false);

    });
</script>
@endpush
