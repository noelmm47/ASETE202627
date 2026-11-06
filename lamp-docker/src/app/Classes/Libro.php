<?php
declare(strict_types=1);

namespace App\Classes;
//require_once __DIR__ . '/MaterialBiblioteca.php';


class Libro extends MaterialBiblioteca {

    private string $isbn;
    private string $editorial='';

    public function getIsbn(): string { return $this->isbn; }

    public function getEditorial(): string {return $this->editorial;}
    public function setEditorial(string $editorial): void {$this->editorial = $editorial;}

    public function tipoMaterial(): string { return 'Libro'; }

    public function mostrarFicha(): string {
        return parent::mostrarFicha() . ', ISBN: ' . $this->isbn;
    }

    public function resumen(): string{
        return parent::resumen();
    }

    public function __construct(
        string $titulo,
        string $autor,
        ?int $anio,
        string $isbn,
        string $editorial,
        bool $disponible = true,
        int $id = 0,
    ) {
        parent::__construct($titulo, $autor, $anio, $disponible, $id);
        $this->isbn = $isbn;
        $this->setEditorial($editorial);
    }
}