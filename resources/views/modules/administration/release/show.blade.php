@extends('layouts.panel')
@section('title', $release->title)
@section('styles')
    <style>
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

        .a4-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
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
            width: 100%;
            max-width: 816px;
        }

        @media (max-width: 816px) {
            .a4-paper {
                background-size: 100% 1056px;
                background-image:
                    linear-gradient(to bottom, transparent 1052px, #cbd5e1 1052px, #cbd5e1 1056px),
                    url('{{ asset('dist/doc/Oficio-Institucional.jpg') }}');
            }
        }

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
                max-width: 100% !important;
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
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a></li>
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('releases.index') }}">Comunicados</a></li>
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
                                <div class="ql-editor doc-body">{!! $release->content !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
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
                                    <span class="fw-semibold text-dark">{{ $release->type === 'actualizacion' ? 'Actualización' : 'Comunicado' }}</span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <span class="text-muted">Autor</span>
                                    <span class="fw-semibold text-dark">{{ $release->author->first_name ?? 'Sistema' }} {{ $release->author->first_last_name ?? '' }}</span>
                                </li>
                                @if ($release->author && $release->author->workDepartment)
                                    <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                                        <span class="text-muted">Departamento</span>
                                        <span class="fw-semibold text-dark">{{ $release->author->workDepartment->name }}</span>
                                    </li>
                                @endif
                                <li class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <span class="text-muted">Publicado en</span>
                                    <span class="fw-semibold text-dark">{{ ($release->published_at ?? $release->created_at)->format('d M Y, H:i') }}</span>
                                </li>
                                <li class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted">Última edición</span>
                                    <span class="fw-semibold text-dark">{{ $release->updated_at->diffForHumans() }}</span>
                                </li>
                            </ul>
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
                        <div class="card mb-3 mb-lg-4">
                            <div class="card-header border-bottom-0 pb-0">
                                <h4 class="card-header-title"><i class="bi-paperclip me-1"></i> Archivos Adjuntos</h4>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-3">
                                    @foreach ($documents as $doc)
                                        @php $ext = strtoupper(pathinfo($doc, PATHINFO_EXTENSION)); @endphp
                                        <a class="card card-sm card-hover-shadow border text-decoration-none" href="{{ asset('storage/' . $doc) }}" target="_blank">
                                            <div class="card-body d-flex align-items-center p-3">
                                                <i class="bi-file-earmark-arrow-down fs-2 text-primary me-3"></i>
                                                <div class="text-truncate">
                                                    <span class="d-block text-dark text-truncate fw-semibold">{{ basename($doc) }}</span>
                                                    <small class="text-muted">{{ $ext }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

                const paper = document.querySelector('.a4-paper');
                if (paper) {
                    const requiredPages = Math.ceil(Math.max(1, maxBottom) / pageHeight);
                    const finalHeight = requiredPages * pageHeight;

                    paper.style.height = finalHeight + 'px';
                    editorNode.style.height = finalHeight + 'px';
                }
            }

            setTimeout(applyPagination, 150);
            window.addEventListener('load', applyPagination);
        });
    </script>
@endpush