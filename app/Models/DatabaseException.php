<?php
namespace App\Models;

/**
 * DatabaseException
 *
 * Excepción específica para errores de base de datos.
 */
class DatabaseException extends \Exception
{
    public function logError()
    {
        error_log("DATABASE ERROR [" . date('Y-m-d H:i:s') . "]: " . $this->getMessage());
    }

    public function getUserMessage()
    {
        return "Error de base de datos. Por favor, intente más tarde.";
    }
}

