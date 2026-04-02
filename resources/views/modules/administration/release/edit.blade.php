@extends('layouts.panel')
@section('title', 'Editar Comunicado')

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <style>
        /* ─── Custom Fonts ─── */
        @font-face {
            font-family: 'Altivo';
            src: url('{{ asset('dist/Fonts/35170.otf') }}') format('opentype');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Altivo';
            src: url('{{ asset('dist/Fonts/35174.otf') }}') format('opentype');
            font-weight: 700;
            font-style: normal;
        }

        /* ─── A4 Paper Settings ─── */
        .a4-wrapper {
            width: 100%;
            max-width: 816px;
            margin: 0 auto;
            overflow-x: auto;
        }

        .a4-paper {
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(15, 25, 35, .08);
            border: 1px solid #e2e8f0;
            background-image:
                linear-gradient(to bottom, transparent 1052px, #cbd5e1 1052px, #cbd5e1 1056px),
                url('{{ asset('dist/doc/Oficio-Institucional.jpg') }}');
            background-size: 100% 1056px;
            background-position: top center;
            background-repeat: repeat-y;
            width: 816px !important;
            min-width: 816px;
            /* Se elimina min-height fijo aquí, lo controla el JS */
        }

        @media (max-width: 816px) {
            .a4-paper {
                background-size: 100% 1056px;
                background-image:
                    linear-gradient(to bottom, transparent 1052px, #cbd5e1 1052px, #cbd5e1 1056px),
                    url('{{ asset('dist/doc/Oficio-Institucional.jpg') }}');
            }
        }

        #toolbar-container {
            font-family: 'DM Sans', sans-serif;
            border: 1px solid #e2e8f0;
            border-radius: .5rem;
            background: #fff;
            margin-bottom: 1rem;
            position: sticky;
            top: 10px;
            z-index: 100;
        }

        /* CORRECCIÓN DE QUILL SCROLL */
        .ql-container.ql-snow {
            border: none !important;
            height: auto !important;
        }

        .ql-editor {
            font-family: 'Altivo', sans-serif !important;
            font-size: 1rem !important;
            line-height: 1.8 !important;
            color: #0f1923 !important;
            padding-top: 3.5cm !important;
            padding-bottom: 2.5cm !important;
            padding-left: 2.5cm !important;
            padding-right: 2.5cm !important;
            overflow-y: hidden !important;
            /* APAGAMOS EL SCROLL INTERNO DE QUILL */
            height: auto !important;
            min-height: 1056px;
        }

        .ql-editor.ql-blank::before {
            color: #9aa5b4 !important;
            font-style: normal !important;
        }

        /* Toggles Custom Styles */
        .type-opt,
        .status-opt {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            background-color: #f8fafc;
            color: #64748b;
        }

        .type-opt:hover,
        .status-opt:hover {
            background-color: #e2e8f0;
            color: #334155;
        }

        .type-opt.active {
            background-color: #0d6efd !important;
            color: #ffffff !important;
            border-color: #0d6efd !important;
        }

        .active-pub {
            background-color: #198754 !important;
            color: #ffffff !important;
            border-color: #198754 !important;
        }

        .active-dra {
            background-color: #ffc107 !important;
            color: #000000 !important;
            border-color: #ffc107 !important;
        }
    </style>
@endsection

@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid py-4">

            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a>
                                </li>
                                <li class="breadcrumb-item"><a class="breadcrumb-link"
                                        href="{{ route('releases.index') }}">Comunicados</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">Editar <span id="typeBadgeDisplay"
                                class="badge bg-soft-primary text-primary ms-2">{{ ucfirst($release->type) }}</span></h1>
                    </div>
                </div>
            </div>

            <form action="{{ route('releases.update', $release->id) }}" method="POST" enctype="multipart/form-data"
                id="releaseForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="content" id="content_hidden">
                <input type="hidden" name="type" id="type_hidden" value="{{ old('type', $release->type) }}">
                <input type="hidden" name="status" id="status_hidden" value="{{ old('status', $release->status) }}">

                <div class="row">
                    <div class="col-lg-8 mb-4 mb-lg-0">

                        <div class="card mb-3 mb-lg-4">
                            <div class="card-body">
                                <label class="form-label">Título del Documento</label>
                                <input type="text" name="title" class="form-control form-control-lg"
                                    value="{{ old('title', $release->title) }}" required>
                            </div>
                        </div>

                        <div class="card mb-3 mb-lg-4">
                            <div class="card-body bg-light rounded-bottom">
                                <div id="toolbar-container">
                                    <span class="ql-formats">
                                        <button class="ql-bold"></button>
                                        <button class="ql-italic"></button>
                                        <button class="ql-underline"></button>
                                    </span>
                                    <span class="ql-formats">
                                        <button class="ql-header" value="1"></button>
                                        <button class="ql-header" value="2"></button>
                                        <button class="ql-list" value="ordered"></button>
                                        <button class="ql-list" value="bullet"></button>
                                    </span>
                                    <span class="ql-formats">
                                        <select class="ql-align"></select>
                                    </span>
                                    <span class="ql-formats">
                                        <button class="ql-image"></button>
                                    </span>
                                </div>

                                <div class="a4-wrapper">
                                    <div class="a4-paper">
                                        <div id="editor">{!! old('content', $release->content) !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">

                        <div class="card mb-3 mb-lg-4">
                            <div class="card-header border-bottom-0 pb-0">
                                <h4 class="card-header-title"><i class="bi-bar-chart-line me-1"></i> Estadísticas</h4>
                            </div>
                            <div class="card-body py-3">
                                <div class="row text-center mb-3">
                                    <div class="col border-end">
                                        <span class="d-block h3 text-dark mb-0" id="stat-words">0</span>
                                        <span class="text-muted small text-uppercase">Palabras</span>
                                    </div>
                                    <div class="col">
                                        <span class="d-block h3 text-dark mb-0" id="stat-read">0m</span>
                                        <span class="text-muted small text-uppercase">Lectura</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header border-bottom-0 pb-0">
                                <h4 class="card-header-title">Asistente de Redacción</h4>
                            </div>
                            <div class="card-body">
                                <textarea id="ai_prompt" class="form-control mb-2" rows="3" placeholder="¿Qué necesitas redactar?"></textarea>
                                <button type="button" class="btn btn-primary w-100" id="btnGenerateAi"
                                    style="font-weight: 600;">
                                    <span id="aiContentSpinner" class="spinner-border spinner-border-sm d-none"
                                        role="status"></span>
                                    <span id="aiGenerateTxt">Generar con IA</span>
                                </button>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label text-muted text-uppercase fw-bold"
                                        style="font-size: .75rem;">Clasificación</label>
                                    <div class="d-flex gap-2">
                                        <div class="btn border type-opt w-100 {{ old('type', $release->type) == 'comunicado' ? 'active' : '' }}"
                                            data-val="comunicado" onclick="setType(this)">Comunicado</div>
                                        <div class="btn border type-opt w-100 {{ old('type', $release->type) == 'actualizacion' ? 'active' : '' }}"
                                            data-val="actualizacion" onclick="setType(this)">Actualización</div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-muted text-uppercase fw-bold"
                                        style="font-size: .75rem;">Visibilidad</label>
                                    <div class="d-flex gap-2">
                                        <div class="btn border status-opt w-100 {{ old('status', $release->status) == 'published' ? 'active-pub' : '' }}"
                                            data-val="published" onclick="setStatus(this)">Público</div>
                                        <div class="btn border status-opt w-100 {{ old('status', $release->status) == 'draft' ? 'active-dra' : '' }}"
                                            data-val="draft" onclick="setStatus(this)">Borrador</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label text-muted text-uppercase fw-bold">Archivos Adjuntos</label>
                                    <label for="documents"
                                        class="d-flex flex-column align-items-center justify-content-center border-dashed rounded p-3 text-center"
                                        style="cursor: pointer; border: 2px dashed #cbd5e1; background: #f8fafc; transition: all 0.2s;">
                                        <i class="bi-cloud-arrow-up fs-3 text-primary mb-2"></i>
                                        <span class="fw-semibold text-dark">Haz clic para subir archivos nuevos</span>
                                        <small class="text-muted mt-1">PDF, DOC, DOCX, JPG, PNG (Max 5MB)</small>
                                    </label>
                                    <input type="file" name="documents[]" id="documents" class="d-none" multiple>
                                    <div id="file-chosen" class="mt-2 text-primary fw-medium small text-center"></div>
                                </div>

                                @if ($release->document_path && is_array($release->document_path) && count($release->document_path))
                                    <div class="mt-3 pt-3 border-top">
                                        <label class="form-label text-muted text-uppercase fw-bold mb-2"
                                            style="font-size: .75rem;">Archivos Actuales</label>
                                        @foreach ($release->document_path as $doc)
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi-file-earmark-check text-primary me-2"></i>
                                                <a href="{{ Storage::url($doc) }}" target="_blank"
                                                    class="text-decoration-none"
                                                    style="font-size:.85rem; word-break: break-all;">{{ basename($doc) }}</a>
                                            </div>
                                        @endforeach
                                        <div class="alert alert-soft-warning mt-2 mb-0 py-2" style="font-size: .75rem;">
                                            ⚠️ Si subes archivos nuevos, reemplazarás totalmente los actuales.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary w-100" id="saveBtn">Actualizar
                                    Documento</button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var quill = new Quill('#editor', {
                theme: 'snow',
                scrollingContainer: 'html',
                modules: {
                    toolbar: '#toolbar-container'
                }
            });

            // ── Motor de Paginación Dinámica ──
            function paginateQuill() {
                const pageHeight = 1056;
                const topMargin = 132;
                const bottomMargin = 95;

                const editorNode = document.querySelector('.ql-editor');
                if (!editorNode) return;

                const blocks = Array.from(editorNode.children);
                blocks.forEach(b => b.style.marginTop = '0px');

                let maxBottom = 0; // Guardará el punto más bajo alcanzado por el contenido

                for (let i = 0; i < blocks.length; i++) {
                    const block = blocks[i];
                    if (block.classList.contains('ql-clipboard')) continue;

                    const blockTop = block.offsetTop;
                    const blockHeight = block.offsetHeight;
                    let blockBottom = blockTop + blockHeight;

                    const currentPage = Math.floor(blockTop / pageHeight);
                    const pageBottomLimit = ((currentPage + 1) * pageHeight) - bottomMargin;

                    // Si el bloque sobrepasa el límite inferior de la página actual
                    if (blockBottom > pageBottomLimit) {
                        const nextPageStart = ((currentPage + 1) * pageHeight) + topMargin;
                        const pushAmount = Math.max(0, nextPageStart - blockTop);
                        block.style.marginTop = pushAmount + 'px';

                        // Recalcular el nuevo fondo del bloque tras haberlo empujado
                        blockBottom = block.offsetTop + block.offsetHeight;
                    }

                    // Registrar el punto más bajo del documento
                    if (blockBottom > maxBottom) {
                        maxBottom = blockBottom;
                    }
                }

                // Forzar la altura exacta de la hoja en múltiplos de 1056px
                const paper = document.querySelector('.a4-paper');
                if (paper) {
                    // Calculamos cuántas páginas enteras necesitamos para cubrir 'maxBottom'
                    const requiredPages = Math.ceil(Math.max(1, maxBottom) / pageHeight);
                    const finalHeight = requiredPages * pageHeight;

                    // Aplicamos la altura exacta a ambos contenedores para evitar scroll
                    paper.style.height = finalHeight + 'px';
                    editorNode.style.height = finalHeight + 'px';
                }
            }

            // ── Estadísticas ──
            function updateStats() {
                const text = quill.getText().trim();
                const words = text.length ? text.split(/\s+/).filter(Boolean).length : 0;
                const readMin = Math.max(1, Math.ceil(words / 200));
                document.getElementById('stat-words').textContent = words;
                document.getElementById('stat-read').textContent = readMin + 'm';
            }

            let paginationTimer;
            quill.on('text-change', function() {
                clearTimeout(paginationTimer);
                paginationTimer = setTimeout(paginateQuill, 50);
                updateStats(); // Actualizar stats al escribir
            });

            // Inicializar al cargar la página (vital para edit)
            setTimeout(function() {
                paginateQuill();
                updateStats(); // Contar las palabras del texto que vino de BD
            }, 100);

            window.addEventListener('load', paginateQuill);

            // ── Eventos UI ──
            const form = document.getElementById('releaseForm');
            const saveBtn = document.getElementById('saveBtn');

            form.onsubmit = function() {
                document.getElementById('content_hidden').value = quill.root.innerHTML;
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';
            };

            window.setStatus = function(el) {
                document.querySelectorAll('.status-opt').forEach(o => o.classList.remove('active-pub',
                    'active-dra'));
                const val = el.dataset.val;
                el.classList.add(val === 'published' ? 'active-pub' : 'active-dra');
                document.getElementById('status_hidden').value = val;
            };

            window.setType = function(el) {
                document.querySelectorAll('.type-opt').forEach(o => o.classList.remove('active'));
                el.classList.add('active');
                const val = el.dataset.val;
                const labels = {
                    comunicado: 'Comunicado',
                    actualizacion: 'Actualización'
                };
                document.getElementById('type_hidden').value = val;
                document.getElementById('typeBadgeDisplay').textContent = labels[val] || val;
            };

            document.getElementById('documents').addEventListener('change', function() {
                const el = document.getElementById('file-chosen');
                if (this.files && this.files.length > 0) {
                    el.textContent = this.files.length === 1 ?
                        '✓ ' + this.files[0].name :
                        `✓ ${this.files.length} archivos seleccionados`;
                } else {
                    el.textContent = '';
                }
            });

            // ── AI Generation ──
            const btnGenerate = document.getElementById('btnGenerateAi');
            const aiSpinner = document.getElementById('aiContentSpinner');
            const aiTxt = document.getElementById('aiGenerateTxt');

            if (btnGenerate) {
                btnGenerate.addEventListener('click', function() {
                    const prompt = document.getElementById('ai_prompt').value.trim();
                    if (!prompt) {
                        alert('Por favor ingresa las instrucciones para la IA.');
                        return;
                    }
                    btnGenerate.disabled = true;
                    if (aiSpinner) aiSpinner.classList.remove('d-none');
                    if (aiTxt) aiTxt.textContent = 'Generando...';

                    fetch('{{ route('releases.generate-ai') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                prompt
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            btnGenerate.disabled = false;
                            if (aiSpinner) aiSpinner.classList.add('d-none');
                            if (aiTxt) aiTxt.textContent = 'Generar con IA';

                            if (data.success) {
                                quill.clipboard.dangerouslyPasteHTML(data.html);
                                if (data.title && document.getElementById('title')) document
                                    .getElementById('title').value = data.title;
                                updateStats();
                                setTimeout(paginateQuill, 100);
                            } else {
                                alert('Error: ' + (data.message || 'Inténtalo de nuevo.'));
                            }
                        })
                        .catch(() => {
                            btnGenerate.disabled = false;
                            if (aiSpinner) aiSpinner.classList.add('d-none');
                            if (aiTxt) aiTxt.textContent = 'Generar con IA';
                            alert('Error de red al conectar con la IA.');
                        });
                });
            }
        });
    </script>
@endpush
