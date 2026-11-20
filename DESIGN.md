---
name: Gestión de música
description: Catálogo de álbumes y artistas leído como la contraportada de un LP.
colors:
  funda: '#141414'
  funda-alta: '#1b1b1a'
  hundido: '#0e0e0e'
  filete: '#2a2a29'
  filete-fuerte: '#3d3d3b'
  tinta: '#f2f2ee'
  gris: '#a3a39c'
  etiqueta: '#e2b33c'
  etiqueta-viva: '#f0c75a'
  error: '#ff8a75'
  ok: '#7fd49a'
typography:
  display:
    fontFamily: 'Archivo, system-ui, sans-serif'
    fontSize: 'clamp(28px, 5vw, 40px)'
    fontWeight: 800
    lineHeight: 1.05
    letterSpacing: '0.01em'
  brand:
    fontFamily: 'Archivo, system-ui, sans-serif'
    fontSize: '19px'
    fontWeight: 800
    letterSpacing: '0.04em'
  title:
    fontFamily: 'Archivo, system-ui, sans-serif'
    fontSize: '18px'
    fontWeight: 700
    lineHeight: 1.3
  body:
    fontFamily: 'Archivo, system-ui, sans-serif'
    fontSize: '16px'
    fontWeight: 400
    lineHeight: 1.55
    fontFeature: 'tnum'
  meta:
    fontFamily: 'Archivo, system-ui, sans-serif'
    fontSize: '14px'
    fontWeight: 400
  label:
    fontFamily: 'Archivo, system-ui, sans-serif'
    fontSize: '13px'
    fontWeight: 700
    letterSpacing: '0.2em'
  button:
    fontFamily: 'Archivo, system-ui, sans-serif'
    fontSize: '15px'
    fontWeight: 700
    lineHeight: 1
  catalog:
    fontFamily: 'ui-monospace, Cascadia Mono, Consolas, monospace'
    fontSize: '12px'
    letterSpacing: '0.04em'
rounded:
  radio: '3px'
spacing:
  xs: '8px'
  sm: '12px'
  md: '16px'
  lg: '24px'
  xl: '40px'
  margen: 'clamp(16px, 4vw, 32px)'
  ancho: '1160px'
  cabecera: '72px'
components:
  button-primary:
    backgroundColor: '{colors.etiqueta}'
    textColor: '{colors.funda}'
    typography: '{typography.button}'
    rounded: '{rounded.radio}'
    padding: '0 18px'
    height: '40px'
  button-primary-hover:
    backgroundColor: '{colors.etiqueta-viva}'
    textColor: '{colors.funda}'
  button-secondary:
    backgroundColor: 'transparent'
    textColor: '{colors.tinta}'
    typography: '{typography.button}'
    rounded: '{rounded.radio}'
    padding: '0 18px'
    height: '40px'
  button-danger:
    backgroundColor: 'transparent'
    textColor: '{colors.error}'
    typography: '{typography.button}'
    rounded: '{rounded.radio}'
    padding: '0 18px'
    height: '40px'
  button-danger-hover:
    backgroundColor: '{colors.error}'
    textColor: '{colors.funda}'
  button-small:
    typography: '{typography.button}'
    rounded: '{rounded.radio}'
    padding: '0 12px'
    height: '32px'
  input:
    backgroundColor: '{colors.hundido}'
    textColor: '{colors.tinta}'
    typography: '{typography.body}'
    rounded: '{rounded.radio}'
    padding: '9px 12px'
    height: '42px'
  nav-link:
    textColor: '{colors.gris}'
    padding: '24px 0 21px'
  nav-link-active:
    textColor: '{colors.etiqueta}'
  track-row:
    textColor: '{colors.tinta}'
    typography: '{typography.title}'
    padding: '14px 0'
  side-label:
    textColor: '{colors.etiqueta}'
    typography: '{typography.label}'
  notice:
    rounded: '{rounded.radio}'
    padding: '12px 16px'
---

# Design System: Gestión de música

## Overview

**Creative North Star: "La contraportada del LP"**

Cada pantalla es el reverso de un disco. El fondo es cartón de funda negro, el texto es tinta blanca hueso impresa encima, y un único dorado, el de la etiqueta central del vinilo, marca lo que está activo y la acción principal. Los listados se leen como la lista de temas de una contraportada: divididos en Lado A y Lado B, cada pieza numerada (A1, A2, B1…) y con su número de catálogo en monoespaciada al margen derecho. Las fichas se leen como los créditos impresos: etiqueta gris a la izquierda, dato en tinta a la derecha, separados por filetes finos.

