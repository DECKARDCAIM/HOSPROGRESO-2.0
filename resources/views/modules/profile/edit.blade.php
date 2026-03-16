@extends('layouts.panel')
@section('title', ' Editar Perfil')

@section('content')
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <div class="row justify-content-lg-center">
                <div class="col-lg-10">
                    <div class="profile-cover">
                        <div class="profile-cover-img-wrapper">

                            <img id="profileCoverImg" class="profile-cover-img"
                                src="{{ $user->banner_photo_path ? asset('storage/' . $user->banner_photo_path) : asset('img/1920x400/img2.jpg') }}"
                                data-src="{{ $user->banner_photo_path ? asset('storage/' . $user->banner_photo_path) : asset('img/1920x400/img2.jpg') }}"
                                alt="Image Description" onerror="this.onerror=null; retryImageLoad(this);">

                            <div class="profile-cover-content profile-cover-uploader p-3">
                                <input type="file" class="js-file-attach profile-cover-uploader-input"
                                    id="profileCoverUplaoder" name="banner_photo" form="profileForm"
                                    data-hs-file-attach-options='{
                    "textTarget": "#profileCoverImg",
                    "mode": "image",
                    "targetAttr": "src",
                    "allowTypes": [".png", ".jpeg", ".jpg"]
                }'>
                                <label class="profile-cover-uploader-label btn btn-sm btn-white" for="profileCoverUplaoder">
                                    <i class="bi-camera-fill"></i>
                                    <span class="d-none d-sm-inline-block ms-1">Upload header</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Header -->
                    <div class="text-center mb-5">
                        @php
                            $nombreCompleto =
                                trim($user->first_name . ' ' . trim($user->second_name . ' ' . $user->third_name)) .
                                ' ' .
                                trim(
                                    $user->first_last_name .
                                        ' ' .
                                        $user->second_last_name .
                                        ' ' .
                                        $user->married_last_name,
                                );
                            $iniciales = '';
                            if (!empty($user->first_name)) {
                                $iniciales .= strtoupper(substr($user->first_name, 0, 1));
                            }
                            if (!empty($user->first_last_name)) {
                                $iniciales .= strtoupper(substr($user->first_last_name, 0, 1));
                            }
                            if (empty($iniciales)) {
                                $iniciales = 'U';
                            }
                        @endphp

                        <label class="avatar avatar-xxl avatar-circle avatar-uploader profile-cover-avatar"
                            for="editAvatarUploaderModal" style="cursor: pointer; position: relative; border: none;">

                            @if ($user->profile_photo_path)
                                <img class="avatar-img" id="editAvatarImgModal"
                                    src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto de perfil">
                            @else
                                <span class="avatar-soft-primary" id="editAvatarInitials"
                                    style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                    <span class="avatar-initials">{{ $iniciales }}</span>
                                </span>
                                <img class="avatar-img d-none" id="editAvatarImgModal" src=""
                                    alt="Previsualización de foto"
                                    onload="document.getElementById('editAvatarInitials').style.display='none'; this.classList.remove('d-none');">
                            @endif

                            <input type="file" class="js-file-attach avatar-uploader-input" id="editAvatarUploaderModal"
                                name="profile_photo" form="profileForm"
                                data-hs-file-attach-options='{
                "textTarget": "#editAvatarImgModal",
                "mode": "image",
                "targetAttr": "src",
                "allowTypes": [".png", ".jpeg", ".jpg"]
            }'>

                            <span class="avatar-uploader-trigger">
                                <i class="bi-pencil-fill avatar-uploader-icon shadow-sm"></i>
                            </span>
                        </label>
                        <h1 class="page-header-title">{{ $nombreCompleto }} <i class="bi-patch-check-fill fs-2 text-primary"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Administrador"></i></h1>
                    </div>
                    <!-- End Profile Header -->

                    <div class="js-nav-scroller hs-nav-scroller-horizontal mb-5">
                        <span class="hs-nav-scroller-arrow-prev" style="display: none;">
                            <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                                <i class="bi-chevron-left"></i>
                            </a>
                        </span>

                        <span class="hs-nav-scroller-arrow-next" style="display: none;">
                            <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                                <i class="bi-chevron-right"></i>
                            </a>
                        </span>


                        <ul class="nav nav-tabs align-items-center">
                            <li class="nav-item">
                                <a class="nav-link active disabled" href="#">Editar perfil</a>
                            </li>

                            <li class="nav-item ms-auto">
                                <div class="d-flex gap-2">
                                    <a class="btn btn-white btn-sm" href="{{ route('profile.index') }}">
                                        <i class="bi-arrow-left me-1"></i> Regresar
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
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
                        <div class="col-lg-12">
                            <div class="card mb-3 mb-lg-5">
                                <div class="card-header card-header-content-between">
                                    <h4 class="card-header-title">Información personal</h4>
                                </div>
                                <div class="card-body">
                                    <form id="profileForm" method="POST" action="{{ route('profile.update') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label" for="first_name">Primer nombre</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name"
                                                    value="{{ old('first_name', $user->first_name) }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="second_name">Segundo
                                                    nombre</label>
                                                <input type="text" class="form-control" id="second_name"
                                                    name="second_name"
                                                    value="{{ old('second_name', $user->second_name) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="third_name">Tercer
                                                    nombre</label>
                                                <input type="text" class="form-control" id="third_name"
                                                    name="third_name" value="{{ old('third_name', $user->third_name) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="first_last_name">Primer
                                                    apellido</label>
                                                <input type="text" class="form-control" id="first_last_name"
                                                    name="first_last_name"
                                                    value="{{ old('first_last_name', $user->first_last_name) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="second_last_name">Segundo
                                                    apellido</label>
                                                <input type="text" class="form-control" id="second_last_name"
                                                    name="second_last_name"
                                                    value="{{ old('second_last_name', $user->second_last_name) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="married_last_name">Apellido
                                                    de
                                                    casada</label>
                                                <input type="text" class="form-control" id="married_last_name"
                                                    name="married_last_name"
                                                    value="{{ old('married_last_name', $user->married_last_name) }}">
                                            </div>
                                        </div>

                                        <hr class="my-5">
                                        <h5 class="mb-4">Información de contacto y demográfica</h5>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label" for="email">Correo
                                                    electrónico</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ old('email', $user->email) }}" readonly disabled>
                                                <small class="form-text text-muted">No puedes modificar el correo
                                                    electrónico de esta cuenta.</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="phone">Teléfono</label>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                    value="{{ old('phone', $user->phone) }}">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label" for="cui">DPI / CUI</label>
                                                <input type="text" class="form-control" id="cui" name="cui"
                                                    maxlength="13" value="{{ old('cui', $user->cui) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="nit">NIT</label>
                                                <input type="text" class="form-control" id="nit" name="nit"
                                                    value="{{ old('nit', $user->nit) }}">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-4">
                                                <label class="form-label" for="marital_status">Estado Civil</label>
                                                <select class="form-control" id="marital_status" name="marital_status">
                                                    <option value=""
                                                        {{ old('marital_status', $user->marital_status) == '' ? 'selected' : '' }}>
                                                        Seleccione</option>
                                                    <option value="soltero"
                                                        {{ old('marital_status', $user->marital_status) == 'soltero' ? 'selected' : '' }}>
                                                        Soltero/a</option>
                                                    <option value="casado"
                                                        {{ old('marital_status', $user->marital_status) == 'casado' ? 'selected' : '' }}>
                                                        Casado/a</option>
                                                    <option value="divorciado"
                                                        {{ old('marital_status', $user->marital_status) == 'divorciado' ? 'selected' : '' }}>
                                                        Divorciado/a</option>
                                                    <option value="viudo"
                                                        {{ old('marital_status', $user->marital_status) == 'viudo' ? 'selected' : '' }}>
                                                        Viudo/a</option>
                                                    <option value="union_libre"
                                                        {{ old('marital_status', $user->marital_status) == 'union_libre' ? 'selected' : '' }}>
                                                        Unión Libre</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label" for="gender">Género</label>
                                                <select class="form-control" id="gender" name="gender">
                                                    <option value=""
                                                        {{ old('gender', $user->gender) == '' ? 'selected' : '' }}>
                                                        Seleccione</option>
                                                    <option value="masculino"
                                                        {{ old('gender', $user->gender) == 'masculino' ? 'selected' : '' }}>
                                                        Masculino</option>
                                                    <option value="femenino"
                                                        {{ old('gender', $user->gender) == 'femenino' ? 'selected' : '' }}>
                                                        Femenino</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label" for="birth_date">Fecha de
                                                    nacimiento</label>
                                                <input type="date" class="form-control" id="birth_date"
                                                    name="birth_date" value="{{ old('birth_date', $user->birth_date) }}">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <label class="form-label" for="address">Dirección de residencia</label>
                                                <input type="text" class="form-control" id="address" name="address"
                                                    value="{{ old('address', $user->address) }}">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 p-4 pt-1">
                                            <a href="{{ route('profile.index') }}" class="btn btn-white">Cancelar</a>
                                            <button type="submit" class="btn btn-primary">Guardar
                                                cambios de perfil</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Seccion de Seguridad y Contraseña -->
                            <div class="card mb-3 mb-lg-5">
                                <div class="card-header card-header-content-between">
                                    <h4 class="card-header-title">Seguridad y acceso</h4>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Para actualizar tu contraseña, verifica tu contraseña actual y
                                        luego escribe la nueva.</p>

                                    <form id="passwordForm" method="POST" action="{{ route('profile.update') }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="update_password_only" value="1">

                                        <div class="row mb-3">
                                            <label for="current_password"
                                                class="col-sm-3 col-form-label form-label">Contraseña actual</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" name="current_password"
                                                    id="current_password" placeholder="Ingresa tu contraseña actual"
                                                    aria-label="Contraseña actual">
                                                @error('current_password')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="new_password" class="col-sm-3 col-form-label form-label">Nueva
                                                contraseña</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" name="password"
                                                    id="new_password" placeholder="Ingresa tu nueva contraseña"
                                                    aria-label="Nueva contraseña">
                                                @error('password')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label for="password_confirmation"
                                                class="col-sm-3 col-form-label form-label">Verificar contraseña</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" name="password_confirmation"
                                                    id="password_confirmation"
                                                    placeholder="Vuelve a escribir la nueva contraseña"
                                                    aria-label="Confirmar contraseña">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 p-2">
                                            <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/hs-nav-scroller/dist/hs-nav-scroller.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/hs-sticky-block/dist/hs-sticky-block.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/hs-file-attach/dist/hs-file-attach.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. PREVISUALIZACIÓN DEL BANNER
            const bannerInput = document.getElementById('profileCoverUplaoder');
            const bannerImg = document.getElementById('profileCoverImg');

            if (bannerInput && bannerImg) {
                bannerInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            bannerImg.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // 2. PREVISUALIZACIÓN DEL AVATAR
            const avatarInput = document.getElementById('editAvatarUploaderModal');
            const avatarImg = document.getElementById('editAvatarImgModal');

            if (avatarInput && avatarImg) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            avatarImg.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

        });
    </script>
@endpush
