/**
 * Vendor Core Bundle — HOSPROGRESO
 * Chunk 1/3: Foundation libraries
 *
 * Contenido: jQuery + Bootstrap
 * Peso estimado: ~180 kB minificado
 * Carga: TODAS las páginas (panel.blade.php + form.blade.php)
 *
 * NOTA: Debe cargarse ANTES de vendor-ui.min.js y vendor-charts.min.js
 */

// ── 1. jQuery ──────────────────────────────────────────────────
// Expone window.$ y window.jQuery globalmente
import '../vendor/jquery/dist/jquery.min.js';

// ── 2. Bootstrap Bundle ────────────────────────────────────────
// Incluye Popper.js. Expone window.bootstrap globalmente.
import '../vendor/bootstrap/dist/js/bootstrap.bundle.min.js';
