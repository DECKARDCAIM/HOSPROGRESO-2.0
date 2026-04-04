/**
 * scripts/audit-assets.mjs
 * ─────────────────────────────────────────────────────────────
 * Asset Dependency Auditor — HOSPROGRESO
 *
 * Escanea TODAS las vistas Blade (y CSS/JS del proyecto) para
 * construir un manifest exacto de qué assets estáticos se usan.
 *
 * Genera: resources/assets-manifest.json
 *
 * El vite.config.js lee este manifest para copiar SOLO los archivos
 * que realmente aparecen en el código fuente.
 *
 * Ejecutar manualmente: node scripts/audit-assets.mjs
 * O corre automáticamente via npm prebuild
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT = path.resolve(__dirname, '..');

// ── Directorios a escanear ─────────────────────────────────────
const SCAN_DIRS = [
  path.resolve(ROOT, 'resources/views'),
  path.resolve(ROOT, 'resources/assets/css'),
  path.resolve(ROOT, 'resources/assets/js'),
];
const SCAN_EXTS = ['.blade.php', '.html', '.css', '.js'];

// ── Directorios de assets que queremos auditar ─────────────────
const ASSET_ROOTS = {
  img:   path.resolve(ROOT, 'resources/assets/img'),
  svg:   path.resolve(ROOT, 'resources/assets/svg'),
  Fonts: path.resolve(ROOT, 'resources/assets/Fonts'),
  json:  path.resolve(ROOT, 'resources/assets/json'),
  doc:   path.resolve(ROOT, 'resources/assets/doc'),
};

// ── Collect all files recursively ─────────────────────────────
function walkDir(dir, ext_filter = null) {
  const results = [];
  if (!fs.existsSync(dir)) return results;

  const entries = fs.readdirSync(dir, { withFileTypes: true });
  for (const e of entries) {
    const full = path.join(dir, e.name);
    if (e.isDirectory()) {
      results.push(...walkDir(full, ext_filter));
    } else if (!ext_filter || ext_filter.some(x => e.name.endsWith(x))) {
      results.push(full);
    }
  }
  return results;
}

// ── Build list of all asset files (relative paths for matching) ─
function buildAssetIndex() {
  const index = {};
  for (const [category, rootDir] of Object.entries(ASSET_ROOTS)) {
    index[category] = walkDir(rootDir).map(f => ({
      abs:     f,
      rel:     path.relative(rootDir, f),           // e.g. "160x160/img1.jpg"
      distKey: `${category}/${path.relative(rootDir, f)}`.replace(/\\/g, '/'),
      // e.g. "img/160x160/img1.jpg"
    }));
  }
  return index;
}

// ── Scan source files for asset references ─────────────────────
function scanSourceFiles() {
  const sourceFiles = [];
  for (const dir of SCAN_DIRS) {
    sourceFiles.push(...walkDir(dir, SCAN_EXTS));
  }

  const foundRefs = new Set();

  for (const file of sourceFiles) {
    const content = fs.readFileSync(file, 'utf8');

    // Match: asset('dist/img/...'), asset('dist/svg/...')
    // Match: src="...dist/img/...", href="...dist/Fonts/..."
    // Match: url('...img/...'), url("...svg/...")
    const patterns = [
      /dist\/(img|svg|Fonts|json|doc)\/([^'"\s)]+)/g,
      /['"]([^'"]*?(?:img|svg|Fonts|json|doc)\/[^'"]+\.[a-zA-Z0-9]+)['"]/g,
    ];

    for (const pattern of patterns) {
      let match;
      while ((match = pattern.exec(content)) !== null) {
        // Normalize: extract just category/path
        const full = match[0];
        const normalized = full
          .replace(/.*dist\//, '')
          .replace(/['"]/g, '')
          .trim();

        if (normalized && /^(img|svg|Fonts|json|doc)\//.test(normalized)) {
          foundRefs.add(normalized);
        }
      }
    }
  }

  return foundRefs;
}

// ── Main ──────────────────────────────────────────────────────
function main() {
  console.log('\n  ── Asset Dependency Audit ──────────────────────────────');

  const assetIndex = buildAssetIndex();
  const usedRefs   = scanSourceFiles();

  // Reportar qué se encontró
  console.log(`  → Scanned ${[...usedRefs].length} unique asset references`);

  // Construir manifest: solo assets que están en usedRefs
  const manifest = {
    generated: new Date().toISOString(),
    used: {},
    unused: {},
    stats: {},
  };

  for (const [category, assets] of Object.entries(assetIndex)) {
    manifest.used[category]   = [];
    manifest.unused[category] = [];

    for (const asset of assets) {
      if (usedRefs.has(asset.distKey)) {
        manifest.used[category].push({
          src:  asset.abs,
          rel:  asset.rel,
          dest: asset.distKey,
        });
      } else {
        manifest.unused[category].push({
          src:  asset.abs,
          rel:  asset.rel,
          dest: asset.distKey,
        });
      }
    }

    manifest.stats[category] = {
      total:  assets.length,
      used:   manifest.used[category].length,
      unused: manifest.unused[category].length,
    };
  }

  // ── Imprimir reporte ─────────────────────────────────────────
  console.log('\n  ┌─────────────────────────────────────────────────────┐');
  console.log('  │  Categoría    Total    Usados   Eliminados           │');
  console.log('  ├─────────────────────────────────────────────────────┤');

  let totalSaved = 0;
  for (const [cat, stats] of Object.entries(manifest.stats)) {
    const savedPct = ((stats.unused / stats.total) * 100).toFixed(0);

    // Calcular bytes ahorrados
    const unusedBytes = manifest.unused[cat].reduce((sum, a) => {
      try { return sum + fs.statSync(a.src).size; } catch { return sum; }
    }, 0);
    totalSaved += unusedBytes;

    const pad = (s, n) => String(s).padEnd(n);
    console.log(`  │  ${pad(cat, 10)}   ${pad(stats.total, 6)}   ${pad(stats.used, 6)}   ${pad(stats.unused, 6)} (-${savedPct}%)    │`);
  }
  console.log('  └─────────────────────────────────────────────────────┘');
  console.log(`\n  💾 Espacio liberado: ${(totalSaved / 1024 / 1024).toFixed(2)} MB`);

  // Mostrar qué assets usados se van a copiar
  console.log('\n  ✅ Assets que SÍ se copiarán:');
  for (const [cat, assets] of Object.entries(manifest.used)) {
    if (assets.length === 0) {
      console.log(`     [${cat}] NINGUNO — categoría eliminada del build`);
    } else {
      for (const a of assets) {
        console.log(`     [${cat}] ${a.rel}`);
      }
    }
  }

  // Escribir manifest
  const manifestPath = path.resolve(ROOT, 'resources/assets-manifest.json');
  fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 2), 'utf8');
  console.log(`\n  ✓ Manifest escrito en: resources/assets-manifest.json\n`);
}

main();
