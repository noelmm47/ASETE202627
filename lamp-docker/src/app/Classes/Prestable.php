<?php
declare(strict_types=1);

namespace App\Classes;

interface Prestable
{
    public function prestar(): bool;
    public function devolver(): bool;
    public function diasPrestamo(): int;
    public function puedePrestarse(): bool;
}