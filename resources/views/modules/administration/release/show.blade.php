@extends('layouts.panel')
@section('title', $release->title)

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/quill/dist/quill.snow.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        /* ─── Custom Fonts ─── */
        @font-face {
            font-family: 'Altivo';
            src: url('{{ asset('Fonts/35170.otf') }}') format('opentype');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Altivo';
            src: url('{{ asset('Fonts/35174.otf') }}') format('opentype');
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
                url('{{ asset('doc/Oficio-Institucional.jpg') }}');
            background-size: 100% 1056px;
            background-position: top center;
            background-repeat: repeat-y;
            width: 816px !important;
            min-width: 816px;
        }

        @media (max-width: 816px) {
            .a4-paper {
                background-size: 100% 1056px;
                background-image:
                    linear-gradient(to bottom, transparent 1052px, #cbd5e1 1052px, #cbd5e1 1056px),
                    url('{{ asset('doc/Oficio-Institucional.jpg') }}');
            }
        }

        /* ─── Content Formatting ─── */
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
            /* Evita scroll interno */
            height: auto !important;
            min-height: 1056px;
        }

        @media print {
            body {
                background: transparent !important;
            }

            .a4-paper {
                box-shadow: none !important;
                border: none !important;
            }

            .col-lg-4,
            .page-header {
                display: none !important;
            }

            .col-lg-8 {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .a4-wrapper {
                max-width: none !important;
                margin: 0 !important;
            }

            @page {
                margin: 0;
            }
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
                                <li class="breadcrumb-item active" aria-current="page">Ver Documento</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">{{ $release->title }}</h1>
                    </div>

                    <div class="col-auto">
                        <a href="{{ route('releases.index') }}" class="btn btn-primary">
                            <i class="bi-arrow-left me-1"></i> Regresar
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="a4-wrapper mb-4">
                        <div class="a4-paper">
                            <div class="ql-container ql-snow">
                                <div class="ql-editor doc-body">
                                    {!! $release->content !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $documents = is_string($release->document_path)
                            ? json_decode($release->document_path, true)
                            : $release->document_path;
                        if (!is_array($documents) && !empty($release->document_path)) {
                            $documents = [$release->document_path];
                        }
                    @endphp

                    @if (!empty($documents) && count($documents) > 0)
                        <div class="card">
                            <div class="card-header border-bottom-0 pt-4 pb-0">
                                <h4 class="card-header-title"><i class="bi-paperclip me-1"></i> Archivos Adjuntos</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($documents as $doc)
                                        @php $ext = strtoupper(pathinfo($doc, PATHINFO_EXTENSION)); @endphp
                                        <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                                            <a class="card card-sm card-hover-shadow h-100 border text-decoration-none"
                                                href="{{ asset('storage/' . $doc) }}" target="_blank">
                                                <div class="card-body d-flex align-items-center">
                                                    <i class="bi-file-earmark-text fs-2 text-primary me-2"></i>
                                                    <div class="text-truncate">
                                                        <span
                                                            class="d-block text-dark text-truncate fw-semibold">{{ basename($doc) }}</span>
                                                        <small class="text-muted">{{ $ext }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">

                    <div class="card mb-3 mb-lg-4">
                        <div class="card-header border-bottom-0 pb-0">
                            <h4 class="card-header-title"><i class="bi-book me-1"></i> Progreso de Lectura</h4>
                        </div>
                        <div class="card-body py-3">
                            <div class="row text-center">
                                <div class="col">
                                    <span class="d-block h3 text-dark mb-0" id="statWords">—</span>
                                    <span class="text-muted small text-uppercase">Palabras</span>
                                </div>
                                <div class="col border-start">
                                    <span class="d-block h3 text-dark mb-0" id="statRead">—</span>
                                    <span class="text-muted small text-uppercase">Minutos</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 mb-lg-4">
                        <div class="card-header border-bottom-0 pb-0">
                            <h4 class="card-header-title"><i class="bi-info-circle me-1"></i> Información</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-py-2 mb-0">
                                <li class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted">Estado</span>
                                    @if ($release->status === 'published')
                                        <span class="badge bg-soft-success text-success">Publicado</span>
                                    @else
                                        <span class="badge bg-soft-warning text-warning">Borrador</span>
                                    @endif
                                </li>
                                <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <span class="text-muted">Tipo</span>
                                    <span
                                        class="fw-semibold text-dark">{{ $release->type === 'actualizacion' ? 'Actualización' : 'Comunicado' }}</span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <span class="text-muted">Autor</span>
                                    <span class="fw-semibold text-dark">{{ $release->author->first_name ?? 'Sistema' }}
                                        {{ $release->author->first_last_name ?? '' }}</span>
                                </li>
                                @if ($release->author && $release->author->workDepartment)
                                    <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                                        <span class="text-muted">Departamento</span>
                                        <span
                                            class="fw-semibold text-dark">{{ $release->author->workDepartment->name }}</span>
                                    </li>
                                @endif
                                <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <span class="text-muted">Publicado en</span>
                                    <span
                                        class="fw-semibold text-dark">{{ ($release->published_at ?? $release->created_at)->format('d M Y, H:i') }}</span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Última edición</span>
                                    <span class="fw-semibold text-dark">{{ $release->updated_at->diffForHumans() }}</span>
                                </li>
                                @if (!empty($documents) && count($documents) > 0)
                                    <li class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="text-muted">Adjuntos</span>
                                        <span class="fw-semibold text-dark">{{ count($documents) }} archivo(s)</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header border-bottom-0 pb-0">
                            <h4 class="card-header-title"><i class="bi-lightning me-1"></i> Acciones</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('releases.edit', $release->id) }}" class="btn btn-primary">
                                    <i class="bi-pencil me-1"></i> Editar documento
                                </a>
                                <button onclick="window.print()" class="btn btn-white border">
                                    <i class="bi-printer text-muted me-1"></i> Imprimir / PDF
                                </button>
                                <a href="{{ route('releases.index') }}" class="btn btn-white border text-secondary">
                                    <i class="bi-arrow-left me-1"></i> Volver al índice
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── Word count & reading time ──
            const bodyEl = document.querySelector('.doc-body');
            const text = bodyEl ? bodyEl.innerText.trim() : '';
            const words = text.length ? text.split(/\s+/).filter(Boolean).length : 0;
            const readMin = Math.max(1, Math.ceil(words / 200));

            document.getElementById('statWords').textContent = words.toLocaleString();
            document.getElementById('statRead').textContent = readMin;

            // ── Motor de Paginación de Solo Lectura ──
            function applyPagination() {
                const pageHeight = 1056;
                const topMargin = 132;
                const bottomMargin = 95;

                const editorNode = document.querySelector('.doc-body');
                if (!editorNode) return;

                const blocks = Array.from(editorNode.children);
                let maxBottom = 0;

                for (let i = 0; i < blocks.length; i++) {
                    const block = blocks[i];

                    // Limpiamos cualquier margen previo que viniera quemado en el HTML
                    block.style.marginTop = '0px';

                    const blockTop = block.offsetTop;
                    const blockHeight = block.offsetHeight;
                    let blockBottom = blockTop + blockHeight;

                    const currentPage = Math.floor(blockTop / pageHeight);
                    const pageBottomLimit = ((currentPage + 1) * pageHeight) - bottomMargin;

                    if (blockBottom > pageBottomLimit) {
                        const nextPageStart = ((currentPage + 1) * pageHeight) + topMargin;
                        const pushAmount = Math.max(0, nextPageStart - blockTop);
                        block.style.marginTop = pushAmount + 'px';

                        blockBottom = block.offsetTop + block.offsetHeight;
                    }

                    if (blockBottom > maxBottom) {
                        maxBottom = blockBottom;
                    }
                }

                // Forzar aspecto final de la hoja
                const paper = document.querySelector('.a4-paper');
                if (paper) {
                    const requiredPages = Math.ceil(Math.max(1, maxBottom) / pageHeight);
                    const finalHeight = requiredPages * pageHeight;

                    paper.style.height = finalHeight + 'px';
                    editorNode.style.height = finalHeight + 'px';
                }
            }

            // Ejecutar paginación. Un pequeño retraso asegura que las fuentes (y sobre todo las imágenes) carguen sus tamaños
            setTimeout(applyPagination, 150);
            window.addEventListener('load', applyPagination);
        });
    </script>
@endpush