La densidad es de impreso, no de panel: filas generosas (14px arriba y abajo) separadas solo por un filete de 1px, sin tarjetas, sin sombras, sin barra lateral. La jerarquía la llevan el peso tipográfico (800 para títulos, 700 para nombres de pieza, 400/500 para datos) y el contraste tinta/gris, no cajas ni fondos. Es un mundo oscuro de un solo tema (`color-scheme: dark`). Rechaza explícitamente el panel de administración gris con tabla y barra lateral que sustituye.

El movimiento es casi nulo: el disco de la marca da una vuelta al cargar y todo lo demás son transiciones de color de 150 ms. El cuidado se demuestra en alineación, números tabulares, estados vacíos y errores de validación, no en adornos.

**Key Characteristics:**

- Cartón negro + tinta hueso + un dorado de etiqueta reservado a lo activo.
- Listados como lista de temas: Lado A / Lado B, numeración A1/B1, número de catálogo GM-0000.
- Una sola familia (Archivo variable, autoalojada); monoespaciada solo para el número de catálogo.
- Plano: filetes de 1px en lugar de tarjetas y sombras.
- Esquinas casi rectas (3px) en todo lo interactivo.

## Colors

Paleta de impreso sobre cartón: neutros negros apenas cálidos, una tinta hueso y un solo acento dorado; rojo y verde existen solo como estados.

### Primary

- **Dorado de etiqueta** (`etiqueta`): la etiqueta central del disco. Fondo del botón principal, enlace de navegación activo y su subrayado de 3px, nombres de sección (Lado A, Artistas), número de pista al pasar el cursor, anillo de foco, selección de texto, `accent-color` y cursor de escritura.
- **Dorado vivo** (`etiqueta-viva`): solo estado hover del dorado (botón principal) y hover de enlaces y títulos de pista. Nunca en reposo.

### Neutral

- **Cartón de funda** (`funda`): fondo de toda la página y texto sobre dorado.
- **Cartón alto** (`funda-alta`): superficie ligeramente elevada disponible en el sistema; no tiene uso propio todavía en las pantallas.
- **Hundido** (`hundido`): interior de campos de texto y selects; el campo se lee como grabado en el cartón.
- **Filete** (`filete`): separador de 1px entre filas de pista, créditos, vínculos y bajo la cabecera.
- **Filete fuerte** (`filete-fuerte`): borde de campos y botón secundario, regla bajo los nombres de sección y sobre los formularios.
- **Tinta hueso** (`tinta`): todo el texto principal, títulos y enlaces en reposo.
- **Gris de imprenta** (`gris`): datos secundarios: número de pista, artistas y género, etiquetas de créditos, enlaces de navegación inactivos, estados vacíos, número de catálogo.

### Estados

- **Error** (`error`): botón Eliminar, mensajes de validación, borde del campo inválido, aviso de error.
- **Ok** (`ok`): aviso de confirmación tras crear, editar o eliminar.

### Named Rules

**The Etiqueta Rule.** El dorado es la etiqueta del disco: aparece solo en lo activo (sección actual, foco, hover) y en una única acción principal por pantalla. Si dos cosas compiten en dorado en reposo, una sobra.

**The Two Inks Rule.** El texto solo tiene dos tintas: hueso para lo que se lee primero y gris para lo que acompaña. No se introducen grises intermedios para texto.

**The States Only Rule.** Rojo y verde existen solo como estado (eliminar, error, confirmación); nunca decoran.

## Typography

**Display Font:** Archivo (variable 400–800, autoalojada en `public/fonts`, con system-ui de respaldo)
**Body Font:** Archivo
**Label/Mono Font:** monoespaciada del sistema (ui-monospace, Cascadia Mono, Consolas), solo para el número de catálogo

**Character:** Un grotesco de impresión con cuerpo; en 800 y mayúsculas tiene el golpe de un título de contraportada, en 400/500 es sobrio y legible. Todos los números son tabulares (`font-variant-numeric: tabular-nums` en `body`), de modo que años y numeraciones se alinean en columna.

### Hierarchy

