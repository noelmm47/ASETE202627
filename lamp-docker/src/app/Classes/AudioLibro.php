<?php
declare(strict_types=1);

namespace App\Classes;

//require_once __DIR__ . '/Libro.php';

public class AudioLibro
{ //} extends Libro{
    private $duracionMin int;

    public function __construct(
        string $titulo,
        string $autor,
        ?int $anio,
        string $isbn,
        string $editorial,
        bool $disponible = true,
        int $id = 0, int $duracionMin
    ) {
        parent::__construct($titulo, $autor, $anio, $disponible, $id, $isbn, $editorial);
        $this->duracionMin = $duracionMin;
    }

    public function getDuracionMinima(): int
    {
        return $this->duracionMin;
    }
    
    public function mostrarFicha(): string
    {
        return parent::mostrarFicha() . ', Duración mínima: ' . $this->duracionMin;
    }

    public function resumen(): string
    {
        return substr(parent()->resumen(), 0, -1) . ', Duración mínima: ' . $this->duracionMin, ')';
        //Substr devuelve el resumen del padre menos el último caracter (el parámetro 'length' negativo te devuelve el string menos 'length' caracteres desde el final)
        //Así elimino el paréntesis final que hay en el resumen del padre, añadiendo más información de forma que quede más o menos natural.
    }
}
?>