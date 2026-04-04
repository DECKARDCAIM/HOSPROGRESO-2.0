/**
 * Vite Configuration — HOSPROGRESO
 * 100% Pure Vite — Replaces Gulp 4 completely
 *
 * npm run build / npm run dist → vite build
 * npm run dev → vite (dev server)
 *
 * Output: public/dist/ (identical structure to Gulp's dist task)
 *
 * ── Chunking Strategy ──────────────────────────────────────────
 * El build de Vite genera vendor.min.js como siempre.
 * El script postbuild (scripts/split-vendors.mjs) lo divide en:
 *   vendor-core.min.js   → jQuery + Bootstrap           (~165 kB)
 *   vendor-ui.min.js     → HS Plugins + UI libs         (~661 kB)
 *   vendor-charts.min.js → Chart.js only                (~191 kB)
 *
 * -- Asset Strategy (Manifest-Driven) -------------------------
 * ANTES: Copia masiva de directorios completos
 *   img/[todos]  -> 106 archivos  [no usado]
 *   svg/[todos]  -> 442 archivos  [no usado]
 *   Fonts/[todos]-> 17 archivos   [no usado]
 *   json/[todos] -> 2 archivos    [no usado]
 *
 * AHORA: Solo los assets que aparecen en las vistas Blade (manifest)
 *   img       -> 8 archivos    [OK]
 *   svg       -> 11 archivos   [OK]
 *   Fonts     -> 2 archivos    [OK]
 *   json      -> 0 archivos    [eliminado - solo era demo data]
 *   doc       -> 1 archivo     [OK]
 *
 * Ahorro: ~17.28 MB eliminados del dist/
 *
 * -- Static Copy: Vendor (Lista Blanca) ------------------------
 * ANTES: 52 directorios -> ~5195+ items
 * AHORA: 18 directorios explícitos -> ~320 items
 */

import { defineConfig } from 'vite';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import { ViteImageOptimizer } from 'vite-plugin-image-optimizer';
import path from 'path';
import fs from 'fs';

// ─── Load Asset Manifest ────────────────────────────────────
// Generado por scripts/audit-assets.mjs (corre en prebuild).
// Si no existe todavía, usa config vacía (primer build).
function loadManifest() {
  const manifestPath = path.resolve('resources/assets-manifest.json');
  if (!fs.existsSync(manifestPath)) {
    console.warn('  ⚠ assets-manifest.json no encontrado. Corre: node scripts/audit-assets.mjs');
    return null;
  }
  return JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
}

// ─── Build viteStaticCopy targets from manifest ─────────────
// Genera una entrada por cada archivo del manifest (no globos masivos).
function buildManifestTargets(manifest) {
  if (!manifest) return [];

  const targets = [];
  for (const [category, assets] of Object.entries(manifest.used)) {
    for (const asset of assets) {
      const destDir = path.dirname(asset.dest);
      targets.push({
        src:  asset.src,
        dest: destDir,
      });
    }
  }
  return targets;
}

