@extends('layouts.panel')
@section('title', $release->title)

@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header border-bottom mb-5 pb-5">
                <div class="row align-items-center">
                    <div class="col">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a>
                                </li>
                                <li class="breadcrumb-item"><a class="breadcrumb-link"
                                        href="{{ route('releases.index') }}">Comunicados</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Ver Detalle</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title display-4 mt-2">{{ $release->title }}</h1>

                        <div class="d-flex align-items-center mt-3">
                            <div class="avatar avatar-circle me-3">
                                <span
                                    class="avatar-initials bg-soft-primary text-primary">{{ substr($release->author->first_name, 0, 1) }}{{ substr($release->author->first_last_name, 0, 1) }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0">{{ $release->author->first_name }}
                                    {{ $release->author->first_last_name }}</h5>
                                <span class="text-muted fs-5">Publicado el
                                    {{ $release->published_at ? $release->published_at->format('d \d\e M, Y - H:i') : $release->created_at->format('d \d\e M, Y - H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-auto">
                        <a href="{{ route('releases.edit', $release->id) }}" class="btn btn-white">
                            <i class="bi-pencil me-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card card-lg shadow-sm">
                        <div class="card-body newsletter-content">
                            {!! $release->content !!}

                            @php
                                // Decodificamos asumiendo que el controlador guardó un JSON array en document_path
                                $documents = is_string($release->document_path)
                                    ? json_decode($release->document_path, true)
                                    : $release->document_path;
                                // Si es un string simple antiguo, lo forzamos a array
                                if (!is_array($documents) && !empty($release->document_path)) {
                                    $documents = [$release->document_path];
                                }
                            @endphp

                            @if (!empty($documents) && count($documents) > 0)
                                <div class="mt-5 pt-4 border-top">
                                    <h5 class="text-cap text-muted mb-3"><i class="bi-paperclip me-1"></i> Archivos Adjuntos
                                    </h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($documents as $doc)
                                            <a href="{{ asset('storage/' . $doc) }}" target="_blank"
                                                class="btn btn-soft-secondary btn-sm rounded-pill">
                                                <i class="bi-download me-1"></i> {{ basename($doc) }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('releases.index') }}" class="btn btn-link text-muted">
                            <i class="bi-arrow-left me-1"></i> Volver al listado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .newsletter-content {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #333;
        }

        .newsletter-content h1,
        .newsletter-content h2,
        .newsletter-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #111;
        }

        .newsletter-content p {
            margin-bottom: 1.5rem;
        }

        .newsletter-content ul,
        .newsletter-content ol {
            margin-bottom: 1.5rem;
        }

        /* SOLUCIÓN IMÁGENES QUILL: Las hace responsivas automáticamente */
        .newsletter-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            /* Bordes suaves para mejor estética */
            margin: 1rem 0;
            object-fit: contain;
        }

        .ql-align-center {
            text-align: center;
        }

        .ql-align-right {
            text-align: right;
        }

        .ql-align-justify {
            text-align: justify;
        }
    </style>
@endsection
