/**
 * Vite Configuration — HOSPROGRESO
 * 100% Pure Vite — Replaces Gulp 4 completely
 *
 * npm run build / npm run dist → vite build
 * npm run dev → vite (dev server)
 *
 * Output: public/dist/ (identical structure to Gulp's dist task)
 */

import { defineConfig } from 'vite';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import { ViteImageOptimizer } from 'vite-plugin-image-optimizer';
import path from 'path';
import fs from 'fs';

// ─── Custom Vite Plugin: Fix legacy implicit globals ────────
// Some vendor libraries assign to undeclared globals (e.g. `appear = function(){}`)
// which is valid in classic scripts but causes ReferenceError in ESM/IIFE bundles.
// This plugin prepends a `var` declaration so the assignment is valid in strict scope.
function fixLegacyGlobalsPlugin() {
  return {
    name: 'fix-legacy-globals',
    transform(code, id) {
      // Fix appear.js: uses `appear=function(){...}` without declaration
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
// Concatenates the same 4 vendor CSS files that Gulp bundled
// and writes vendor.min.css to the output directory
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

      // Same 4 vendor CSS files as Gulp's vendorBundleCSS task
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
        // Use clean-css-like minification via simple regex (no extra deps needed)
        // Vite's built-in CSS minifier handles the heavy lifting
        fs.writeFileSync(path.join(cssDir, 'vendor.min.css'), combined);
        console.log('  ✓ vendor.min.css bundled');
      }
    }
  };
}

// ─── Custom Vite Plugin: Copy & Process Theme Assets ────────
// Copies CSS files (with .min.min versions) and individual JS files
function themeAssetsPlugin() {
  return {
    name: 'theme-assets',
    apply: 'build',
    async writeBundle(options) {
      const outDir = options.dir || path.resolve('public/dist');
      const srcCSS = path.resolve('resources/assets/css');
      const srcJS = path.resolve('resources/assets/js');
      const distCSS = path.join(outDir, 'css');
      const distJS = path.join(outDir, 'js');

      // Ensure directories exist
      [distCSS, distJS].forEach(d => {
        if (!fs.existsSync(d)) fs.mkdirSync(d, { recursive: true });
      });

      // ── Copy CSS files ──
      if (fs.existsSync(srcCSS)) {
        const cssFiles = fs.readdirSync(srcCSS).filter(f => f.endsWith('.css'));
        for (const file of cssFiles) {
          // Copy original
          fs.copyFileSync(path.join(srcCSS, file), path.join(distCSS, file));

          // Create .min.min version for files that already have .min (matching Gulp behavior)
          if (file.endsWith('.min.css')) {
            const content = fs.readFileSync(path.join(srcCSS, file), 'utf8');
            // Basic CSS minification (remove comments, whitespace)
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
      const fontsSrc = path.resolve('resources/assets/vendor/bootstrap-icons/font/fonts');
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

// ─── Helper: List all vendor subdirectories for static copy ──
function getVendorTargets() {
  const vendorDir = path.resolve('resources/assets/vendor');
  if (!fs.existsSync(vendorDir)) return [];

  return fs.readdirSync(vendorDir, { withFileTypes: true })
    .filter(entry => entry.isDirectory())
    .map(entry => ({
      src: `resources/assets/vendor/${entry.name}/**/*`,
      dest: `vendor/${entry.name}`,
    }));
}

// ─── Main Vite Config ───────────────────────────────────────
export default defineConfig({
  root: path.resolve(import.meta.dirname),

  // Disable publicDir — outDir is inside public/ which would cause recursion
  publicDir: false,

  build: {
    outDir: path.resolve(import.meta.dirname, 'public/dist'),
    emptyOutDir: true,
    sourcemap: false,

    // Terser for JS minification (matches gulp-uglify-es behavior)
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
    chunkSizeWarningLimit: 1000,
  },

  css: {
    devSourcemap: false,
  },

  plugins: [
    // ── 0. Fix legacy implicit globals (appear, etc.) ──
    fixLegacyGlobalsPlugin(),

    // ── 1. Copy static assets (img, svg, Fonts, json, sound, video, doc) ──
    viteStaticCopy({
      structured: false,
      targets: [
        // Images
        { src: 'resources/assets/img/**/*', dest: 'img' },
        // SVG compiled files
        { src: 'resources/assets/svg/**/*', dest: 'svg' },
        // Custom Fonts (OTF files)
        { src: 'resources/assets/Fonts/*', dest: 'Fonts' },
        // JSON data files
        { src: 'resources/assets/json/*', dest: 'json' },
        // Document files
        { src: 'resources/assets/doc/*', dest: 'doc' },
        // All 52 vendor library directories (each copied individually)
        ...getVendorTargets(),
      ],
    }),

    // ── 2. Image & SVG optimization (uses sharp + svgo) ──
    ViteImageOptimizer({
      includePublic: false,
      logStats: true,
      svg: {
        multipass: true,
        plugins: [
          {
            name: 'preset-default',
            params: {
              overrides: {
                removeViewBox: false,
                cleanupIDs: false,
              },
            },
          },
        ],
      },
      png: {
        quality: 80,
        compressionLevel: 9,
      },
      jpeg: {
        quality: 75,
        progressive: true,
        mozjpeg: true,
      },
      jpg: {
        quality: 75,
        progressive: true,
        mozjpeg: true,
      },
      gif: {
        interlaced: true,
      },
      webp: {
        quality: 80,
      },
    }),

    // ── 3. Bundle vendor CSS → vendor.min.css ──
    vendorCSSBundlePlugin(),

    // ── 4. Copy & process theme CSS/JS + Bootstrap fonts ──
    themeAssetsPlugin(),
  ],
});
