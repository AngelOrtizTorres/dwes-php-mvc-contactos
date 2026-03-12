<?php
namespace App\Models;

class UserModel extends DBAbstractModel
{
    private $id;
    private $username;
    private $password;

    public function setUsername($u) { $this->username = $u; }
    public function setPassword($p) { $this->password = $p; }

    public function get($id = '')
    {
        try {
            $this->query = "SELECT * FROM users WHERE id = :id";
            $this->parametros = ['id' => $id];
            $this->get_results_from_query();
            return $this->rows[0] ?? null;
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    // Obtener usuario por nombre de usuario
    public function getByUsername(string $username)
    {
        try {
            $this->query = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $this->parametros = ['username' => $username];
            $this->get_results_from_query();
            return $this->rows[0] ?? null;
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    public function set()
    {
        try {
            $this->query = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $this->parametros = [
                'username' => $this->username,
                'password' => $this->password
            ];
            $this->execute_single_query();
            return true;
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    // No usado pero requerido por la abstracción
    public function edit()
    {
        try {
            $this->query = "UPDATE users SET password = :password WHERE id = :id";
            $this->parametros = ['password' => $this->password, 'id' => $this->id];
            $this->execute_single_query();
            return true;
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    public function delete($id = '')
    {
        try {
            $this->query = "DELETE FROM users WHERE id = :id";
            $this->parametros = ['id' => $id];
            $this->execute_single_query();
            return ($this->getAffectedRows() > 0);
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }

    // Obtener todos los usuarios (para scripts de administración)
    public function getAll()
    {
        try {
            $this->query = "SELECT id, username, password, created_at FROM users ORDER BY id ASC";
            $this->parametros = [];
            $this->get_results_from_query();
            return $this->rows;
        } catch (\Exception $e) {
            $error = new DatabaseException("Error en BD: " . $e->getMessage());
            $error->logError();
            throw $error;
        }
    }
}
