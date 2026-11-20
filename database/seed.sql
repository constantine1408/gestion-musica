-- Datos de demostración para probar la aplicación. No son un catálogo oficial:
-- si los vas a mostrar en una entrega, revisa discográficas y fechas.

USE gestion_musica;

INSERT INTO Artistas (nombre, apellido, fecha_nacimiento, nacionalidad) VALUES
    ('Gustavo', 'Cerati', '1959-08-11', 'Argentina'),
    ('Charly',  'García', '1951-10-23', 'Argentina'),
    ('Fito',    'Páez',   '1963-03-13', 'Argentina');

INSERT INTO Albumes (titulo, genero, anio_lanzamiento, discografica) VALUES
    ('Bocanada',                   'Rock alternativo', 1999, 'BMG'),
    ('Clics modernos',             'Rock',             1983, 'Interdisc'),
    ('El amor después del amor',   'Rock',             1992, 'Warner Music'),
    ('Rock en español, vol. 1 (demo)', 'Compilación',  2020, NULL);

INSERT INTO Album_Artista (album_id, artista_id) VALUES
    (1, 1),
    (2, 2),
    (3, 3),
    (4, 1), (4, 2), (4, 3);
