<style>
    /* Estilos adaptativos para Modo Claro/Oscuro */
    .swal-dynamic-popup {
        background-color: var(--bs-body-bg, #ffffff) !important;
        color: var(--bs-body-color, #212529) !important;
        border: 1px solid var(--bs-border-color, rgba(0, 0, 0, 0.1)) !important;
        border-radius: 12px !important;
    }

    .swal-dynamic-title {
        color: var(--bs-body-color, #212529) !important;
    }

    .swal-dynamic-text {
        color: var(--bs-secondary-color, #6c757d) !important;
    }
</style>

<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script>
    document.querySelectorAll('form.requires-confirmation').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            let msg = form.getAttribute('data-message') || '¿Confirmar acción?';
            let isRestore = msg.toLowerCase().includes('reactivar');

            // 1. PREGUNTA INICIAL
            Swal.fire({
                title: isRestore ? '¿Reactivar registro?' : '¿Desactivar registro?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'swal-dynamic-popup shadow-lg',
                    title: 'swal-dynamic-title',
                    htmlContainer: 'swal-dynamic-text',
                    confirmButton: isRestore ? 'btn btn-success ms-2' : 'btn btn-danger ms-2',
                    cancelButton: 'btn btn-white'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // 2. LOADER MANUAL (Inicio de la petición)
                    if (window.showManualLoader) window.showManualLoader("Procesando solicitud...");

                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (window.hideGlobalLoader) window.hideGlobalLoader();

                            if (response.ok) {
                                // 3. ÉXITO REAL (Solo si el servidor confirma)
                                Swal.fire({
                                    title: "¡Logrado!",
                                    text: "La operación se completó con éxito.",
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'swal-dynamic-popup shadow-lg',
                                        title: 'swal-dynamic-title',
                                        htmlContainer: 'swal-dynamic-text'
                                    }
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error('Error en el servidor');
                            }
                        })
                        .catch(error => {
                            if (window.hideGlobalLoader) window.hideGlobalLoader();

                            // 4. ERROR REAL (Si algo falla)
                            Swal.fire({
                                title: "¡Hubo un problema!",
                                text: "No pudimos procesar la solicitud en este momento.",
                                icon: "error",
                                confirmButtonText: "Entendido",
                                customClass: {
                                    popup: 'swal-dynamic-popup shadow-lg',
                                    title: 'swal-dynamic-title',
                                    htmlContainer: 'swal-dynamic-text',
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        });
                }
            });
        });
    });
</script>