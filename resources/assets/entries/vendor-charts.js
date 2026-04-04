/**
 * Vendor Charts Bundle — HOSPROGRESO
 * Chunk 3/3: Charting library
 *
 * Contenido: Chart.js
 * Peso estimado: ~500 kB minificado
 * Carga: Solo panel.blade.php (dashboard, módulos con gráficas)
 *        NO se carga en form.blade.php (login, formularios públicos)
 *
 * DEPENDENCIA: vendor-core.min.js debe cargarse ANTES que este chunk.
 * Al aislarlo aquí, el navegador puede cachear este chunk de forma
 * independiente y solo lo descarga una vez, reutilizándolo en todas
 * las páginas del panel sin re-descarga.
 */

// ── Chart.js ──────────────────────────────────────────────────
// Biblioteca de gráficas. Es el asset más pesado del vendor bundle.
// Al aislarlo en su propio chunk, se convierte en un recurso cacheable
// independiente del resto del vendor.
import '../vendor/chart.js/dist/chart.min.js';
