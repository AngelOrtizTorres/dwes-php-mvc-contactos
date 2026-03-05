<?php
namespace App\Forms;

/*****************************************************
 * ContactoFormSanitizer
 *
 * Clase responsable de limpiar y normalizar los datos
 * de entrada del formulario de contacto antes de su
 * validación y almacenamiento.
 *
 * (Se mantienen las TAREAs de documentación en el repo.)
 */

class ContactoFormSanitizer
{
    public function sanitize(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            $sanitized[$key] = $this->sanitizeField($key, $value);
        }
        return $sanitized;
    }

 
    public function sanitizeForOutput(array $data): array
    {
        return array_map([$this, 'sanitizeOutputValue'], $data);
    }

    private function sanitizeOutputValue($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'sanitizeOutputValue'], $value);
        }

        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    private function sanitizeField(string $field, $value)
    {
        if (!is_string($value)) return $value;
        $value = trim($value);

        return match ($field) {
            'nombre'   => mb_convert_case(preg_replace('/[^\p{L}\s\-\.\']/u', '', $value), MB_CASE_TITLE, "UTF-8"),
            'telefono' => preg_replace('/[^\d+]/', '', $value),
            'email'    => filter_var(mb_strtolower($value), FILTER_SANITIZE_EMAIL),
            default    => $value,
        };
    }
}