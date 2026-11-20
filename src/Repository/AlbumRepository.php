<?php

declare(strict_types=1);

namespace Musica\Repository;

use Musica\Domain\Album;
use Musica\Domain\Artista;
use Musica\Support\Sql;
use PDO;
use PDOException;

final class AlbumRepository
{
    private const COLUMNAS = 'album_id, titulo, genero, anio_lanzamiento, discografica';

    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @return Album[] */
    public function listar(?string $busqueda = null): array
    {
        $sql = 'SELECT ' . self::COLUMNAS . ' FROM Albumes';
        $params = [];

        if ($busqueda !== null && $busqueda !== '') {
            $sql .= " WHERE titulo LIKE :q1 ESCAPE '!'"
                . " OR genero LIKE :q2 ESCAPE '!'"
                . " OR discografica LIKE :q3 ESCAPE '!'";
            $patron = Sql::patronLike($busqueda);
            $params = [':q1' => $patron, ':q2' => $patron, ':q3' => $patron];
        }

        $stmt = $this->pdo->prepare($sql . ' ORDER BY anio_lanzamiento DESC, titulo');
        $stmt->execute($params);

        return array_map(Album::desdeFila(...), $stmt->fetchAll());
    }

    public function buscar(int $id): ?Album
    {
        $stmt = $this->pdo->prepare('SELECT ' . self::COLUMNAS . ' FROM Albumes WHERE album_id = :id');
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();

        return $fila === false ? null : Album::desdeFila($fila);
    }

    public function crear(Album $album): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO Albumes (titulo, genero, anio_lanzamiento, discografica)
             VALUES (:titulo, :genero, :anio, :discografica)'
        );
        $stmt->execute([
            ':titulo'       => $album->titulo,
            ':genero'       => $album->genero,
            ':anio'         => $album->anioLanzamiento,
            ':discografica' => $album->discografica,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function actualizar(Album $album): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE Albumes
                SET titulo = :titulo, genero = :genero,
                    anio_lanzamiento = :anio, discografica = :discografica
              WHERE album_id = :id'
        );
        $stmt->execute([
            ':titulo'       => $album->titulo,
            ':genero'       => $album->genero,
            ':anio'         => $album->anioLanzamiento,
            ':discografica' => $album->discografica,
            ':id'           => $album->id,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM Albumes WHERE album_id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Trae los artistas de varios álbumes en una sola consulta, así el listado
     * no dispara una query por fila.
     *
     * @param int[] $albumIds
     * @return array<int, Artista[]> artistas agrupados por album_id
     */
    public function artistasPorAlbum(array $albumIds): array
    {
        if ($albumIds === []) {
            return [];
        }

        $marcadores = implode(',', array_fill(0, count($albumIds), '?'));
        $stmt = $this->pdo->prepare(
            "SELECT aa.album_id, a.artista_id, a.nombre, a.apellido, a.fecha_nacimiento, a.nacionalidad
               FROM Album_Artista aa
               JOIN Artistas a ON a.artista_id = aa.artista_id
              WHERE aa.album_id IN ($marcadores)
              ORDER BY a.apellido, a.nombre"
        );
        $stmt->execute(array_values($albumIds));

        $agrupados = [];
        foreach ($stmt->fetchAll() as $fila) {
            $agrupados[(int) $fila['album_id']][] = Artista::desdeFila($fila);
        }

        return $agrupados;
    }

    /**
     * Crea el vínculo álbum-artista. Devuelve false si ya existía, así que
     * repetir la operación (doble clic, reintento) no produce un error.
     */
    public function vincular(int $albumId, int $artistaId): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO Album_Artista (album_id, artista_id) VALUES (:album, :artista)'
        );

        try {
            $stmt->execute([':album' => $albumId, ':artista' => $artistaId]);

            return true;
        } catch (PDOException $e) {
            if ($this->esClaveDuplicada($e)) {
                return false;
            }
            throw $e;
        }
    }

    public function desvincular(int $albumId, int $artistaId): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM Album_Artista WHERE album_id = :album AND artista_id = :artista'
        );
        $stmt->execute([':album' => $albumId, ':artista' => $artistaId]);

        return $stmt->rowCount() > 0;
    }

    private function esClaveDuplicada(PDOException $e): bool
    {
        // MySQL/MariaDB usa el código 1062; SQLite (que usan las pruebas) lo
        // informa solo en el mensaje.
        return ($e->errorInfo[1] ?? null) === 1062
            || str_contains($e->getMessage(), 'UNIQUE constraint failed');
    }
}
