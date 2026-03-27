@extends('layouts.panel')
@section('title', 'Crear Comunicado')

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/quill/dist/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/dist/flatpickr.min.css') }}">
@endsection

@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a class="breadcrumb-link"
                                href="{{ route('releases.index') }}">Comunicados</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear</li>
                    </ol>
                </nav>
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <h1 class="page-header-title">Nuevo Comunicado</h1>
                    </div>
                </div>
            </div>

            <div class="row justify-content-lg-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <form action="{{ route('releases.store') }}" method="POST" enctype="multipart/form-data"
                                id="releaseForm">
                                @csrf

                                <div class="row mb-4">
                                    <div class="col-md-4 mb-4 mb-md-0">
                                        <label for="published_at" class="form-label text-dark fw-bold">Programar
                                            publicación</label>
                                        <div class="input-group input-group-merge border-2 rounded">
                                            <span class="input-group-prepend input-group-text"><i
                                                    class="bi-calendar-event"></i></span>
                                            <input type="text" name="published_at" id="published_at"
                                                class="form-control flatpickr-custom" placeholder="Seleccionar fecha y hora"
                                                value="{{ old('published_at') }}">
                                        </div>
                                        <small class="text-muted">Vacío = Publicar ahora.</small>
                                    </div>

                                    <div class="col-md-4 mb-4 mb-md-0">
                                        <label for="type" class="form-label text-dark fw-bold">Clasificación</label>
                                        <select name="type" id="type" class="form-select border-2">
                                            <option value="comunicado" {{ old('type') == 'comunicado' ? 'selected' : '' }}>
                                                Comunicado</option>
                                            <option value="actualizacion"
                                                {{ old('type') == 'actualizacion' ? 'selected' : '' }}>Actualización
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status" class="form-label text-dark fw-bold">Visibilidad
                                            inicial</label>
                                        <select name="status" id="status" class="form-select border-2">
                                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                                Publicado / Programado</option>
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Borrador
                                                (Oculto)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-5 p-4 bg-light rounded border border-2 border-dashed">
                                    <label class="form-label text-dark fw-bold d-block">Documentos o Imágenes adjuntas
                                        (Opcional)</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <label class="btn btn-primary mb-0" for="documents" style="cursor: pointer;">
                                            <i class="bi-cloud-arrow-up-fill me-1"></i> Seleccionar archivos
                                        </label>
                                        <span id="file-chosen" class="text-muted fw-semibold">Ningún archivo
                                            seleccionado</span>
                                    </div>
                                    <input type="file" name="documents[]" id="documents" class="d-none"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                                    <small class="text-muted d-block mt-2">Formatos permitidos: PDF, Word, Imágenes (Máx.
                                        50MB por documento)</small>
                                </div>

                                <hr class="my-4">

                                <div class="mb-4">
                                    <label for="title" class="form-label text-dark fw-bold">Título del Comunicado</label>
                                    <input type="text" name="title" id="title"
                                        class="form-control form-control-lg border-2"
                                        placeholder="Ej: Nueva Actualización de Sistema" value="{{ old('title') }}"
                                        required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-dark fw-bold">Cuerpo del Contenido</label>
                                    <div class="quill-custom border-2 rounded">
                                        <div id="editor" class="bg-white" style="height: 350px;">{!! old('content') !!}
                                        </div>
                                    </div>
                                    <input type="hidden" name="content" id="content_hidden">
                                </div>

                                <div class="d-flex justify-content-end gap-3 mt-5">
                                    <a href="{{ route('releases.index') }}" class="btn btn-white">Descartar</a>
                                    <button type="submit" class="btn btn-primary px-4" id="saveBtn">
                                        <i class="bi-check2-circle me-1"></i> Confirmar y Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/quill/dist/quill.min.js') }}"></script>
    <script src="{{ asset('vendor/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendor/flatpickr/dist/l10n/es.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Inicialización de Flatpickr directa y segura
            flatpickr("#published_at", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                locale: "es",
                disableMobile: "true"
            });

            // 2. Lógica para el botón de múltiples archivos
            const fileInput = document.getElementById('documents');
            const fileChosen = document.getElementById('file-chosen');

            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    // Si es 1 archivo, muestra el nombre. Si son varios, muestra la cantidad.
                    if (this.files.length === 1) {
                        fileChosen.textContent = this.files[0].name;
                    } else {
                        fileChosen.textContent = this.files.length + ' archivos seleccionados';
                    }
                    fileChosen.classList.remove('text-muted');
                    fileChosen.classList.add('text-primary');
                } else {
                    fileChosen.textContent = 'Ningún archivo seleccionado';
                    fileChosen.classList.add('text-muted');
                    fileChosen.classList.remove('text-primary');
                }
            });

            // 3. Inicialización de Quill
            var quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{
                            'header': [1, 2, 3, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['link', 'blockquote'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        ['clean']
                    ]
                }
            });

            // 4. Sincronizar contenido Quill antes de enviar el formulario
            document.getElementById('releaseForm').addEventListener('submit', function(e) {
                var html = quill.root.innerHTML;
                if (html === '<p><br></p>') html = '';
                document.getElementById('content_hidden').value = html;
            });
        });
    </script>
@endpush