- **Display** (800, clamp 28–40px, 1.05, mayúsculas, +0.01em, `text-wrap: balance`): el `h1` de cada pantalla (Álbumes, título del álbum, nombre del artista, encabezado del formulario). Uno por pantalla.
- **Brand** (800, 19px; 15px en móvil, mayúsculas, +0.04em): solo el nombre «Gestión de música» junto al disco.
- **Title** (700, 18px, 1.3): título de cada pista en los listados; 17px en los vínculos de la ficha.
- **Body** (400, 16px, 1.55): texto corriente, valores de campo. Los datos de créditos van en 500.
- **Meta** (400, 14px, en gris): artistas y género bajo la pista, etiquetas de créditos, nacionalidad, número de pista (500).
- **Label** (700, 13px, +0.2em, mayúsculas, en dorado, con regla de 1px debajo): nombres de sección reales: Lado A, Lado B, Artistas, Álbumes, Agregar artista.
- **Button** (700, 15px, 1): texto de todos los botones; 14px en el botón pequeño.
- **Catalog** (mono, 12px, +0.04em, en gris): número de catálogo GM-0000.

### Named Rules

**The Label Is A Side Rule.** El rótulo dorado en versalitas espaciadas nombra una sección real de contenido (un lado, una lista de la ficha). Nunca se usa como antetítulo decorativo encima de un `h1`.

**The Mono Is Catalog Rule.** La monoespaciada está reservada al número de catálogo; cualquier otro dato, incluidas fechas y años, va en Archivo con cifras tabulares.

## Layout

Columna única centrada de `ancho` como máximo, con `margen` lateral fluido (16–32px). La cabecera mide `cabecera` de alto, con un filete inferior; la marca empuja la navegación a la derecha. El `main` deja 40px arriba (28px en móvil) y 72px abajo.

Cada pantalla abre con una **barra**: el `h1` a la izquierda y las acciones (botón principal, o Editar + Eliminar) alineadas a la base a la derecha, separadas 28px del contenido; envuelve en pantallas estrechas.

- **Listados:** búsqueda a todo el ancho, luego una rejilla de dos columnas iguales (Lado A, Lado B) con 56px de separación. Cada pista es una rejilla de tres columnas: número (40px; 34px en móvil), contenido flexible, y columna derecha alineada a la derecha con año y catálogo.
- **Fichas:** dos columnas, créditos en `minmax(220px, 300px)` a la izquierda y la relación (artistas o álbumes) a la derecha, separadas 64px. Los créditos son pares etiqueta/dato en rejilla de 120px + 1fr.
- **Formularios:** una columna de 560px máximo, etiquetas encima del campo, 16px entre campos y 32px antes de las acciones.

Ritmo de espaciado: 8 / 12 / 16 / 24 / 40px, con 56px y 64px solo como separación de columnas. Por debajo de 760px los lados y la ficha se apilan en una columna y el botón Buscar ocupa todo el ancho.

## Elevation & Depth

El sistema es completamente plano: no hay `box-shadow` en ningún elemento. La profundidad se expresa por tono y línea. Los campos se hunden (`hundido`, más oscuro que el cartón) y todo lo demás vive sobre el mismo plano de cartón, separado por filetes de 1px (`filete` entre filas, `filete-fuerte` como regla de sección). Los avisos se tiñen con su color de estado al 9% sobre el cartón y borde al 55%.

### Named Rules

**The Printed Sleeve Rule.** Una contraportada es papel impreso: sin sombras, sin tarjetas flotantes, sin desenfoques. Si algo necesita separarse, recibe un filete.

## Shapes

Esquinas casi rectas: un único radio de 3px (`radio`) en botones, campos, avisos y anillo de foco. Las filas y secciones no tienen caja: son bandas abiertas definidas por un filete inferior. La única forma curva es el disco de la marca (SVG de 28px): vinilo casi negro con surcos concéntricos y etiqueta dorada con un brillo en arco; es el emblema del sistema y no se repite como decoración.

## Components

### Buttons

Sólidos y directos, de altura fija.

