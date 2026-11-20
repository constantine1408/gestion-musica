-- Mismo modelo que database/schema.sql, adaptado a SQLite para que las pruebas
-- corran sin servidor MySQL.
CREATE TABLE Artistas (
    artista_id       INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre           TEXT NOT NULL,
    apellido         TEXT NOT NULL,
    fecha_nacimiento TEXT NULL,
    nacionalidad     TEXT NULL
);

CREATE TABLE Albumes (
    album_id         INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo           TEXT    NOT NULL,
    genero           TEXT    NOT NULL,
    anio_lanzamiento INTEGER NOT NULL CHECK (anio_lanzamiento BETWEEN 1877 AND 2100),
    discografica     TEXT    NULL
);

CREATE TABLE Album_Artista (
    album_id   INTEGER NOT NULL REFERENCES Albumes (album_id)   ON DELETE CASCADE,
    artista_id INTEGER NOT NULL REFERENCES Artistas (artista_id) ON DELETE CASCADE,
    PRIMARY KEY (album_id, artista_id)
);
