<?php

declare(strict_types=1);

namespace Musica\Repository;

use Musica\Domain\Album;
use Musica\Domain\Artista;
use Musica\Support\Sql;
use PDO;

final class ArtistaRepository
{
    private const COLUMNAS = 'artista_id, nombre, apellido, fecha_nacimiento, nacionalidad';

    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @return Artista[] */
    public function listar(?string $busqueda = null): array
    {
        $sql = 'SELECT ' . self::COLUMNAS . ' FROM Artistas';
        $params = [];

        if ($busqueda !== null && $busqueda !== '') {
            $sql .= " WHERE nombre LIKE :q1 ESCAPE '!'"
                . " OR apellido LIKE :q2 ESCAPE '!'"
                . " OR nacionalidad LIKE :q3 ESCAPE '!'";
            $patron = Sql::patronLike($busqueda);
            $params = [':q1' => $patron, ':q2' => $patron, ':q3' => $patron];
        }

        $stmt = $this->pdo->prepare($sql . ' ORDER BY apellido, nombre');
        $stmt->execute($params);

        return array_map(Artista::desdeFila(...), $stmt->fetchAll());
    }

    public function buscar(int $id): ?Artista
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM Artistas WHERE artista_id = :id');
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();

        return $fila === false ? null : Artista::desdeFila($fila);
    }

    public function crear(Artista $artista): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO Artistas (nombre, apellido, fecha_nacimiento, nacionalidad)
             VALUES (:nombre, :apellido, :fecha, :nacionalidad)'
        );
        $stmt->execute([
            ':nombre'       => $artista->nombre,
            ':apellido'     => $artista->apellido,
            ':fecha'        => $artista->fechaNacimiento,
            ':nacionalidad' => $artista->nacionalidad,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function actualizar(Artista $artista): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE Artistas
                SET nombre = :nombre, apellido = :apellido,
                    fecha_nacimiento = :fecha, nacionalidad = :nacionalidad
              WHERE artista_id = :id'
        );
        $stmt->execute([
            ':nombre'       => $artista->nombre,
            ':apellido'     => $artista->apellido,
            ':fecha'        => $artista->fechaNacimiento,
            ':nacionalidad' => $artista->nacionalidad,
            ':id'           => $artista->id,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM Artistas WHERE artista_id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }

    /** @return Album[] */
    public function albumesDe(int $artistaId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT al.album_id, al.titulo, al.genero, al.anio_lanzamiento, al.discografica
               FROM Albumes al
               JOIN Album_Artista aa ON aa.album_id = al.album_id
              WHERE aa.artista_id = :id
              ORDER BY al.anio_lanzamiento DESC, al.titulo'
        );
        $stmt->execute([':id' => $artistaId]);

        return array_map(Album::desdeFila(...), $stmt->fetchAll());
    }

    /**
     * Artistas que todavía no participan en el álbum; alimenta el selector
     * de "agregar artista" para no ofrecer opciones que fallarían por duplicado.
     *
     * @return Artista[]
     */
    public function noVinculadosAlAlbum(int $albumId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLUMNAS . '
               FROM Artistas a
              WHERE NOT EXISTS (
                    SELECT 1 FROM Album_Artista aa
                     WHERE aa.artista_id = a.artista_id AND aa.album_id = :album
              )
              ORDER BY a.apellido, a.nombre'
        );
        $stmt->execute([':album' => $albumId]);

        return array_map(Artista::desdeFila(...), $stmt->fetchAll());
    }
}