- **Shape:** casi recto (3px), altura mínima 40px, 18px de relleno lateral.
- **Primary:** fondo dorado, texto cartón, 700 15px. Una por pantalla: Nuevo álbum, Nuevo artista, Guardar, Agregar.
- **Hover / Focus:** hover pasa a dorado vivo (transición de color 150 ms ease-out); foco con anillo dorado de 2px a 2px de distancia.
- **Secondary:** transparente, texto tinta, borde `filete-fuerte`; hover aclara el borde a tinta. Para Editar, Cancelar, Buscar, Quitar.
- **Danger:** transparente con texto y borde rojo de error; hover se rellena de rojo con texto cartón. Solo Eliminar, siempre con confirmación.
- **Small:** 32px de alto, 12px de relleno, 14px de texto; para Quitar dentro de una fila de vínculo.

### Inputs / Fields

- **Style:** fondo hundido, borde 1px `filete-fuerte`, radio 3px, 42px de alto, 16px de texto (sin zoom en iOS). El placeholder va en gris atenuado.
- **Hover:** el borde se aclara un paso.
- **Focus:** borde dorado y anillo dorado de 2px sin separación.
- **Error:** el campo de una etiqueta con mensaje de error toma borde rojo; el mensaje va debajo en 14px rojo.

### Navigation

- **Style:** dos enlaces (Álbumes, Artistas) en 500, gris en reposo, tinta al pasar el cursor.
- **Active:** `aria-current="page"` pinta el enlace en dorado con un subrayado de 3px pegado al filete de la cabecera.
- **Mobile:** mismos enlaces con menos separación (18px); la marca baja a 15px.

### Notices

- **Style:** banda con borde de 1px y radio 3px, texto 500 en el color de estado, fondo teñido al 9% sobre el cartón. Variantes ok y error, encima del contenido con 28px de separación.

### Pista (signature component)

La fila de la lista de temas. Número de pista (A1, B2…) en gris 500 14px; título de la pieza en 700 18px tinta; debajo, artistas y género en gris 14px; a la derecha, año en tinta 500 y número de catálogo GM-0000 en mono. Toda la fila es clicable (el enlace del título se extiende a la fila) y al pasar el cursor o enfocar, el número de pista se vuelve dorado y el título dorado vivo. Se repite en la ficha del artista (numerada sin lado) para mostrar sus álbumes.

### Créditos y vínculos

Los créditos de una ficha son una lista de definiciones: etiqueta gris a la izquierda, dato en tinta 500 a la derecha, filete debajo; el número de catálogo usa la mono. Los vínculos (artistas de un álbum) son filas con el nombre enlazado en 700 17px, la nacionalidad en gris debajo y el botón pequeño Quitar a la derecha. El estado vacío es una frase en gris sobre un filete, con el enlace de salida en dorado.

### Marca

Disco SVG de 28px más el nombre en Brand. Al cargar, el disco gira una vuelta (1.4s, `cubic-bezier(.16, 1, .3, 1)`) solo si el usuario no pide movimiento reducido. Es el único movimiento de entrada del sistema.

## Do's and Don'ts

### Do:

- **Do** presentar cualquier listado como lista de temas: Lado A / Lado B, numeración por lado y filas separadas por filete de 1px.
- **Do** mostrar el número de catálogo GM-0000 derivado del id real en monoespaciada de 12px, alineado a la derecha.
- **Do** reservar el dorado (`etiqueta`) a la acción principal, el elemento activo y el foco; su hover es siempre `etiqueta-viva`.
- **Do** usar cifras tabulares para años, fechas y numeraciones.
- **Do** separar con filetes (`filete`, `filete-fuerte`) en lugar de cajas o fondos.
- **Do** mantener el radio de 3px en todo control y el anillo de foco dorado de 2px.
- **Do** distinguir Eliminar con el botón de peligro rojo y confirmar siempre antes de enviar.
- **Do** respetar `prefers-reduced-motion`: el giro del disco es el único movimiento de entrada.

### Don't:

- **Don't** volver al panel de administración gris con tablas y barra lateral.
- **Don't** añadir sombras, tarjetas flotantes ni desenfoques; el sistema es plano.
- **Don't** usar el dorado como decoración ni poner dos elementos dorados en reposo compitiendo en la misma barra.
- **Don't** usar la monoespaciada para nada que no sea el número de catálogo.
- **Don't** poner rótulos en versalitas espaciadas como antetítulo de un `h1`; ese estilo solo nombra secciones de contenido.
- **Don't** inventar portadas, fotos de artistas ni logotipos; el disco de la marca es el único recurso gráfico.
- **Don't** introducir un tema claro: el mundo es cartón negro con `color-scheme: dark`.
