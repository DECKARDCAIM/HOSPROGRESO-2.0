@extends('layouts.panel')
@section('title', 'Crear Usuario')

@section('content')
<main id="content" role="main" class="main">
    <!-- Content -->
    <div class="content container-fluid">
        <!-- Step Form -->
        <form action="{{ route('users.store') }}" method="POST" class="js-step-form py-md-5" data-hs-step-form-options='{
              "progressSelector": "#addUserStepFormProgress",
              "stepsSelector": "#addUserStepFormContent",
              "endSelector": "#addUserFinishBtn",
              "isValidate": false
            }'>
            @csrf
            <div class="row justify-content-lg-center">
                <div class="col-lg-8">
                    <!-- Step -->
                    <ul id="addUserStepFormProgress"
                        class="js-step-progress step step-sm step-icon-sm step step-inline step-item-between mb-3 mb-md-5">
                        <li class="step-item">
                            <a class="step-content-wrapper" href="javascript:;"
                                data-hs-step-form-next-options='{ "targetSelector": "#addUserStepProfile" }'>
                                <span class="step-icon step-icon-soft-dark">1</span>
                                <div class="step-content">
                                    <span class="step-title">Perfil</span>
                                </div>
                            </a>
                        </li>
                        <li class="step-item">
                            <a class="step-content-wrapper" href="javascript:;"
                                data-hs-step-form-next-options='{ "targetSelector": "#addUserStepProfessional" }'>
                                <span class="step-icon step-icon-soft-dark">2</span>
                                <div class="step-content">
                                    <span class="step-title">Profesional</span>
                                </div>
                            </a>
                        </li>
                        <li class="step-item">
                            <a class="step-content-wrapper" href="javascript:;"
                                data-hs-step-form-next-options='{ "targetSelector": "#addUserStepLocation" }'>
                                <span class="step-icon step-icon-soft-dark">3</span>
                                <div class="step-content">
                                    <span class="step-title">Ubicación</span>
                                </div>
                            </a>
                        </li>
                        <li class="step-item">
                            <a class="step-content-wrapper" href="javascript:;"
                                data-hs-step-form-next-options='{ "targetSelector": "#addUserStepConfirmation" }'>
                                <span class="step-icon step-icon-soft-dark">4</span>
                                <div class="step-content">
                                    <span class="step-title">Confirmación</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- End Step -->

                    <!-- Content Step Form -->
                    <div id="addUserStepFormContent">
                        <!-- Card Profile -->
                        <div id="addUserStepProfile" class="card card-lg active">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-sm-4 mb-2">
                                        <label for="firstNameLabel" class="form-label">Primer Nombre</label>
                                        <input type="text" class="form-control" name="first_name" id="firstNameLabel"
                                            placeholder="Ej. Juan">
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <label for="secondNameLabel" class="form-label">Segundo Nombre</label>
                                        <input type="text" class="form-control" name="second_name" id="secondNameLabel"
                                            placeholder="Ej. Carlos">
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <label for="thirdNameLabel" class="form-label">Tercer Nombre</label>
                                        <input type="text" class="form-control" name="third_name" id="thirdNameLabel"
                                            placeholder="Ej. Luis">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-4 mb-2">
                                        <label for="firstLastNameLabel" class="form-label">Primer Apellido</label>
                                        <input type="text" class="form-control" name="first_last_name"
                                            id="firstLastNameLabel" placeholder="Ej. Pérez">
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <label for="secondLastNameLabel" class="form-label">Segundo Apellido</label>
                                        <input type="text" class="form-control" name="second_last_name"
                                            id="secondLastNameLabel" placeholder="Ej. Gómez">
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <label for="marriedLastNameLabel" class="form-label">Apellido de Casada</label>
                                        <input type="text" class="form-control" name="married_last_name"
                                            id="marriedLastNameLabel" placeholder="Ej. de López">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-4 mb-2">
                                        <label for="cuiLabel" class="form-label">CUI</label>
                                        <input type="text" class="form-control" name="cui" id="cuiLabel"
                                            placeholder="CUI">
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <label for="nitLabel" class="form-label">NIT</label>
                                        <input type="text" class="form-control" name="nit" id="nitLabel"
                                            placeholder="NIT">
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <label for="maritalStatusLabel" class="form-label">Estado Civil</label>
                                        <select class="form-select" name="marital_status" id="maritalStatusLabel">
                                            <option value="" selected>Seleccione</option>
                                            <option value="soltero">Soltero(a)</option>
                                            <option value="casado">Casado(a)</option>
                                            <option value="divorciado">Divorciado(a)</option>
                                            <option value="viudo">Viudo(a)</option>
                                            <option value="union_libre">Unión Libre</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-4 mb-2">
                                        <label for="birthDateLabel" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" name="birth_date" id="birthDateLabel">
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <label for="genderLabel" class="form-label">Género</label>
                                        <select class="form-select" name="gender" id="genderLabel">
                                            <option value="" selected>Seleccione</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                            <option value="O">Otro</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <label for="phoneLabel" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" name="phone" id="phoneLabel"
                                            placeholder="Ej. +12345678">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-6 mb-2">
                                        <label for="emailLabel" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" name="email" id="emailLabel"
                                            placeholder="correo@ejemplo.com">
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <label for="passwordLabel" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" name="password" id="passwordLabel"
                                            placeholder="********">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end align-items-center">
                                <button type="button" class="btn btn-primary"
                                    data-hs-step-form-next-options='{ "targetSelector": "#addUserStepProfessional" }'>
                                    Siguiente paso <i class="bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <!-- End Card Profile -->

                        <!-- Card Professional -->
                        <div id="addUserStepProfessional" class="card card-lg" style="display: none;">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-sm-12 mb-4">
                                        <label for="roleIdLabel" class="form-label">Rol en el Sistema</label>
                                        <select class="js-select form-select" name="role_id" id="roleIdLabel"
                                            data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                                            <option value="" selected>Seleccione Rol</option>
                                            @foreach($roles ?? [] as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-6 mb-2">
                                        <label for="specialtyLabel" class="form-label">Especialidad (Si es
                                            Médico)</label>
                                        <select class="form-select" name="specialty_id" id="specialtyLabel">
                                            <option value="" selected>Seleccione Especialidad</option>
                                            @foreach($specialties ?? [] as $specialty)
                                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <label for="scheduleIdLabel" class="form-label">Asignar Horario de
                                            Trabajo</label>
                                        <select class="js-select form-select" name="schedule_id" id="scheduleIdLabel"
                                            data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                                            <option value="" selected>Seleccione Horario</option>
                                            @foreach($schedules ?? [] as $schedule)
                                            <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Footer -->
                            <div class="card-footer d-flex align-items-center">
                                <button type="button" class="btn btn-ghost-secondary"
                                    data-hs-step-form-prev-options='{ "targetSelector": "#addUserStepProfile" }'>
                                    <i class="bi-chevron-left"></i> Paso anterior
                                </button>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary"
                                        data-hs-step-form-next-options='{ "targetSelector": "#addUserStepLocation" }'>
                                        Siguiente paso <i class="bi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- End Footer -->
                        </div>
                        <!-- End Card Professional -->

                        <!-- Card Location -->
                        <div id="addUserStepLocation" class="card card-lg" style="display: none;">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-sm-4 mb-2">
                                        <label for="countryIdLabel" class="form-label">País</label>
                                        <select class="form-select" name="country_id" id="countryIdLabel">
                                            <option value="" selected>Seleccione País</option>
                                            @foreach($countries ?? [] as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <label for="departmentIdLabel" class="form-label">Departamento</label>
                                        <select class="form-select" name="department_id" id="departmentIdLabel">
                                            <option value="" selected>Seleccione Departamento</option>
                                            @foreach($departments ?? [] as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mb-2">
                                        <label for="municipalityIdLabel" class="form-label">Municipio</label>
                                        <select class="form-select" name="municipality_id" id="municipalityIdLabel">
                                            <option value="" selected>Seleccione Municipio</option>
                                            @foreach($municipalities ?? [] as $municipality)
                                            <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-12">
                                        <label for="addressLabel" class="form-label">Dirección Exacta</label>
                                        <input type="text" class="form-control" name="address" id="addressLabel"
                                            placeholder="Ej. 1ra Avenida 2-33 Zona 1">
                                    </div>
                                </div>
                            </div>
                            <!-- Footer -->
                            <div class="card-footer d-flex align-items-center">
                                <button type="button" class="btn btn-ghost-secondary"
                                    data-hs-step-form-prev-options='{ "targetSelector": "#addUserStepProfessional" }'>
                                    <i class="bi-chevron-left"></i> Paso anterior
                                </button>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-primary"
                                        data-hs-step-form-next-options='{ "targetSelector": "#addUserStepConfirmation" }'>
                                        Siguiente paso <i class="bi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- End Footer -->
                        </div>
                        <!-- End Card Location -->

                        <!-- Card Confirmation -->
                        <div id="addUserStepConfirmation" class="card card-lg" style="display: none;">
                            <div class="card-body">
                                <h3 class="card-title text-center mb-5">Por favor, confirma que la información es
                                    correcta antes de guardar.</h3>

                                <h5 class="card-title">Información del Perfil</h5>
                                <dl class="row mb-5">
                                    <dt class="col-sm-6 text-sm-end">Nombre Completo:</dt>
                                    <dd class="col-sm-6" id="confirm-fullName">-</dd>

                                    <dt class="col-sm-6 text-sm-end">CUI:</dt>
                                    <dd class="col-sm-6" id="confirm-cui">-</dd>

                                    <dt class="col-sm-6 text-sm-end">NIT:</dt>
                                    <dd class="col-sm-6" id="confirm-nit">-</dd>

                                    <dt class="col-sm-6 text-sm-end">Estado Civil:</dt>
                                    <dd class="col-sm-6" id="confirm-maritalStatus">-</dd>

                                    <dt class="col-sm-6 text-sm-end">Fecha de Nacimiento:</dt>
                                    <dd class="col-sm-6" id="confirm-birthDate">-</dd>

                                    <dt class="col-sm-6 text-sm-end">Género:</dt>
                                    <dd class="col-sm-6" id="confirm-gender">-</dd>

                                    <dt class="col-sm-6 text-sm-end">Teléfono:</dt>
                                    <dd class="col-sm-6" id="confirm-phone">-</dd>

                                    <dt class="col-sm-6 text-sm-end">Correo Electrónico:</dt>
                                    <dd class="col-sm-6" id="confirm-email">-</dd>
                                </dl>
                                <hr>

                                <h5 class="card-title">Información Profesional</h5>
                                <dl class="row mb-5">
                                    <dt class="col-sm-6 text-sm-end">Rol en el Sistema:</dt>
                                    <dd class="col-sm-6" id="confirm-roleId">-</dd>

                                    <dt class="col-sm-6 text-sm-end">Especialidad:</dt>
                                    <dd class="col-sm-6" id="confirm-specialty">-</dd>

                                    <dt class="col-sm-6 text-sm-end">Horario Asignado:</dt>
                                    <dd class="col-sm-6" id="confirm-scheduleId">-</dd>
                                </dl>
                                <hr>

                                <h5 class="card-title">Ubicación</h5>
                                <dl class="row">
                                    <dt class="col-sm-6 text-sm-end">País:</dt>
                                    <dd class="col-sm-6" id="confirm-countryId">-</dd>

                                    <dt class="col-sm-6 text-sm-end">Departamento:</dt>
                                    <dd class="col-sm-6" id="confirm-departmentId">-</dd>

                                    <dt class="col-sm-6 text-sm-end">Municipio:</dt>
                                    <dd class="col-sm-6" id="confirm-municipalityId">-</dd>

                                    <dt class="col-sm-6 text-sm-end">Dirección:</dt>
                                    <dd class="col-sm-6" id="confirm-address">-</dd>
                                </dl>
                            </div>
                            <!-- Footer -->
                            <div class="card-footer d-sm-flex align-items-sm-center">
                                <button type="button" class="btn btn-ghost-secondary mb-2 mb-sm-0"
                                    data-hs-step-form-prev-options='{ "targetSelector": "#addUserStepLocation" }'>
                                    <i class="bi-chevron-left"></i> Paso anterior
                                </button>
                                <div class="ms-auto">
                                    <button id="addUserFinishBtn" type="button" class="btn btn-primary">Crear
                                        usuario</button>
                                </div>
                            </div>
                            <!-- End Footer -->
                        </div>
                        <!-- End Card Confirmation -->

                        <!-- Message Body -->
                        @if(session('success'))
                        <div id="successMessageContent">
                            <div class="text-center">
                                <img class="img-fluid mb-3" src="{{ asset('svg/illustrations/oc-hi-five.svg') }}"
                                    alt="Image Description" data-hs-theme-appearance="default"
                                    style="max-width: 15rem;">
                                <img class="img-fluid mb-3" src="{{ asset('svg/illustrations-light/oc-hi-five.svg') }}"
                                    alt="Image Description" data-hs-theme-appearance="dark" style="max-width: 15rem;">
                                <div class="mb-4">
                                    <h2>¡Usuario Creado!</h2>
                                    <p>{{ session('success') }}</p>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <a class="btn btn-white me-3" href="{{ route('users.index') }}">
                                        <i class="bi-chevron-left ms-1"></i> Volver a usuarios
                                    </a>
                                    <a class="btn btn-primary" href="{{ route('users.create') }}">
                                        <i class="bi-person-plus-fill me-1"></i> Nuevo usuario
                                    </a>
                                </div>
                            </div>
                        </div>
                        <script>
                            window.addEventListener('load', function () {
                                document.getElementById("addUserStepFormProgress").style.display = 'none';
                                document.getElementById("addUserStepFormContent").style.display = 'none';
                            });
                        </script>
                        @endif
                        <!-- End Message Body -->

                    </div>
                    <!-- End Content Step Form -->
                </div>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('vendor/hs-step-form/dist/hs-step-form.min.js') }}"></script>
<script src="{{ asset('vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
<script>
    (function () {
        window.onload = function () {
            // Función para actualizar datos en confirmación
            function updateConfirmation() {
                let fullName = [
                    document.getElementById('firstNameLabel')?.value,
                    document.getElementById('secondNameLabel')?.value,
                    document.getElementById('thirdNameLabel')?.value,
                    document.getElementById('firstLastNameLabel')?.value,
                    document.getElementById('secondLastNameLabel')?.value,
                    document.getElementById('marriedLastNameLabel')?.value
                ].filter(Boolean).join(' ');

                document.getElementById('confirm-fullName').textContent = fullName || '-';

                var fieldIds = [
                    'cui', 'nit', 'maritalStatus', 'birthDate', 'gender', 'phone', 'email',
                    'roleId', 'specialty', 'scheduleId', 'countryId', 'departmentId', 'municipalityId', 'address'
                ];

                fieldIds.forEach(function (field) {
                    let el = document.getElementById(field + 'Label');
                    let displayEl = document.getElementById('confirm-' + field);

                    if (el && displayEl) {
                        if (el.tagName === 'SELECT') {
                            displayEl.textContent = el.options[el.selectedIndex]?.text || '-';
                        } else {
                            displayEl.textContent = el.value || '-';
                        }
                    }
                });
            }

            // INITIALIZATION OF STEP FORM
            new HSStepForm('.js-step-form', {
                finish: () => {
                    const form = document.querySelector('.js-step-form');
                    form.submit();
                },
                onNextStep: function () {
                    updateConfirmation();
                    scrollToTop();
                },
                onPrevStep: function () {
                    scrollToTop();
                }
            });

            if (typeof HSCore !== 'undefined' && HSCore.components.HSTomSelect) {
                HSCore.components.HSTomSelect.init('.js-select');
            }

            function scrollToTop(el = '.js-step-form') {
                var element = document.querySelector(el);
                if (element) {
                    window.scrollTo({
                        top: (element.getBoundingClientRect().top + window.scrollY) - 30,
                        left: 0,
                        behavior: 'smooth'
                    });
                }
            }
        }
    })();
</script>
@endpush