<div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center min-vh-lg-100 position-relative bg-light px-0">
    <div class="position-absolute top-0 start-0 end-0 mt-3 mx-3">
        <div class="d-none d-lg-flex justify-content-between">
            <a href="/">
                <img class="w-100" src="{{ asset('img/logotipo.svg') }}" alt="Image Description" data-hs-theme-appearance="default" style="min-width: 12rem; max-width: 12rem;">
                <img class="w-100" src="{{ asset('img/logotipo-white.svg') }}" alt="Image Description" data-hs-theme-appearance="dark" style="min-width: 12rem; max-width: 12rem;">
            </a>
        </div>
    </div>
    <div style="max-width: 23rem;">

        <div class="mb-5">
            <h2 class="display-5">Bienvenido al sistema {{ config('app.name') }}</h2>
        </div>

        <ul class="list-checked list-checked-lg list-checked-primary list-py-2">
            <li class="list-checked-item">
                <span class="d-block fw-semibold mb-1">Registra y da seguimiento a la atención de los pacientes.</span>
                Registra, actualiza y consulta los pacientes, así como sus características y ubicaciones.
            </li>

            <li class="list-checked-item">
                <span class="d-block fw-semibold mb-1">Gestiona el expediente clínico del paciente.</span>
                Registra, actualiza y consulta el expediente clínico del paciente, así como sus características y ubicaciones.
            </li>
        </ul>
        
    </div>
</div>