<?php
declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class LimitePrestamosException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Se ha alcanzado el máximo de préstamos. No puede tomar prestado nada más.");
    }
}