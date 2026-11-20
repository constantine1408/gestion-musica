---
version: 1
slug: 'templates-layout-php'
primary_target: 'templates/layout.php'
related_targets:
  [
    'templates/albumes/index.php',
    'templates/albumes/detalle.php',
    'templates/albumes/form.php',
    'templates/artistas/index.php',
    'templates/artistas/detalle.php',
    'templates/artistas/form.php',
    'templates/error.php',
  ]
---

## Direction contract

THESIS: Cada pantalla es el reverso de un disco: el listado se lee como la lista de temas de una contraportada, en Lado A y Lado B, con número de catálogo por pieza. Rechaza el panel de administración gris con tabla y barra lateral.

OWN-WORLD: Cartón de funda negro (#141414) con tinta blanca hueso (#f2f2ee), un dorado de etiqueta central (#e2b33c) solo para lo activo y la acción principal, gris de imprenta (#9a9a93) para datos secundarios y filetes #2a2a2a. Archivo autoalojada: títulos en mayúsculas de peso 800, cuerpo 400/500, números tabulares. Mono solo para el número de catálogo. Marca: disco con etiqueta dorada en SVG.

STORY: Quien evalúa entiende en un vistazo qué hay en el catálogo, entra a una ficha, ve la relación álbum-artista en ambos sentidos y completa cada acción del CRUD sin buscar los controles.

FIRST VIEWPORT: Encabezado de 72px con marca a la izquierda y navegación Álbumes / Artistas (activa en dorado). Debajo, título de sección en 40px mayúsculas y «Nuevo álbum» dorado a la derecha; búsqueda a todo el ancho; luego dos columnas Lado A / Lado B con filas numeradas A1, A2… (título 18px, artistas y género en gris, número de catálogo mono a la derecha). En móvil los lados se apilan.

FORM: Contraportada de LP, candidata 1 de mi lista ordenada (elegida por el usuario como IMPECCABLE’S PICK sobre la asignada, candidata 5). seed:e375a7d41ac1ae75. Firma: numeración A1/B1 y número de catálogo GM-0000 derivado del id real. Movimiento: el disco de la marca gira una vuelta al cargar (ease-out), sin efectos dispersos; respeta prefers-reduced-motion.

FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
