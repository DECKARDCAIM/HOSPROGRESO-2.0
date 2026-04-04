/**
 * scripts/split-vendors.mjs
 * ─────────────────────────────────────────────────────────────
 * Script postbuild: IIFE Vendor Chunking
 *
 * Ejecutado automáticamente por npm como "postbuild" hook.
 * Corre DESPUÉS de `vite build`, cuando public/dist/ ya existe.
 *
 * Estrategia:
 *   En lugar de batallar con las restricciones de Rollup (format:iife
 *   es incompatible con múltiples inputs en un solo build), este script
 *   usa la API JS de Rollup directamente para lanzar 3 builds IIFE
 *   independientes y escribir los resultados en public/dist/js/.
 *
 * Resultado:
 *   public/dist/js/vendor-core.min.js   → jQuery + Bootstrap
 *   public/dist/js/vendor-ui.min.js     → HS Plugins + UI libs
 *   public/dist/js/vendor-charts.min.js → Chart.js
 *
 * Los layouts Blade cargan estos 3 archivos en lugar del antiguo
 * vendor.min.js de 1MB.
 *
 * Para añadir chunks en el futuro:
 *   1. Crear resources/assets/entries/vendor-NOMBRE.js
 *   2. Agregar una entrada al array CHUNKS debajo
 *   3. Agregar el <script> correspondiente en los layouts Blade
 *   4. Agregar las librerías a USED_VENDOR_DIRS en vite.config.js
 */

import { rollup } from 'rollup';
import { minify as terserMinify } from 'terser';
import path from 'path';
import fs from 'fs';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT      = path.resolve(__dirname, '..');
const OUT_JS    = path.resolve(ROOT, 'public/dist/js');

// ── Chunk definitions ─────────────────────────────────────────
// Cada chunk define su entry point y el nombre IIFE global.
// El footer de vendor-core limpia residuos CJS del scope IIFE.
// El footer de vendor-ui expone `appear` al window (fix legacy).
const CHUNKS = [
  {
    entry:  path.resolve(ROOT, 'resources/assets/entries/vendor-core.js'),
    output: 'vendor-core.min.js',
    name:   'VendorCore',
    footer: 'window.module=undefined;window.exports=undefined;window.define=undefined;',
  },
  {
    entry:  path.resolve(ROOT, 'resources/assets/entries/vendor-ui.js'),
    output: 'vendor-ui.min.js',
    name:   'VendorUI',
    footer: 'if(typeof appear!=="undefined")window.appear=appear;',
  },
  {
    entry:  path.resolve(ROOT, 'resources/assets/entries/vendor-charts.js'),
    output: 'vendor-charts.min.js',
    name:   'VendorCharts',
    footer: '',
  },
];

// ── Fix legacy globals plugin ─────────────────────────────────
// appear.js usa `appear = function(){}` sin declarar la variable,
// lo que causa ReferenceError en strict mode de los IIFEs.
const fixAppearPlugin = {
  name: 'fix-appear-global',
  transform(code, id) {
    if (id.includes('appear') && id.includes('appear.min.js')) {
      return { code: 'var appear;\n' + code, map: null };
    }
  },
};

// ── Main ──────────────────────────────────────────────────────
async function main() {
  console.log('\n  ── Vendor Chunk Splitting (postbuild) ────────────────');

  // Asegurar que el directorio de salida exista
  if (!fs.existsSync(OUT_JS)) {
    fs.mkdirSync(OUT_JS, { recursive: true });
  }

  const totalStart = Date.now();

  for (const chunk of CHUNKS) {
    const start = Date.now();
    process.stdout.write(`  ⟳  ${chunk.output}...`);

    // 1. Construir el bundle con Rollup
    const bundle = await rollup({
      input: chunk.entry,
      plugins: [fixAppearPlugin],
      onwarn(warning, warn) {
        // Silenciar warnings esperados de librerías de terceros
        if (warning.code === 'EVAL') return;
        if (warning.code === 'CIRCULAR_DEPENDENCY') return;
        warn(warning);
      },
    });

    // 2. Generar el código IIFE (sin minificar todavía)
    const { output } = await bundle.generate({
      format:               'iife',
      name:                 chunk.name,
      inlineDynamicImports: true,
      sourcemap:            false,
    });

    await bundle.close();

    const rawCode = output[0].code;

    // 3. Minificar con terser (drop_console equivale a gulp-uglify-es)
    const minResult = await terserMinify(rawCode, {
      compress: { drop_console: true },
      format:   { comments: false },
    });

    // 4. Agregar footer después de minificar para que no sea eliminado
    const finalCode = (minResult.code || rawCode) +
      (chunk.footer ? '\n' + chunk.footer : '');

    // 5. Escribir el archivo
    const dest = path.join(OUT_JS, chunk.output);
    fs.writeFileSync(dest, finalCode, 'utf8');

    const sizeKb  = (Buffer.byteLength(finalCode) / 1024).toFixed(2);
    const elapsed = Date.now() - start;
    console.log(` ✓  ${sizeKb} kB  (${elapsed}ms)`);
  }

  const totalElapsed = Date.now() - totalStart;
  console.log(`  ── Vendor splitting done in ${totalElapsed}ms ──────────────\n`);

  // Eliminar el vendor.min.js original (ya no es referenciado en los layouts Blade).
  // Los 3 chunks lo reemplazan completamente.
  const originalBundle = path.join(OUT_JS, 'vendor.min.js');
  if (fs.existsSync(originalBundle)) {
    fs.unlinkSync(originalBundle);
    console.log('  ✓ vendor.min.js (original) eliminado — reemplazado por los 3 chunks\n');
  }
}

main().catch(err => {
  console.error('\n  ✗ split-vendors.mjs failed:', err.message);
  process.exit(1);
});
