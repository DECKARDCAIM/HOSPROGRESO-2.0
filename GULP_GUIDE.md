# Guía de Gestión de Activos con Gulp (HOSPROGRESO)

Esta guía detalla los comandos necesarios para compilar, optimizar y gestionar los activos del proyecto tras la migración de Vite a Gulp.

## Estructura de Archivos
- **Fuentes**: `resources/assets/` (Aquí es donde debes hacer cambios en CSS, JS o Vendors).
- **Compilados**: `public/` (Archivos finales servidos por Laravel).

---

## Comandos Principales

### 1. Desarrollo (Modo Watch)
Para que los cambios que realices en `resources/assets/` se reflejen instantáneamente en el navegador:
```bash
npx gulp
```
- **Qué hace**: Vigila cambios en archivos `.js`, `.css`, `.scss`, e imágenes. Al detectar un cambio, compila y hace un refresh automático de la carpeta `public/`.
- **Ideal para**: Programación diaria y ajustes visuales rápidos.

### 2. Producción (Optimización Máxima)
Cuando estés listo para desplegar o quieras generar los bundles finales:
```bash
npx gulp dist
```
- **Qué hace**: 
    1. Limpia la carpeta `public/`.
    2. Minifica todo el CSS y JS.
    3. **Genera los Bundles Unificados**: Crea `vendor.min.js` y `vendor.min.css` con todas las librerías comunes (TomSelect, DataTables, etc.).
    4. Copia fuentes, imágenes y el archivo de configuración `hs-config.js`.
    5. Optimiza SVGs.
- **Ideal para**: Antes de hacer un commit, desplegar a staging o producción.

### 3. Limpieza de Activos
Si notas comportamientos extraños o archivos "fantasmas":
```bash
npx gulp clean
```
- **Qué hace**: Elimina las carpetas `css`, `js` y `vendor` de la carpeta `public/`.

### 4. Compilador de SVG
Si agregas nuevos iconos SVG a la carpeta de origen:
```bash
npx gulp svg-compiler
```
- **Qué hace**: Procesa y optimiza los archivos SVG para su uso eficiente en la web.

---

## Tips y Notas Importantes

### Reflejar Cambios Instantáneos
Si estás editando un JS en `resources/assets/js/theme-custom.js`, asegúrate de tener `npx gulp` corriendo en una terminal. Verás un mensaje en consola confirmando que la tarea `JS` o `CSS` ha finalizado. Luego solo refresca el navegador.

### Configuración del Tema
La configuración global del dashboard (colores, layout, skins) ahora vive en:
`resources/assets/js/hs-config.js`
Si necesitas cambiar el color primario o el comportamiento del sidebar, edita ese archivo y corre `npx gulp dist`.

### ¿Por qué `dist` y no `build`?
- **`dist`**: Está adaptado específicamente para nuestra estructura de Laravel con rutas fijas y bundles centralizados.
- **`build`**: Es la tarea original del template para generar sitios HTML estáticos. Úsala solo si necesitas generar una versión de prueba fuera de Laravel.

---

**¡IMPORTANTE!** No edites archivos directamente en la carpeta `public/`. Los cambios se perderán la próxima vez que ejecutes una tarea de Gulp. Trabaja siempre sobre `resources/assets/`.
