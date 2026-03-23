@extends('layouts.panel')
@section('title', 'Crear Departamento')

@section('content')
<main id="content" role="main" class="main">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><span>Mantenimiento</span></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('departments.index') }}">Departamentos</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Crear departamento</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row justify-content-lg-center">
            <div class="col-lg-9">
                <!-- Card -->
                <form action="{{ route('departments.store') }}" method="POST" id="departmentForm" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="card card-lg mb-3 mb-lg-5">
                        <!-- Header -->
                        <div class="card-header border-bottom">
                            <h4 class="card-header-title">Detalles del departamento</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Form Group -->
                            <div class="mb-4">
                                <label for="countrySelect" class="form-label">País</label>
                                <select class="form-select @error('country_id') is-invalid @enderror" 
                                        name="country_id" id="countrySelect" required>
                                    <option value="" selected disabled>Selecciona un país...</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="mb-4">
                                <label for="nameLabel" class="form-label">Nombre del departamento</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" id="nameLabel" placeholder="Ej. Guatemala" 
                                       value="{{ old('name') }}" required minlength="5" autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- End Form Group -->
                        </div>
                        <!-- End Body -->

                        <!-- Footer -->
                        <div class="card-footer d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('departments.index') }}" class="btn btn-white">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                        <!-- End Footer -->
                    </div>
                </form>
                <!-- End Card -->
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('#departmentForm');
        
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                const preloader = document.getElementById('loading-spinner');
                if (preloader) {
                    preloader.style.setProperty('display', 'none', 'important');
                    preloader.style.opacity = '0';
                }
                
                showToast('Atención', 'Por favor, completa los campos requeridos correctamente.', 'warning');
            }
            form.classList.add('was-validated');
        }, false);

        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast('Error de validación', '{{ $error }}', 'danger');
            @endforeach
        @endif

        function showToast(title, message, type = 'info') {
            const toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) return;

            const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
            
            let toastClass = 'bg-info text-white';
            let iconClass = 'bi-info-circle text-info';
            
            if (type === 'success') {
                toastClass = 'bg-success text-white';
                iconClass = 'bi-check-lg text-success';
            } else if (type === 'danger' || type === 'error') {
                toastClass = 'bg-danger text-white';
                iconClass = 'bi-exclamation-octagon text-danger';
            } else if (type === 'warning') {
                toastClass = 'bg-warning text-dark';
                iconClass = 'bi-exclamation-triangle text-warning';
            }

            const toastHTML = `
                <div id="${toastId}" class="toast custom-toast ${toastClass} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="custom-toast-icon-wrapper">
                        <div class="custom-toast-icon-bg">
                            <i class="${iconClass}"></i>
                        </div>
                    </div>
                    <div class="custom-toast-content">
                        <div class="custom-toast-title">${title}</div>
                        <div class="custom-toast-message">${message}</div>
                    </div>
                    <button type="button" class="custom-toast-close" data-bs-dismiss="toast" aria-label="Close">
                        <i class="bi-x-lg"></i>
                    </button>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            const toastEl = document.getElementById(toastId);
            const bsToast = new bootstrap.Toast(toastEl, { delay: 5000 });
            bsToast.show();
            
            toastEl.addEventListener('hidden.bs.toast', function () {
                toastEl.remove();
            });
        }
    });
</script>
@endpush