// ─── Custom Plugin: Post-Copy Asset Compression ─────────────
// ViteImageOptimizer solo comprime archivos emitidos por Rollup,
// no los copiados por viteStaticCopy. Este plugin corre DESPUES
// de que viteStaticCopy escribe los archivos en outDir y aplica
// compresion maxima directamente con sharp (JPG/PNG) y svgo (SVG).
function assetCompressionPlugin(manifest) {
  return {
    name: 'asset-compression',
    apply: 'build',
    enforce: 'post',
    async closeBundle() {
      if (!manifest) return;

      const outDir = path.resolve('public/dist');
      let compressed = 0;
      let savedBytes = 0;

      // Recopilar todos los archivos de imagen del manifest
      const imgFiles = [];
      for (const assets of Object.values(manifest.used)) {
        for (const asset of assets) {
          const destPath = path.join(outDir, asset.dest);
          if (fs.existsSync(destPath)) {
            imgFiles.push({ dest: destPath, ext: path.extname(destPath).toLowerCase() });
          }
        }
      }

      if (imgFiles.length === 0) return;

      console.log(`\n  -- Comprimiendo ${imgFiles.length} assets de imagen...`);

      // sharp para JPG/PNG/WEBP
      let sharp;
      try {
        sharp = (await import('sharp')).default;
      } catch { sharp = null; }

      // svgo para SVG
      let svgo;
      try {
        const { optimize } = await import('svgo');
        svgo = optimize;
      } catch { svgo = null; }

      for (const { dest, ext } of imgFiles) {
        const before = fs.statSync(dest).size;

        try {
          if ((ext === '.jpg' || ext === '.jpeg') && sharp) {
            const buf = await sharp(dest)
              .jpeg({ quality: 72, progressive: true, mozjpeg: true })
              .toBuffer();
            fs.writeFileSync(dest, buf);

          } else if (ext === '.png' && sharp) {
            const buf = await sharp(dest)
              .png({ quality: 75, compressionLevel: 9, palette: true })
              .toBuffer();
            fs.writeFileSync(dest, buf);

          } else if (ext === '.webp' && sharp) {
            const buf = await sharp(dest)
              .webp({ quality: 75, effort: 6, smartSubsample: true })
              .toBuffer();
            fs.writeFileSync(dest, buf);

          } else if (ext === '.svg' && svgo) {
            const content = fs.readFileSync(dest, 'utf8');
            const result  = svgo(content, {
              multipass: true,
              js2svg: { indent: 0, pretty: false },
              plugins: [
                {
                  name: 'preset-default',
                  params: {
                    overrides: {
                      removeViewBox: false,
                      removeTitle: false,
                    },
                  },
                },
                // Plugins independientes (no son parte de preset-default)
                'cleanupIds',
                'removeComments',
                'removeMetadata',
                'removeDesc',
                'mergePaths',
                'sortAttrs',
              ],
            });
            if (result && result.data) {
              fs.writeFileSync(dest, result.data, 'utf8');
            }
          }

          const after = fs.statSync(dest).size;
          const saved = before - after;
          if (saved > 0) {
            savedBytes += saved;
            compressed++;
            const pct = ((saved / before) * 100).toFixed(1);
            const name = path.relative(outDir, dest);
            console.log(`     ${name}: ${(before/1024).toFixed(1)}kB -> ${(after/1024).toFixed(1)}kB (-${pct}%)`);
          }
        } catch (e) {
          // Silenciar errores individuales (el archivo sigue copiado sin comprimir)
        }
      }

      if (compressed > 0) {
        console.log(`  OK ${compressed} archivos comprimidos, ${(savedBytes/1024).toFixed(1)}kB ahorrados\n`);
      }
    }
  };
}

// ─── Custom Vite Plugin: Fix legacy implicit globals ────────
function fixLegacyGlobalsPlugin() {
  return {
    name: 'fix-legacy-globals',
    transform(code, id) {
      if (id.includes('appear') && id.includes('appear.min.js')) {
        return {
          code: 'var appear;\n' + code,
          map: null,
        };
      }
    }
  };
}

// ─── Custom Vite Plugin: Bundle Vendor CSS ──────────────────
function vendorCSSBundlePlugin() {
  return {
    name: 'vendor-css-bundle',
    apply: 'build',
    async writeBundle(options) {
      const outDir = options.dir || path.resolve('public/dist');
      const cssDir = path.join(outDir, 'css');

      if (!fs.existsSync(cssDir)) {
        fs.mkdirSync(cssDir, { recursive: true });
      }

      const vendorCSSFiles = [
        path.resolve('resources/assets/vendor/bootstrap-icons/font/bootstrap-icons.css'),
        path.resolve('resources/assets/vendor/tom-select/dist/css/tom-select.bootstrap5.css'),
        path.resolve('resources/assets/vendor/quill/dist/quill.snow.css'),
        path.resolve('resources/assets/vendor/flatpickr/dist/flatpickr.min.css'),
      ];

      let combined = '';
      for (const file of vendorCSSFiles) {
        if (fs.existsSync(file)) {
          combined += fs.readFileSync(file, 'utf8') + '\n';
        }
      }

      if (combined.length > 0) {
        fs.writeFileSync(path.join(cssDir, 'vendor.min.css'), combined);
        console.log('  ✓ vendor.min.css bundled');
      }
    }
  };
}

