<?php
declare(strict_types=1);

namespace App\Repos;

/*require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/Libro.php';
require_once __DIR__ . '/../classes/Revista.php';
require_once __DIR__ . '/../classes/DVD.php';*/
use App\Classes\DVD;
use App\Classes\Libro;
use App\Classes\MaterialBiblioteca;
use App\Classes\Revista;
use App\Config\db;

class MaterialRepo {

    public function findAll(): array {
        $stmt = db()->query('SELECT * FROM materiales ORDER BY tipo, titulo');
        $materiales = [];
        foreach ($stmt->fetchAll() as $fila) {
            $materiales[] = $this->hidratar($fila);
        }
        return $materiales;
    }

    public function findById(int $id): ?MaterialBiblioteca {
        $stmt = db()->prepare('SELECT * FROM materiales WHERE id = ?');
        $stmt->execute([$id]);
        $fila = $stmt->fetch();
        return $fila ? $this->hidratar($fila) : null;
    }

    /**
     * Convierte una fila de BBDD en el objeto PHP correcto.
     * También hidrata la fecha de creación desde 'creado_en'.
     */
    private function hidratar(array $fila): MaterialBiblioteca {
        $material = match ($fila['tipo']) {
            'libro'   => new Libro(
                $fila['titulo'], $fila['autor'], (int)$fila['anio'],
                $fila['isbn'] ?? '', "", (bool)$fila['disponible'], (int)$fila['id']
            ),
            'revista' => new Revista(
                $fila['titulo'], $fila['autor'], (int)$fila['anio'],
                (int)$fila['numero'], (bool)$fila['disponible'], (int)$fila['id']
            ),
            'dvd'     => new DVD(
                $fila['titulo'], $fila['autor'], (int)$fila['anio'],
                (int)$fila['duracion_min'], (bool)$fila['disponible'], (int)$fila['id']
            ),
            default   => throw new RuntimeException("Tipo desconocido: {$fila['tipo']}"),
        };
        $material->setFechaCreacion($fila['creado_en'] ?? null);
        return $material;
    }

    public function setDisponible(int $id, bool $disponible): void {
        $stmt = db()->prepare('UPDATE materiales SET disponible = ? WHERE id = ?');
        $stmt->execute([$disponible ? 1 : 0, $id]);
    }

    public function registrarPrestamo(int $materialId, int $usuarioId): void {
        $stmt = db()->prepare(
            'INSERT INTO prestamos (material_id, usuario_id) VALUES (?, ?)'
        );
        $stmt->execute([$materialId, $usuarioId]);
    }

    public function findDisponibles(): array{
        $stmt= db()->query('SELECT * from materiales where disponible=1 ORDER BY titulo');
        $materiales=[];
        foreach($smtm->fetchAll() as $material){
            $materiales= hidratar($material);
        }
        return $materiales;
    }

    public function findByTipo(String $tipo): array{
        $smtm= bd() -> prepare('SELECT * FROM materiales WHERE tipo=? ORDER BY titulo') -> execute([$tipo]);
        $materiales=[];

        foreach($smtm->fetchAll() as $material){
            $materiales= hidratar($material);
        }
        return $materiales;
    }

    public function contarPorTipo(): array{
        $smtm= bd()->query('SELECT tipo, COUNT(*) as cantidad FROM materiales GROUP BY tipo');
        $out= [];
        foreach($smtm->fetchAll() as $element){
            $out[$element['tipo']]= (int)$elemento['cantidad']; //tipo -> cantidad
        }
        return $out;
    }

    public function crear(MaterialBiblioteca $m): int{
        $material = match($m->tipoMaterial()){
            'Libro' => new Libro($m->$titulo,
                                $m->$autor,
                                $m->$anio,
                                $m->$isbn,
                                $m->$editorial,
                                $m->$disponible ?: false),
            'Revista' => new Revista($m->$titulo,
                                    $m-> $autor,
                                    $m -> $anio,
                                    $m-> $numero,
                                    $m-> $disponible?: false),
            'DVD' => new DVD($m -> $titulo,
                            $m -> $autor,
                            $m -> $anio,
                            $m-> $duracionMin,
                            $m-> $disponible?: false)
        };
        $material->setFechaCreacion(date("Y/m/d"));
        persistencia($material); 
        return (int)(bd()->query('SELECT id FROM materiales WHERE id = (SELECT COUNT(*) FROM materiales)')->fetch()); //TODO ¿Funciona bien?
    }

    private function persistencia(MaterialBiblioteca $m):void{ //TODO probar
        $tipo= $m->tipoMaterial();
        $params= match($tipo){
            'Libro' => [$m->$titulo,
                                $m->$autor,
                                $m-> $anio,
                                $m-> $isbn,
                                $m-> $editorial,
                                $m-> $disponible],
            'Revista' => [$m->$titulo,
                                    $m-> $autor,
                                    $m -> $anio,
                                    $m-> $numero,
                                    $m-> $disponible],
            'DVD' => [$m -> $titulo,
                            $m -> $autor,
                            $m -> $anio,
                            $m-> $duracionMin,
                            $m-> $disponible]
        };

        if($tipo == 'Libro'){
            persistenciaLibro($params);
        }elseif($tipo=='Revista'){
            persistenciaRevista($params);
        }elseif($tipo=='DVD'){
            persistenciaDVD($params);
        }

        echo 'Éxito.';
    }

    private function persistenciaLibro(array $params):void{
        $stmt= bd()->prepare('INSERT INTO material(titulo, autor, anio, isbn, editorial, disponible) VALUES ?, ?, ?, ?, ?, ?');
        $smtm -> execute($params);
    }

    private function persistenciaRevista(array $params):void{
        $stmt= bd()->prepare('INSERT INTO material(titulo, autor, anio, numero, disponible) VALUES ?, ?, ?, ?, ?');
        $smtm -> execute($params);
    }

    private function persistenciaDVD(array $params):void{
        $stmt= bd()->prepare('INSERT INTO material(titulo, autor, anio, duracionMin, disponible) VALUES ?, ?, ?, ?, ?');
        $smtm -> execute($params);
    }
}