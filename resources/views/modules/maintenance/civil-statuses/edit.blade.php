@extends('layouts.panel')
@section('title', 'Editar Estado Civil')
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
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('maintenance.civil-statuses.index') }}">Estados Civiles</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Editar estado civil</h1>
                </div>
                <div class="col-auto"><a href="{{ route('maintenance.civil-statuses.index') }}" class="btn btn-primary"><i class="bi-arrow-left"></i> Regresar</a></div>
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



        <div class="row justify-content-lg-center"><div class="col-lg-9">

            <form action="{{ route('maintenance.civil-statuses.update', $item->id) }}" method="POST" id="itemForm" class="needs-validation" novalidate>
                @csrf @method('PUT')
                <div class="card card-lg mb-3 mb-lg-5">
                    <div class="card-header"><h4 class="card-header-title">Detalles del estado civil</h4></div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="nameLabel" class="form-label">Nombre del estado civil</label>
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend input-group-text"><i class="bi-heart"></i></div>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="nameLabel" placeholder="Ej. Soltero/a" value="{{ old('name', $item->name) }}" required minlength="3" autocomplete="off">
                            </div>
                            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end align-items-center gap-3">
                        <a href="{{ route('maintenance.civil-statuses.index') }}" class="btn btn-white">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </div>
            </form>
        </div></div>
    </div>
</main>
@endsection
@push('scripts')
<script type="module">
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#itemForm');
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); const p = document.getElementById('loading-spinner'); if (p) { p.style.setProperty('display', 'none', 'important'); p.style.opacity = '0'; }  }
        form.classList.add('was-validated');
    }, false);
});
</script>
@endpush

