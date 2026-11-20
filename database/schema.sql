-- Catálogo musical: álbumes, artistas y la relación muchos a muchos entre ellos.
-- Pensado para MySQL 8 / MariaDB 10.6 o superior.

CREATE DATABASE IF NOT EXISTS gestion_musica
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gestion_musica;
SET NAMES utf8mb4;

CREATE TABLE Artistas (
    artista_id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre           VARCHAR(80)  NOT NULL,
    apellido         VARCHAR(80)  NOT NULL,
    fecha_nacimiento DATE         NULL,
    nacionalidad     VARCHAR(60)  NULL,
    PRIMARY KEY (artista_id),
    KEY idx_artistas_apellido_nombre (apellido, nombre)
) ENGINE=InnoDB;

-- La columna se llama anio_lanzamiento y no año_lanzamiento: la ñ en
-- identificadores funciona, pero da problemas con herramientas y dumps
-- configurados con otro charset.
CREATE TABLE Albumes (
    album_id         INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    titulo           VARCHAR(150)      NOT NULL,
    genero           VARCHAR(60)       NOT NULL,
    anio_lanzamiento SMALLINT UNSIGNED NOT NULL,
    discografica     VARCHAR(100)      NULL,
    PRIMARY KEY (album_id),
    KEY idx_albumes_genero (genero),
    KEY idx_albumes_anio (anio_lanzamiento),
    CONSTRAINT chk_albumes_anio CHECK (anio_lanzamiento BETWEEN 1877 AND 2100)
) ENGINE=InnoDB;

-- Un álbum puede tener varios artistas y un artista varios álbumes.
-- La clave compuesta evita vínculos duplicados; si se borra cualquiera de
-- los dos lados, el vínculo desaparece solo.
CREATE TABLE Album_Artista (
    album_id   INT UNSIGNED NOT NULL,
    artista_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (album_id, artista_id),
    KEY idx_album_artista_artista (artista_id),
    CONSTRAINT fk_aa_album   FOREIGN KEY (album_id)   REFERENCES Albumes (album_id)   ON DELETE CASCADE,
    CONSTRAINT fk_aa_artista FOREIGN KEY (artista_id) REFERENCES Artistas (artista_id) ON DELETE CASCADE
) ENGINE=InnoDB;
