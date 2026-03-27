@extends('layouts.panel')
@section('title', 'Editar Comunicado')

@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('releases.index') }}">Comunicados</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                    </ol>
                </nav>
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <h1 class="page-header-title">Modificar Comunicado</h1>
                    </div>
                </div>
            </div>

            <div class="row justify-content-lg-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm mb-4 border-2 border-primary-light">
                        <div class="card-body">
                            <form action="{{ route('releases.update', $release->id) }}" method="POST" enctype="multipart/form-data" id="releaseForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label for="title" class="form-label text-dark fw-bold">Título del Comunicado</label>
                                    <input type="text" name="title" id="title" class="form-control form-control-lg border-2" value="{{ old('title', $release->title) }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-dark fw-bold">Contenido</label>
                                    <div class="quill-custom">
                                        <div id="editor" class="bg-white" style="height: 350px;">{!! old('content', $release->content) !!}</div>
                                    </div>
                                    <input type="hidden" name="content" id="content_hidden">
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <label for="type" class="form-label text-dark fw-bold">Clasificación</label>
                                        <select name="type" id="type" class="form-select border-2">
                                            <option value="comunicado" {{ old('type', $release->type) == 'comunicado' ? 'selected' : '' }}>📢 Comunicado</option>
                                            <option value="actualizacion" {{ old('type', $release->type) == 'actualizacion' ? 'selected' : '' }}>🔄 Actualización</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="published_at" class="form-label text-dark fw-bold">Programar cambio</label>
                                        <div class="input-group input-group-merge border-2 rounded">
                                            <span class="input-group-prepend input-group-text"><i class="bi-calendar-event"></i></span>
                                            <input type="text" name="published_at" id="published_at" class="form-control js-flatpickr flatpickr-custom" placeholder="Seleccionar nueva fecha" value="{{ old('published_at', $release->published_at ? $release->published_at->format('Y-m-d H:i') : '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="status" class="form-label text-dark fw-bold">Estado</label>
                                        <select name="status" id="status" class="form-select border-2">
                                            <option value="published" {{ old('status', $release->status) == 'published' ? 'selected' : '' }}>Publicado (Visible)</option>
                                            <option value="draft" {{ old('status', $release->status) == 'draft' ? 'selected' : '' }}>Borrador (Oculto)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="document" class="form-label text-dark fw-bold">Adjunto</label>
                                    @if($release->document_path)
                                        <div class="mb-2">
                                            <span class="badge bg-soft-info text-info"><i class="bi-file-earmark-check me-1"></i> Archivo guardado: {{ basename($release->document_path) }}</span>
                                        </div>
                                    @endif
                                    <div class="input-group input-group-merge border-2 rounded">
                                        <span class="input-group-prepend input-group-text"><i class="bi-paperclip"></i></span>
                                        <input type="file" name="document" id="document" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('releases.index') }}" class="btn btn-white">Atrás</a>
                                    <button type="submit" class="btn btn-primary px-4" id="saveBtn">
                                        <i class="bi-save me-1"></i> Actualizar Comunicado
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
    <link rel="stylesheet" href="{{ asset('vendor/quill/dist/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/dist/flatpickr.min.css') }}">
    
    <script src="{{ asset('vendor/quill/dist/quill.min.js') }}"></script>
    <script src="{{ asset('vendor/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendor/flatpickr/dist/l10n/es.js') }}"></script>

    <script>
        $(document).ready(function() {
            flatpickr("#published_at", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                locale: "es",
                disableMobile: "true"
            });

            var quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['link', 'blockquote'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['clean']
                    ]
                }
            });

            $('#releaseForm').on('submit', function() {
                var html = quill.root.innerHTML;
                if (html === '<p><br></p>') html = ''; 
                $('#content_hidden').val(html);
            });
        });
    </script>
@endpush
