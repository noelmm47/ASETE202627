<?php
declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class MaterialYaPrestadoException extends RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Ya se le ha prestado el material con id {$id}");
    }
}