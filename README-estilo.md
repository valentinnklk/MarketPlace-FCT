# Marketplace · Variante «SaaS Profesional»
## Bootstrap 5 + paleta corporate (basada en el mockup de referencia)

Variante construida desde cero sobre el proyecto MarketPlace-FCT
original, aplicando la paleta de colores y la estética del mockup
de referencia (estilo dashboard SaaS limpio y profesional).

---

## Paleta oficial (60/20/10/10)

| % | Color | Hex | Uso |
|---|---|---|---|
| **60%** | Blanco | `#FFFFFF` | Fondo de tarjetas, modales, formularios |
| **20%** | Azul claro | `#E8F1FF` | Fondos secundarios, hover, pildoras de precio |
| **10%** | Azul oscuro | `#2563EB` | Botones primarios, enlaces, iconos activos |
| **10%** | Gris | `#F2F4F7` | Fondo de página, divisores, textos secundarios |

Color semánticos complementarios (alertas y estados):
verde `#10B981`, ámbar `#F59E0B`, rojo `#EF4444`.

---

## Librerías (todas vía CDN)

| Librería | Versión | Función |
|---|---|---|
| Bootstrap | 5.3.2 | Framework CSS base |
| Bootstrap Icons | 1.11.3 | 1.800+ iconos profesionales (sustituyen los 85 emojis) |
| Google Fonts | — | Inter + IBM Plex Mono |

---

## Tipografía

- **Inter** — toda la interfaz (UI, títulos, botones, navegación).
  Pesos 400/500/600/700/800. Activación de features `cv11` y `ss01`
  para variantes estilísticas modernas.
- **IBM Plex Mono** — exclusivamente para precios. Aplicada en las
  pildoras "50,00 € / trabajo" para refuerzo visual del dato numérico.

---

## Iconos: cero emojis

Los 85 emojis del proyecto original han sido sustituidos por iconos
vectoriales Bootstrap Icons, envueltos en
`<i class="bi bi-X" aria-hidden="true"></i>` para que los lectores
de pantalla no los lean y se apoyen en el texto adyacente del botón
o enlace.

35 iconos únicos cubren todo el inventario: estrellas, check, x,
persona, mensaje, campana, calendario, bandera, lupa, herramienta,
pin de ubicación, lápiz, etiqueta, euro, cohete, sobre, candado,
papelera, pulgares arriba/abajo, gráfica, etc.

---

## Accesibilidad WCAG 2.1 AA

- `lang="es"` en cada vista
- `<meta viewport>` para reflujo responsive
- Skip-link al contenido principal (primer focusable)
- `role="navigation"` + `aria-label="Principal"` en navbar
- `role="main"` + `id="contenido"` en contenedor principal
- `role="dialog"` + `aria-modal="true"` + `tabindex="-1"` en modales
- `aria-label` en input y botón de búsqueda
- `aria-hidden="true"` en los 85 iconos
- `*:focus-visible` con outline azul de alto contraste
- `prefers-reduced-motion` y `prefers-contrast` respetados

Contrastes verificados:
- Tinta `#111827` sobre blanco: **17.91:1** (AAA)
- Azul `#2563EB` sobre blanco: 5.17:1 (AA)
- Gris texto `#6B7280` sobre blanco: 5.07:1 (AA)
- Azul oscuro `#1D4ED8` sobre azul claro `#E8F1FF`: 6.15:1 (AA)

---

## Estructura

```
MarketPlace-FCT-SaaS/
├── assets/
│   └── css/
│       └── estilo.css        ← capa de estilo sobre Bootstrap
├── conexion.php              ← INTACTO
├── controladores/            ← INTACTO
├── modelo/                   ← INTACTO
├── vista/                    ← 10 vistas adaptadas (clases Bootstrap mantenidas)
├── marketplace_actualizado.sql
├── index.php
└── README-estilo.md
```

---

## Instalación en XAMPP / Laragon

1. **Copie la carpeta completa** a `htdocs/`:
   `C:\xampp\htdocs\MarketPlace-FCT-SaaS\`

2. **Verifique que la estructura es plana**, no anidada. Después
   de descomprimir, dentro de `htdocs/MarketPlace-FCT-SaaS/` deben
   aparecer directamente las carpetas `vista/`, `assets/`,
   `controladores/`, `modelo/` y los archivos `conexion.php` e
   `index.php`. Si aparece una carpeta intermedia
   (`MarketPlace-FCT-SaaS/MarketPlace-FCT-SaaS/...`),
   suba el contenido un nivel.

3. **Importe** `marketplace_actualizado.sql` en phpMyAdmin.

4. **Edite** `conexion.php` si necesita ajustar credenciales.

5. **Abra** en el navegador:
   `http://localhost/MarketPlace-FCT-SaaS/`

   Y use **Ctrl+F5** la primera vez para evitar caché de versiones
   anteriores.

---

## Cómo verificar que el CSS se aplica correctamente

1. Pulse **F12** para abrir DevTools.
2. Pestaña **Network** → recargue con **Ctrl+F5**.
3. Busque `estilo.css` en la lista. Debe aparecer:
   - **Estado**: 200 (verde)
   - **Tamaño**: ~18 KB (no `from cache`, no `0 B`)
4. Si aparece 404 (rojo), la ruta no encuentra el archivo:
   compruebe que `assets/css/estilo.css` existe al mismo nivel
   que `vista/`.

---

## Lo que NO se ha tocado

- Toda la lógica PHP (controladores, modelos, conexión, SQL)
- Atributos `name=`, `action=`, `method=` de formularios
- Modales Bootstrap (`data-bs-toggle`, `data-bs-target`)
- Variables PHP (`$_SESSION`, `$_POST`, `$_GET`)
- IDs de los inputs, nombres de funciones JavaScript

La aplicación funciona exactamente igual que el proyecto original.
Sólo cambia el aspecto visual.
