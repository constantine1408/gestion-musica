# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Product Purpose

"Gestión de música" es un catálogo de álbumes y artistas con relación muchos a muchos (un álbum tiene varios artistas y un artista aparece en varios álbumes). Existe para demostrar un CRUD completo, bien estructurado y seguro en PHP. El éxito es que quien evalúa pueda recorrer todas las funciones sin fricción y perciba un trabajo cuidado.

## Operating Context

- Se ejecuta localmente con `php -S` (o Apache con `public/` como raíz) contra MySQL/MariaDB, típicamente XAMPP en Windows.
- La revisión es un recorrido de tareas: listar, buscar, ver ficha, crear, editar, eliminar, vincular y desvincular artistas.

## Capabilities and Constraints

- PHP 8.1+ sin frameworks; vistas PHP en `templates/`, CSS hoy embebido en `templates/layout.php`.
- Sin restricciones técnicas del enunciado para la interfaz: se permiten CSS propio y fuentes web.
- Rutas, formularios, nombres de campos (`_token`, `artista_id`, etc.) y comportamiento del servidor no cambian con el rediseño.
- Seguridad existente que debe mantenerse: token CSRF en todo POST, salida escapada con `e()`, confirmación antes de eliminar.
- Datos por álbum: título, género, año de lanzamiento, discográfica (opcional), artistas. Por artista: nombre, apellido, fecha de nacimiento y nacionalidad (opcionales).

## Brand Commitments

- Nombre: "Gestión de música". Idioma: español.
- Los textos actuales de la interfaz se conservan tal cual; el rediseño cambia solo la apariencia.

## Evidence on Hand

- Datos de demostración en `database/seed.sql` (rock en español: Cerati, García, Páez). No son un catálogo oficial.
- No hay portadas de álbumes, fotos de artistas, logotipo ni otros recursos gráficos

## Product Principles

1. Cada función del CRUD debe ser evidente y alcanzable en uno o dos clics desde cualquier pantalla.
2. Las acciones destructivas se distinguen claramente y siempre piden confirmación.
3. La relación muchos a muchos se ve: en cada ficha se entiende qué artistas tiene un álbum y qué álbumes tiene un artista.
4. El cuidado se demuestra en los detalles (alineación, estados vacíos, errores de validación, móvil), no en adornos.