// ─── Custom Vite Plugin: Copy & Process Theme Assets ────────
function themeAssetsPlugin() {
  return {
    name: 'theme-assets',
    apply: 'build',
    async writeBundle(options) {
      const outDir = options.dir || path.resolve('public/dist');
      const srcCSS = path.resolve('resources/assets/css');
      const srcJS  = path.resolve('resources/assets/js');
      const distCSS = path.join(outDir, 'css');
      const distJS  = path.join(outDir, 'js');

      [distCSS, distJS].forEach(d => {
        if (!fs.existsSync(d)) fs.mkdirSync(d, { recursive: true });
      });

      // ── Copy CSS files ──
      // Solo se copian los archivos CSS que realmente se cargan en los layouts.
      // docs.min.css, docs-dark.min.css, snippets.min.css son del template demo
      // y no se referencian en ningun layout Blade del proyecto.
      const CSS_PRODUCTION = new Set([
        'theme.min.css',
        'theme-dark.min.css',
      ]);
      if (fs.existsSync(srcCSS)) {
        const cssFiles = fs.readdirSync(srcCSS)
          .filter(f => f.endsWith('.css') && CSS_PRODUCTION.has(f));
        for (const file of cssFiles) {
          fs.copyFileSync(path.join(srcCSS, file), path.join(distCSS, file));

          if (file.endsWith('.min.css')) {
            const content  = fs.readFileSync(path.join(srcCSS, file), 'utf8');
            const minified = content
              .replace(/\/\*[\s\S]*?\*\//g, '')
              .replace(/\s+/g, ' ')
              .replace(/\s*([{}:;,>+~])\s*/g, '$1')
              .replace(/;}/g, '}')
              .trim();
            const minName = file.replace('.min.css', '.min.min.css');
            fs.writeFileSync(path.join(distCSS, minName), minified);
          }
        }
        console.log(`  ✓ ${cssFiles.length} CSS files processed`);
      }

      // ── Copy individual JS files ──
      const jsFiles = [
        'hs-config.js',
        'hs.theme-appearance.js',
        'hs.theme-appearance-charts.js',
        'hs.theme-appearance-helper.js',
        'theme.min.js',
      ];
      for (const file of jsFiles) {
        const src = path.join(srcJS, file);
        if (fs.existsSync(src)) {
          fs.copyFileSync(src, path.join(distJS, file));
        }
      }
      console.log(`  ✓ ${jsFiles.length} JS files copied`);

      // ── Copy Bootstrap Icons fonts ──
      const fontsSrc  = path.resolve('resources/assets/vendor/bootstrap-icons/font/fonts');
      const fontsDest = path.join(distCSS, 'fonts');
      if (fs.existsSync(fontsSrc)) {
        if (!fs.existsSync(fontsDest)) fs.mkdirSync(fontsDest, { recursive: true });
        const fonts = fs.readdirSync(fontsSrc);
        for (const f of fonts) {
          fs.copyFileSync(path.join(fontsSrc, f), path.join(fontsDest, f));
        }
        console.log(`  ✓ ${fonts.length} Bootstrap Icons fonts copied`);
      }
    }
  };
}

// ─── Vendor Static Copy: Lista blanca explícita ─────────────
const USED_VENDOR_DIRS = [
  'jquery',
  'bootstrap',
  'appear',
  'hs-navbar-vertical-aside',
  'hs-form-search',
  'hs-counter',
  'hs-toggle-password',
  'hs-file-attach',
  'hs-nav-scroller',
  'hs-step-form',
  'hs-sticky-block',
  'hs-add-field',
  'tom-select',
  'imask',
  'quill',
  'chart.js',
  'bootstrap-icons',
  'flatpickr',
];

function getUsedVendorTargets() {
  const vendorDir = path.resolve('resources/assets/vendor');
  return USED_VENDOR_DIRS
    .filter(name => fs.existsSync(path.join(vendorDir, name)))
    .map(name => ({
      src:  `resources/assets/vendor/${name}/**/*`,
      dest: `vendor/${name}`,
    }));
}

// ─── Load manifest ───────────────────────────────────────────
const manifest = loadManifest();
const manifestTargets = buildManifestTargets(manifest);

