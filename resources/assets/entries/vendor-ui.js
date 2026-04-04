/**
 * Vendor UI Bundle — HOSPROGRESO
 * Chunk 2/3: UI components & HS plugins
 *
 * Contenido: Appear + HS Plugins + Tom Select + IMask + Quill
 * Peso estimado: ~350 kB minificado
 * Carga: TODAS las páginas (panel.blade.php + form.blade.php)
 *
 * DEPENDENCIA: vendor-core.min.js debe cargarse ANTES que este chunk.
 */

// ── 1. Appear ─────────────────────────────────────────────────
// Detecta cuando elementos entran al viewport (scroll animations)
import '../vendor/appear/dist/appear.min.js';

// ── 2. HS Navbar Vertical Aside ────────────────────────────────
// Sidebar navigation plugin del tema HtmlStream
import '../vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside.min.js';

// ── 3. HS Form Search ─────────────────────────────────────────
import '../vendor/hs-form-search/dist/hs-form-search.min.js';

// ── 4. HS Counter ─────────────────────────────────────────────
import '../vendor/hs-counter/dist/hs-counter.min.js';

// ── 5. HS Toggle Password ─────────────────────────────────────
import '../vendor/hs-toggle-password/dist/js/hs-toggle-password.js';

// ── 6. HS File Attach ─────────────────────────────────────────
import '../vendor/hs-file-attach/dist/hs-file-attach.min.js';

// ── 7. HS Nav Scroller ────────────────────────────────────────
import '../vendor/hs-nav-scroller/dist/hs-nav-scroller.min.js';

// ── 8. HS Step Form ───────────────────────────────────────────
import '../vendor/hs-step-form/dist/hs-step-form.min.js';

// ── 9. HS Sticky Block ────────────────────────────────────────
import '../vendor/hs-sticky-block/dist/hs-sticky-block.min.js';

// ── 10. HS Add Field ──────────────────────────────────────────
import '../vendor/hs-add-field/dist/hs-add-field.min.js';

// ── 11. Tom Select ────────────────────────────────────────────
// Reemplaza los <select> nativos con versión avanzada con búsqueda
import '../vendor/tom-select/dist/js/tom-select.complete.min.js';

// ── 12. IMask ─────────────────────────────────────────────────
// Máscaras de input (teléfonos, fechas, DNI, etc.)
import '../vendor/imask/dist/imask.min.js';

// ── 13. Quill ─────────────────────────────────────────────────
// Editor de texto enriquecido (WYSIWYG)
import '../vendor/quill/dist/quill.min.js';
