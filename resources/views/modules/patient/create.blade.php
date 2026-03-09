@extends('layouts.panel')
@section('title', 'Crear Paciente')

@section('content')
    <main id="content" role="main" class="main">
    <div class="content container-fluid">
      <form id="addPatientForm" action="{{ route('patients.store') }}" method="POST" class="js-step-form py-md-5" data-hs-step-form-options='{"progressSelector": "#addUserStepFormProgress","stepsSelector": "#addUserStepFormContent","endSelector": "#addUserFinishBtn","isValidate": false}'>
        @csrf
        <div class="row justify-content-lg-center">
          <div class="col-lg-8">
            <ul id="addUserStepFormProgress" class="js-step-progress step step-sm step-icon-sm step step-inline step-item-between mb-3 mb-md-5">
              <li class="step-item">
                <a class="step-content-wrapper" href="javascript:;" data-hs-step-form-next-options='{"targetSelector": "#addUserStepProfile"}'>
                  <span class="step-icon step-icon-soft-dark">1</span>
                  <div class="step-content">
                    <span class="step-title">Datos Generales</span>
                  </div>
                </a>
              </li>

              <li class="step-item">
                <a class="step-content-wrapper" href="javascript:;" data-hs-step-form-next-options='{"targetSelector": "#addUserStepBillingAddress"}'>
                  <span class="step-icon step-icon-soft-dark">2</span>
                  <div class="step-content">
                    <span class="step-title">Ubicación</span>
                  </div>
                </a>
              </li>

              <li class="step-item">
                <a class="step-content-wrapper" href="javascript:;" data-hs-step-form-next-options='{"targetSelector": "#addUserStepConfirmation"}'>
                  <span class="step-icon step-icon-soft-dark">3</span>
                  <div class="step-content">
                    <span class="step-title">Datos de Contacto</span>
                  </div>
                </a>
              </li>

              <li class="step-item">
                <a class="step-content-wrapper" href="javascript:;" data-hs-step-form-next-options='{"targetSelector": "#addUserStepConfirmation"}'>
                  <span class="step-icon step-icon-soft-dark">4</span>
                  <div class="step-content">
                    <span class="step-title">Confirmación</span>
                  </div>
                </a>
              </li>

            </ul>

            <div id="addUserStepFormContent">
              <div id="addUserStepProfile" class="card card-lg active">
                <div class="card-body">
                  <div class="row mb-4">
                    <label for="firstNameLabel" class="col-sm-3 col-form-label form-label">Nombres <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Rellene los nombres del paciente"></i></label>
                    <div class="col-sm-9">
                      <div class="input-group input-group-sm-vertical">
                        <input type="text" class="form-control" name="first_name" id="firstNameLabel" placeholder="Primer nombre" aria-label="Primer nombre" required>
                        <input type="text" class="form-control" name="second_name" id="secondNameLabel" placeholder="Segundo nombre" aria-label="Segundo nombre">
                        <input type="text" class="form-control" name="third_name" id="thirdNameLabel" placeholder="Tercer nombre" aria-label="Tercer nombre">
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="firstLastNameLabel" class="col-sm-3 col-form-label form-label">Apellidos <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Rellene los apellidos del paciente"></i></label>
                    <div class="col-sm-9">
                      <div class="input-group input-group-sm-vertical">
                        <input type="text" class="form-control" name="first_last_name" id="firstLastNameLabel" placeholder="Primer apellido" aria-label="Primer apellido" required>
                        <input type="text" class="form-control" name="second_last_name" id="secondLastNameLabel" placeholder="Segundo apellido" aria-label="Segundo apellido">
                        <input type="text" class="form-control" name="married_last_name" id="marriedLastNameLabel" placeholder="Apellido de casada" aria-label="Apellido de casada">
                      </div>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="dpiLabel" class="col-sm-3 col-form-label form-label">DPI <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Rellene el DPI del paciente"></i></label>
                    <div class="col-sm-9">
                      <input type="text" class="js-input-mask form-control" name="dpi" id="dpiLabel" placeholder="0000 00000 0000" aria-label="DPI" data-hs-mask-options='{"mask": "0000 00000 0000"}'>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="emailLabel" class="col-sm-3 col-form-label form-label">Correo electrónico <span class="form-label-secondary">(Opcional)</span></label>
                    <div class="col-sm-9">
                      <input type="email" class="form-control" name="email" id="emailLabel" placeholder="ejemplo@correo.com" aria-label="ejemplo@correo.com">
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="phoneLabel" class="col-sm-3 col-form-label form-label">Teléfono <span class="form-label-secondary">(Opcional)</span></label>
                    <div class="col-sm-9">
                      <input type="text" class="js-input-mask form-control" name="phone" id="phoneLabel" placeholder="00000000" aria-label="00000000" data-hs-mask-options='{"mask": "00000000"}'>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="birthDateLabel" class="col-sm-3 col-form-label form-label">Fecha de nacimiento</label>
                    <div class="col-sm-9">
                      <input type="date" class="form-control" name="birth_date" id="birthDateLabel" required>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="genderLabel" class="col-sm-3 col-form-label form-label">Género</label>
                    <div class="col-sm-9">
                      <select class="js-select form-select" name="gender_id" id="genderLabel" data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                        <option value="">Seleccione</option>
                        @foreach($genders as $gender)
                          <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="civilStatusLabel" class="col-sm-3 col-form-label form-label">Estado civil</label>
                    <div class="col-sm-9">
                      <select class="js-select form-select" name="civil_status_id" id="civilStatusLabel" data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                        <option value="">Seleccione</option>
                        @foreach($civilStatuses as $status)
                          <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="ethnicityLabel" class="col-sm-3 col-form-label form-label">Etnia</label>
                    <div class="col-sm-9">
                      <select class="js-select form-select" name="ethnicity_id" id="ethnicityLabel" data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                        <option value="">Seleccione</option>
                        @foreach($ethnicities as $ethnicity)
                          <option value="{{ $ethnicity->id }}">{{ $ethnicity->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="linguisticCommunityLabel" class="col-sm-3 col-form-label form-label">Comunidad lingüística</label>
                    <div class="col-sm-9">
                      <select class="js-select form-select" name="linguistic_community_id" id="linguisticCommunityLabel" data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                        <option value="">Seleccione</option>
                        @foreach($linguisticCommunities as $community)
                          <option value="{{ $community->id }}">{{ $community->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                <div class="card-footer d-flex justify-content-end align-items-center">
                  <button type="button" class="btn btn-primary" data-hs-step-form-next-options='{
                            "targetSelector": "#addUserStepBillingAddress"
                          }'>
                    Siguiente <i class="bi-chevron-right"></i>
                  </button>
                </div>
              </div>



              <!-- Step 2 -->
              <div id="addUserStepBillingAddress" class="card card-lg" style="display: none;">
                <div class="card-body">
                  <div class="row mb-4">
                    <label for="countryLabel" class="col-sm-3 col-form-label form-label">País</label>
                    <div class="col-sm-9">
                      <select class="js-select form-select" name="country_id" id="countryLabel" data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                        <option value="">Seleccione</option>
                        @foreach($countries as $country)
                          <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="departmentLabel" class="col-sm-3 col-form-label form-label">Departamento</label>
                    <div class="col-sm-9">
                      <select class="js-select form-select" name="department_id" id="departmentLabel" data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                        <option value="">Seleccione</option>
                        @foreach($departments as $department)
                          <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="municipalityLabel" class="col-sm-3 col-form-label form-label">Municipio</label>
                    <div class="col-sm-9">
                      <select class="js-select form-select" name="municipality_id" id="municipalityLabel" data-hs-tom-select-options='{"searchInDropdown": true, "hideSearch": false}'>
                        <option value="">Seleccione</option>
                        @foreach($municipalities as $municipality)
                          <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="row mb-4">
                    <label for="placeLabel" class="col-sm-3 col-form-label form-label">Lugar / Dirección</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="place" id="placeLabel" placeholder="Dirección exacta" aria-label="Dirección exacta">
                    </div>
                  </div>
                </div>

                <div class="card-footer d-flex align-items-center">
                  <button type="button" class="btn btn-ghost-secondary" data-hs-step-form-prev-options='{
                       "targetSelector": "#addUserStepProfile"
                     }'>
                    <i class="bi-chevron-left"></i> Anterior
                  </button>

                  <div class="ms-auto">
                    <button type="button" class="btn btn-primary" data-hs-step-form-next-options='{
                              "targetSelector": "#addUserStepFamily"
                            }'>
                      Siguiente <i class="bi-chevron-right"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Step 3 -->
              <div id="addUserStepFamily" class="card card-lg" style="display: none;">
                <div class="card-body">

                  <div class="row mb-4">
                    <label for="familyLabel" class="col-sm-3 col-form-label form-label">Familiares</label>
                    <div class="col-sm-9">
                      <select class="form-select" name="family" id="familyLabel">
                        <option value="">Seleccione</option>
                        <option value="1">Familiar 1</option>
                        <option value="2">Familiar 2</option>
                        <option value="3">Familiar 3</option>
                        <option value="4">Familiar 4</option>
                      </select>
                    </div>
                  
                    <button type="button" class="btn btn-primary">Agregar familiar</button>
                  </div>

                </div>

                <div class="card-footer d-flex align-items-center">
                  <button type="button" class="btn btn-ghost-secondary" data-hs-step-form-prev-options='{
                       "targetSelector": "#addUserStepProfile"
                     }'>
                    <i class="bi-chevron-left"></i> Anterior
                  </button>

                  <div class="ms-auto">
                    <button type="button" class="btn btn-primary" data-hs-step-form-next-options='{
                              "targetSelector": "#addUserStepConfirmation"
                            }'>
                      Siguiente <i class="bi-chevron-right"></i>
                    </button>
                  </div>
                </div>
              </div>  

              <!-- Step 4 -->
              <div id="addUserStepConfirmation" class="card card-lg" style="display: none;">
                <div class="profile-cover">
                  <div class="profile-cover-img-wrapper">
                    <img class="profile-cover-img" src="./assets/img/1920x400/img1.jpg" alt="Image Description">
                  </div>
                </div>

                <div class="avatar avatar-xxl avatar-circle avatar-border-lg profile-cover-avatar">
                  <img class="avatar-img" src="./assets/img/160x160/img9.jpg" alt="Image Description">
                </div>

                <div class="card-body">
                  <dl class="row">
                    <dt class="col-sm-6 text-sm-end">Primer Nombre:</dt>
                    <dd class="col-sm-6">Ella Lauda</dd>

                    <dt class="col-sm-6 text-sm-end">Segundo Nombre:</dt>
                    <dd class="col-sm-6">Ella Lauda</dd>

                    <dt class="col-sm-6 text-sm-end"> Primer Apellido:</dt>
                    <dd class="col-sm-6">Ella Lauda</dd>

                    <dt class="col-sm-6 text-sm-end"> Segundo Apellido:</dt>
                    <dd class="col-sm-6">Ella Lauda</dd>

                    <dt class="col-sm-6 text-sm-end">Apellido Casada:</dt>
                    <dd class="col-sm-6">Ella Lauda</dd>

                    <dt class="col-sm-6 text-sm-end">Correo electrónico:</dt>
                    <dd class="col-sm-6">ella@site.com</dd>

                    <dt class="col-sm-6 text-sm-end">Teléfono:</dt>
                    <dd class="col-sm-6">+1 (609) 972-22-22</dd>

                    <dt class="col-sm-6 text-sm-end">DPI:</dt>
                    <dd class="col-sm-6">Htmlstream</dd>

                    <dt class="col-sm-6 text-sm-end">Fecha de nacimiento:</dt>
                    <dd class="col-sm-6">-</dd>

                    <dt class="col-sm-6 text-sm-end">Género:</dt>
                    <dd class="col-sm-6">Individual</dd>

                    <dt class="col-sm-6 text-sm-end">Estado civil:</dt>
                    <dd class="col-sm-6"><img class="avatar avatar-xss avatar-circle me-1" src="./assets/vendor/flag-icon-css/flags/1x1/gb.svg" alt="Great Britain Flag"> United Kingdom</dd>

                    <dt class="col-sm-6 text-sm-end">País:</dt>
                    <dd class="col-sm-6">London</dd>

                    <dt class="col-sm-6 text-sm-end">Departamento:</dt>
                    <dd class="col-sm-6">-</dd>

                    <dt class="col-sm-6 text-sm-end">Municipio:</dt>
                    <dd class="col-sm-6">45 Roker Terrace, Latheronwheel</dd>

                    <dt class="col-sm-6 text-sm-end">Dirección:</dt>
                    <dd class="col-sm-6">-</dd>
                  </dl>
                </div>

                <div class="card-footer d-sm-flex align-items-sm-center">
                  <button type="button" class="btn btn-ghost-secondary mb-2 mb-sm-0" data-hs-step-form-prev-options='{
                       "targetSelector": "#addUserStepBillingAddress"
                     }'>
                    <i class="bi-chevron-left"></i> Anterior
                  </button>

                  <div class="ms-auto">
                    <button id="addUserFinishBtn" type="button" class="btn btn-primary" onclick="document.getElementById('addPatientForm').submit();">Guardar</button>
                  </div>
                </div>
              </div>
            </div>

            <div id="successMessageContent" style="display:none;">
              <div class="text-center">
                <img class="img-fluid mb-3" src="./assets/svg/illustrations/oc-hi-five.svg" alt="Image Description" data-hs-theme-appearance="default" style="max-width: 15rem;">
                <img class="img-fluid mb-3" src="./assets/svg/illustrations-light/oc-hi-five.svg" alt="Image Description" data-hs-theme-appearance="dark" style="max-width: 15rem;">

                <div class="mb-4">
                  <h2>¡Exitoso!</h2>
                  <p>El paciente ha sido creado exitosamente y guardado en la base de datos.</p>
                </div>

                <div class="d-flex justify-content-center">
                  <a class="btn btn-white me-3" href="{{ route('patients.index') }}">
                    <i class="bi-chevron-left ms-1"></i> Volver a pacientes
                  </a>
                  <a class="btn btn-primary" href="{{ route('patients.create') }}">
                    <i class="bi-person-plus-fill me-1"></i> Agregar nuevo paciente
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/hs-file-attach/dist/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-step-form/dist/hs-step-form.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-add-field/dist/hs-add-field.min.js') }}"></script>
    <script src="{{ asset('vendor/imask/dist/imask.min.js') }}"></script>
    <script src="{{ asset('vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>

    <!-- JS Plugins Init. -->
    <script>
    (function () {
      window.onload = function () {


        // INITIALIZATION OF NAVBAR VERTICAL ASIDE
        // =======================================================
        new HSSideNav('.js-navbar-vertical-aside').init()


        // INITIALIZATION OF FORM SEARCH
        // =======================================================
        new HSFormSearch('.js-form-search')


        // INITIALIZATION OF BOOTSTRAP DROPDOWN
        // =======================================================
        HSBsDropdown.init()


        // INITIALIZATION OF FILE ATTACH
        // =======================================================
        new HSFileAttach('.js-file-attach')


        // INITIALIZATION OF STEP FORM
        // =======================================================
        new HSStepForm('.js-step-form', {
          finish: () => {
            document.getElementById("addUserStepFormProgress").style.display = 'none'
            document.getElementById("addUserStepProfile").style.display = 'none'
            document.getElementById("addUserStepBillingAddress").style.display = 'none'
            document.getElementById("addUserStepConfirmation").style.display = 'none'
            document.getElementById("successMessageContent").style.display = 'block'
            scrollToTop('#header');
            const formContainer = document.getElementById('formContainer')
          },
          onNextStep: function () {
            scrollToTop()
          },
          onPrevStep: function () {
            scrollToTop()
          }
        })

        function scrollToTop(el = '.js-step-form') {
          el = document.querySelector(el)
          window.scrollTo({
            top: (el.getBoundingClientRect().top + window.scrollY) - 30,
            left: 0,
            behavior: 'smooth'
          })
        }


        // INITIALIZATION OF ADD FIELD
        // =======================================================
        new HSAddField('.js-add-field', {
          addedField: field => {
            HSCore.components.HSTomSelect.init(field.querySelector('.js-select-dynamic'))
            HSCore.components.HSMask.init(field.querySelector('.js-input-mask'))
          }
        })


        // INITIALIZATION OF SELECT
        // =======================================================
        HSCore.components.HSTomSelect.init('.js-select', {
          render: {
            'option': function (data, escape) {
              return data.optionTemplate || `<div>${data.text}</div>>`
            },
            'item': function (data, escape) {
              return data.optionTemplate || `<div>${data.text}</div>>`
            }
          }
        })


        // INITIALIZATION OF INPUT MASK
        // =======================================================
        HSCore.components.HSMask.init('.js-input-mask')
      }
    })()
  </script>

  <!-- Style Switcher JS -->

  <script>
      (function () {
        // STYLE SWITCHER
        // =======================================================
        const $dropdownBtn = document.getElementById('selectThemeDropdown') // Dropdowon trigger
        const $variants = document.querySelectorAll(`[aria-labelledby="selectThemeDropdown"] [data-icon]`) // All items of the dropdown

        // Function to set active style in the dorpdown menu and set icon for dropdown trigger
        const setActiveStyle = function () {
          $variants.forEach($item => {
            if ($item.getAttribute('data-value') === HSThemeAppearance.getOriginalAppearance()) {
              $dropdownBtn.innerHTML = `<i class="${$item.getAttribute('data-icon')}" />`
              return $item.classList.add('active')
            }

            $item.classList.remove('active')
          })
        }

        // Add a click event to all items of the dropdown to set the style
        $variants.forEach(function ($item) {
          $item.addEventListener('click', function () {
            HSThemeAppearance.setAppearance($item.getAttribute('data-value'))
          })
        })

        // Call the setActiveStyle on load page
        setActiveStyle()

        // Add event listener on change style to call the setActiveStyle function
        window.addEventListener('on-hs-appearance-change', function () {
          setActiveStyle()
        })
      })()
  </script>
@endpush