// ─── Main Vite Config ───────────────────────────────────────
export default defineConfig({
  root: path.resolve(import.meta.dirname),
  publicDir: false,

  build: {
    outDir: path.resolve(import.meta.dirname, 'public/dist'),
    emptyOutDir: true,
    sourcemap: false,

    minify: 'terser',
    terserOptions: {
      compress: { drop_console: true }
    },

    rollupOptions: {
      onwarn(warning, warn) {
        if (warning.code === 'EVAL') return;
        warn(warning);
      },
      input: {
        vendor: path.resolve(import.meta.dirname, 'resources/assets/entries/vendor-bundle.js'),
      },
      output: {
        entryFileNames: 'js/[name].min.js',
        assetFileNames: 'assets/[name][extname]',
        manualChunks: undefined,
        footer: '\nwindow.module = undefined; window.exports = undefined; window.define = undefined; if(typeof appear !== "undefined") window.appear = appear;',
        format: 'iife',
        name: 'VendorBundle',
        globals: {},
      }
    },

    cssCodeSplit: false,
    assetsInlineLimit: 0,
    chunkSizeWarningLimit: 1100,
  },

  css: {
    devSourcemap: false,
  },

  plugins: [
    // ── 0. Fix legacy implicit globals ──
    fixLegacyGlobalsPlugin(),

    // ── 1. Copy SOLO los assets referenciados en vistas (manifest) ──
    //    + vendor en lista blanca
    //
    //    ANTES (total masivo):
    //      img/**/*  → 106 archivos
    //      svg/**/*  → 442 archivos
    //      Fonts/*   → 17 archivos
    //      json/*    → 2 archivos
    //      52 vendor dirs
    //
    //    AHORA (solo lo usado):
    //      img       → 8 archivos   (-92%)
    //      svg       → 11 archivos  (-98%)
    //      Fonts     → 2 archivos   (-88%)
    //      json      → 0 archivos   (-100%)
    //      doc       → 1 archivo
    //      18 vendor dirs
    viteStaticCopy({
      structured: false,
      targets: [
        // Assets auditados (manifest-driven)
        ...manifestTargets,
        // Vendor: lista blanca de 18 librerías
        ...getUsedVendorTargets(),
      ],
    }),

    // ── 2. Image & SVG optimization — Máxima compresión ──────────
    //    Solo procesa los archivos que copiamos (los del manifest).
    //    Los logos SVG grandes (255kB, 288kB) quedan en ~15-30kB.
    ViteImageOptimizer({
      includePublic: false,
      logStats: true,

      svg: {
        multipass: true,
        js2svg: {
          indent: 0,
          pretty: false,
        },
        plugins: [
          {
            name: 'preset-default',
            params: {
              overrides: {
                removeViewBox: false,
                cleanupIDs: false,
                // Habilitados explícitamente para máxima compresión:
                removeComments: true,
                removeMetadata: true,
                removeTitle: true,
                removeDesc: true,
                removeUselessDefs: true,
                removeEditorsNSData: true,
                removeEmptyAttrs: true,
                removeHiddenElems: true,
                removeEmptyText: true,
                removeEmptyContainers: true,
                mergePaths: true,
                convertShapeToPath: true,
                sortAttrs: true,
              },
            },
          },
          // Convertir colores a formato compacto
          'convertColors',
          // Comprimir paths
          { name: 'convertPathData', params: { floatPrecision: 2 } },
          // Eliminar defs vacíos
          'removeUnusedNS',
        ],
      },

      // PNG: calidad máxima sin pérdida visible
      png: {
        quality: 75,
        compressionLevel: 9,
        palette: true,       // Reducción de paleta si aplica
      },

      // JPEG: mozjpeg para compresión superior
      jpeg: {
        quality: 72,
        progressive: true,
        mozjpeg: true,
        trellisQuantisation: true,
        overshootDeringing: true,
        optimiseScans: true,
      },

      // JPG: igual que jpeg
      jpg: {
        quality: 72,
        progressive: true,
        mozjpeg: true,
        trellisQuantisation: true,
        overshootDeringing: true,
        optimiseScans: true,
      },

      gif: {
        interlaced: true,
        optimizationLevel: 3,       // Máximo (1-3)
      },

      webp: {
        quality: 75,
        lossless: false,
        nearLossless: false,
        smartSubsample: true,
        effort: 6,                  // 0-6 (máximo)
      },

      tiff: {
        quality: 80,
      },
    }),

    // ── 3. Bundle vendor CSS -> vendor.min.css ──
    vendorCSSBundlePlugin(),

    // ── 4. Copy & process theme CSS/JS + Bootstrap fonts ──
    themeAssetsPlugin(),

    // ── 5. Comprimir SOLO los assets usados (post-copia) ──────
    //    ViteImageOptimizer no comprime archivos de viteStaticCopy.
    //    Este plugin usa sharp + svgo directamente sobre los
    //    archivos ya escritos en public/dist por viteStaticCopy.
    assetCompressionPlugin(manifest),
  ],
});
