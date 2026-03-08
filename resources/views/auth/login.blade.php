@extends('layouts.form')

@section('content')
    <form method="POST" action="{{ route('login') }}" role="form" class="text-start" novalidate autocomplete="off">
        @csrf
        <div class="text-center">
            <div class="mb-5">
<<<<<<< Updated upstream
            <div class="text-center mb-4 d-lg-none">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logotipo.svg') }}" alt="Logotipo" tyle="min-width: 20rem; max-width: 20rem;" data-hs-theme-appearance="default">
                    <img src="{{ asset('img/logotipo-white.svg') }}" alt="Logotipo" style="min-width: 20rem; max-width: 20rem;" data-hs-theme-appearance="dark">
                </a>
            </div>
=======
                <div class="text-center mb-4 d-lg-none">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('img/logotipo.svg') }}" alt="Logotipo" style="min-width: 20rem; max-width: 20rem;" data-hs-theme-appearance="default">
                        <img src="{{ asset('img/logotipo-white.svg') }}" alt="Logotipo" style="min-width: 20rem; max-width: 20rem;" data-hs-theme-appearance="dark">
                    </a>
                </div>
>>>>>>> Stashed changes
                <h1 class="display-5">Iniciar Sesión</h1>
                <p>Ingrese sus credenciales para acceder a su cuenta.</p>
                <span class="divider-center text-muted mb-4"></span>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label" for="signinSrEmail">Correo electrónico</label>
<<<<<<< Updated upstream
            <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail" tabindex="1" placeholder="Ingrese su correo electrónico" required>
=======
            <input type="email" autocomplete="off" class="form-control form-control-lg" name="email" id="signinSrEmail" placeholder="Ingrese su correo electrónico" aria-label="Ingrese su correo electrónico" required>
>>>>>>> Stashed changes
            <span class="invalid-feedback">Por favor, ingrese un correo electrónico válido.</span>
        </div>

        <div class="mb-4">
            <label class="form-label w-100" for="signupSrPassword"
                <span class="d-flex justify-content-between align-items-center">
                    <span>Contraseña</span>
                </span>
            </label>

            <div class="input-group input-group-merge" data-hs-validation-validate-class>
<<<<<<< Updated upstream
                <input type="password" class="js-toggle-password form-control form-control-lg" name="password" id="signupSrPassword" placeholder="Ingrese su contraseña" required minlength="8" data-hs-toggle-password-options='{ "target": "#changePassTarget", "defaultClass": "bi-eye-slash", "showClass": "bi-eye", "classChangeTarget": "#changePassIcon"}'>
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" value="" id="termsCheckbox">
            <label class="form-check-label" for="termsCheckbox">Recuérdame</label>
        </div>
        
=======
                <input type="password" autocomplete="off" class="js-toggle-password form-control form-control-lg" name="password" id="signupSrPassword" placeholder="Ingrese su contraseña" aria-label="Ingrese su contraseña" required minlength="8" data-hs-toggle-password-options='{"target": "#changePassTarget","defaultClass": "bi-eye-slash","showClass": "bi-eye","classChangeTarget": "#changePassIcon"}'>
                <a id="changePassTarget" class="input-group-append input-group-text" href="javascript:;">
                    <i id="changePassIcon" class="bi-eye"></i>
                </a>
            </div>
        </div>

>>>>>>> Stashed changes
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>
        </div>
    </form>
@endsection

@push('styles')
    <style>
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none;
    }

    input[type="password"]::-webkit-credentials-auto-fill-button {
        display: none !important;
        visibility: hidden;
        pointer-events: none;
    }
    </style>
@endpush

@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        HSBsValidation.init('.js-validate')

        new HSTogglePassword('.js-toggle-password')

        HSCore.components.HSTomSelect.init('.js-select')

    })
    </script>
@endsection