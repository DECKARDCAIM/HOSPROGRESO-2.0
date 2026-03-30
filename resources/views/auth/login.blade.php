@extends('layouts.form')
@section('title', 'Inicio de Sesión')

@section('content')
    <main id="content" role="main" class="main pt-0">
        <div class="container-fluid px-3">
            <div class="row">

                @include('includes.form.content')

                <div class="col-lg-6 d-flex justify-content-center align-items-center min-vh-lg-100">
                    <div class="w-100 content-space-t-4 content-space-t-lg-2 content-space-b-1" style="max-width: 25rem;">

                        <div id="login-form-wrapper">
                            @if ($errors->any())
                                <div class="alert alert-danger text-white" role="alert">
                                    <strong>¡Ups! Ha ocurrido un problema:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" role="form" class="text-start" novalidate
                                autocomplete="off"
                                onsubmit="if(window.showManualLoader) window.showManualLoader('Iniciando sesión...');">
                                @csrf
                                <div class="text-center">
                                    <div class="mb-5">
                                        <div class="text-center mb-4 d-lg-none">
                                            <a href="{{ route('home') }}">
                                                <img src="{{ asset('img/Logotipo.svg') }}" alt="Logotipo"
                                                    style="min-width: 20rem; max-width: 20rem;"
                                                    data-hs-theme-appearance="default">
                                                <img src="{{ asset('img/Logotipo-white.svg') }}" alt="Logotipo"
                                                    style="min-width: 20rem; max-width: 20rem;"
                                                    data-hs-theme-appearance="dark">
                                            </a>
                                        </div>
                                        <h1 class="display-5">Iniciar Sesión</h1>
                                        <p>Ingrese sus credenciales para acceder a su cuenta.</p>
                                        <span class="divider-center text-muted mb-4"></span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="signinSrEmail">Correo electrónico</label>
                                    <input type="email" autocomplete="off" class="form-control form-control-lg"
                                        name="email" id="signinSrEmail" placeholder="Ingrese su correo electrónico"
                                        aria-label="Ingrese su correo electrónico" required>
                                    <span class="invalid-feedback">Por favor, ingrese un correo electrónico válido.</span>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label w-100" for="signupSrPassword">
                                        <span class="d-flex justify-content-between align-items-center">
                                            <span>Contraseña</span>
                                        </span>
                                    </label>
                                    <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                        <input type="password" class="form-control form-control-lg" name="password"
                                            id="signupSrPassword" placeholder="Ingrese su contraseña"
                                            aria-label="Ingrese su contraseña" required minlength="8"
                                            autocomplete="current-password">
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
