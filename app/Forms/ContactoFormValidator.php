<?php
namespace App\Forms;

class ContactoFormValidator
{
    public function validate(array $data): array
    {
        $errors = [];
        
        if (empty($data['nombre']) || mb_strlen($data['nombre']) < 2) {
            $errors['nombre'] = 'El nombre es obligatorio (mín. 2 caracteres)';
        }

        /*****************************************************
         * TAREA 3
         * 
         * Validación de correo electrónico 
         * 
         * FIN TAREA
        */
        $email = $data['email'] ?? '';
        if (empty($email)) {
            $errors['email'] = 'El email es obligatorio';
        } else {
            // Sanitize simple y validar formato
            $cleanEmail = filter_var(mb_strtolower($email), FILTER_SANITIZE_EMAIL);
            if (!filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'El email no tiene un formato válido';
            }
        }
        $soloNumeros = preg_replace('/[^\d]/', '', $data['telefono'] ?? '');
        if (strlen($soloNumeros) < 9) {
            $errors['telefono'] = 'El teléfono debe tener al menos 9 dígitos';
        }

        return $errors;
    }
}