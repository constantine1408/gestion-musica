# Gestión de música

Catálogo de álbumes y artistas en PHP. Un álbum puede tener varios artistas y un artista puede aparecer en varios álbumes, así que la relación es muchos a muchos y vive en una tabla intermedia, `Album_Artista`.

## Requisitos

- PHP 8.1 o superior con las extensiones `pdo_mysql` y `mbstring`
- MySQL 8 o MariaDB 10.6 o superior
- `pdo_sqlite` solo si quieres correr las pruebas

## Puesta en marcha

Crea la base y las tablas (el script también crea la base `gestion_musica`):

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql   # datos de demostración, opcional
```

Configura la conexión con variables de entorno y levanta el servidor de desarrollo:

```bash
export DB_HOST=127.0.0.1 DB_NAME=gestion_musica DB_USER=root DB_PASS=tu_clave
php -S localhost:8000 -t public public/index.php
```

Abre http://localhost:8000. Con Apache, apunta el `DocumentRoot` a `public/` (el `.htaccess` ya redirige todo a `index.php`). La aplicación espera estar en la raíz del dominio; si la montas en un subdirectorio hay que ajustar los enlaces de las plantillas.

`APP_DEBUG=1` hace que las páginas de error muestren el mensaje real. Déjalo apagado fuera de tu máquina.

## Pruebas

```bash
php tests/run.php
```

Corren contra SQLite en memoria, con el mismo modelo que el esquema de MySQL. Cubren validaciones, el vínculo álbum-artista (incluido repetirlo), los borrados en cascada, el orden de los listados y la búsqueda con comodines.

## Cómo está organizado

```
database/     schema.sql, seed.sql
config/       lectura de variables de entorno
src/
  Domain/       Album y Artista (objetos inmutables)
  Repository/   todo el SQL, con PDO y consultas preparadas
  Service/      validación y reglas; lanza ValidationException o NoEncontradoException
  Controller/   traduce peticiones a llamadas al servicio y elige la vista
  Http/Router   rutas por expresión regular
  Support/      vistas, sesión/CSRF, utilidades
templates/    vistas PHP
public/       index.php (front controller)
tests/        run.php y el esquema de SQLite
```

Los repositorios no validan y los servicios no escriben SQL, de modo que cambiar el motor o la interfaz toca una sola capa.

## Rutas

| Método | Ruta | Acción |
|--------|------|--------|
| GET | `/albumes`, `/artistas` | Listado, con `?q=` para buscar |
| GET | `/albumes/nuevo`, `/artistas/nuevo` | Formulario de alta |
| POST | `/albumes`, `/artistas` | Crear |
| GET | `/albumes/{id}`, `/artistas/{id}` | Ficha |
| GET | `/albumes/{id}/editar`, `/artistas/{id}/editar` | Formulario de edición |
| POST | `/albumes/{id}`, `/artistas/{id}` | Actualizar |
| POST | `/albumes/{id}/eliminar`, `/artistas/{id}/eliminar` | Eliminar |
| POST | `/albumes/{id}/artistas` | Agregar un artista al álbum (`artista_id`) |
| POST | `/albumes/{id}/artistas/{artistaId}/quitar` | Quitar un artista del álbum |

## Decisiones que conviene conocer

La columna `año_lanzamiento` se llama `anio_lanzamiento` para evitar la ñ en identificadores, que suele dar problemas con dumps y herramientas configuradas con otro charset.

Borrar un álbum o un artista elimina solo los vínculos (`ON DELETE CASCADE`), nunca al otro lado. Un artista borrado deja sus álbumes sin ese nombre; el diálogo de confirmación aclara que los álbumes se conservan.

`fecha_nacimiento` y `nacionalidad` son opcionales; nombre y apellido no. Como el modelo separa nombre y apellido, una banda se registra mal (por ejemplo "Soda Stereo" necesitaría inventar un apellido). Si el catálogo va a incluir grupos, el cambio natural es un campo `nombre_artistico` o un tipo de artista.

Todos los POST exigen un token CSRF de sesión, la salida se escapa con `e()` y las consultas usan parámetros reales, no concatenación.
