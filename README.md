# Gestión de música

Catálogo de álbumes y artistas en PHP. Un álbum puede tener varios artistas y un artista puede aparecer en varios álbumes, así que la relación es muchos a muchos y vive en una tabla intermedia, `Album_Artista`.

## Requisitos

- PHP 8.1 o superior con las extensiones `pdo_mysql` y `mbstring`
- MySQL 8 o MariaDB 10.6 o superior
- `pdo_sqlite` solo si quieres correr las pruebas

## Puesta en marcha

Todos los comandos se ejecutan desde la carpeta `gestion-musica`. El script `schema.sql` también crea la base `gestion_musica`.

### Linux / macOS

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql   # datos de demostración, opcional

export DB_HOST=127.0.0.1 DB_NAME=gestion_musica DB_USER=root DB_PASS=tu_clave
php -S localhost:8000 -t public public/index.php
```

### Windows con XAMPP (PowerShell)

Arranca MySQL desde el panel de control de XAMPP. Si `php` y `mysql` no están en el PATH, usa las rutas completas de XAMPP. PowerShell no acepta `<`, y pasar el archivo con `Get-Content ... |` estropea las tildes. Por eso se carga con `source`, que lee el archivo directamente:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 -e "source database/schema.sql"
C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 -e "source database/seed.sql"   # opcional

$env:DB_USER = "root"; $env:DB_PASS = ""   # en XAMPP root no tiene clave por defecto
C:\xampp\php\php.exe -S localhost:8000 -t public public\index.php
```

Si el usuario `root` tiene clave, agrega `-p` a los comandos de `mysql.exe` y pon la clave en `DB_PASS`. Las variables de entorno no definidas toman los valores por defecto de `config/config.php` (`127.0.0.1:3306`, base `gestion_musica`, usuario `root` sin clave).

Abre http://localhost:8000; la raíz redirige a `/albumes`. Con Apache, apunta el `DocumentRoot` a `public/` (el `.htaccess` ya redirige todo a `index.php`). La aplicación espera estar en la raíz del dominio; si la montas en un subdirectorio hay que ajustar los enlaces de las plantillas.

`APP_DEBUG=1` hace que las páginas de error muestren el mensaje real. Déjalo apagado fuera de tu máquina.

## Pruebas

```bash
php tests/run.php                       # Linux / macOS
C:\xampp\php\php.exe tests\run.php      # Windows con XAMPP
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
| GET | `/` | Redirige a `/albumes` |
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

En Windows, MySQL/MariaDB guarda los nombres de tabla en minúsculas (`albumes`, `artistas`, `album_artista`). No afecta a la aplicación, pero si exportas la base y la importas en Linux, donde los nombres distinguen mayúsculas, vuelve a crearla con `schema.sql` en lugar de usar el volcado.

La columna `año_lanzamiento` se llama `anio_lanzamiento` para evitar la ñ en identificadores, que suele dar problemas con dumps y herramientas configuradas con otro charset.

Borrar un álbum o un artista elimina solo los vínculos (`ON DELETE CASCADE`), nunca al otro lado. Un artista borrado deja sus álbumes sin ese nombre; el diálogo de confirmación aclara que los álbumes se conservan.

`fecha_nacimiento` y `nacionalidad` son opcionales; nombre y apellido no. Como el modelo separa nombre y apellido, una banda se registra mal (por ejemplo "Soda Stereo" necesitaría inventar un apellido). Si el catálogo va a incluir grupos, el cambio natural es un campo `nombre_artistico` o un tipo de artista.

Todos los POST exigen un token CSRF de sesión, la salida se escapa con `e()` y las consultas usan parámetros reales, no concatenación.

## Uso de inteligencia artificial

En este proyecto usé un asistente de IA (Claude Code, de Anthropic) como apoyo. Los commits en los que intervino llevan la línea `Co-Authored-By: Claude`.

**Trabajo propio:** _[echo por Edwin Sanchez: el modelo de datos, la lógica PHP, las pruebas, las decisiones de diseño]_.

**Con ayuda de IA:**

- **Diseño de la interfaz (CSS y plantillas):** el rediseño visual con estilo de contraportada de disco (`public/css/app.css`, las plantillas de `templates/`, las funciones `numero_catalogo()` y `lados()` en `src/Support/helpers.php` y el bloque de archivos estáticos en `public/index.php`). que también generó `PRODUCT.md`, `DESIGN.md` y la carpeta `.impeccable/`. La dirección visual la elegí yo entre varias propuestas.
- **Imágenes y bocetos:** los bocetos de diseño los generó la IA. No se usan en la aplicación.

Revisé los cambios y la aplicación funciona con ellos. Las pruebas (`tests/run.php`) pasan.

## pruebas echas en local


