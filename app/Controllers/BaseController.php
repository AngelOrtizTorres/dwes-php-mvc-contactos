<?php 
/*****************************************************
 * TAREA 1
 * 
 * Incluye bloque de documentación del archivo. 
 * 
 * FIN TAREA
*/

/*****************************************************
 * TAREA 2
 * 
 * Documenta cada una de los métodos de la clase. 
 * 
 * FIN TAREA
*/


namespace App\Controllers;

use App\Services\ContactoService;


class BaseController {

    public function __construct() {
       
    }
    
    /**
     * Comprueba si hay un usuario autenticado en la sesión.
     */
    protected function isLoggedIn(): bool {
        return isset($_SESSION) && !empty($_SESSION['user']);
    }

    /**
     * Fuerza el acceso solo a usuarios autenticados.
     * Redirige al formulario de login si no hay sesión.
     */
    protected function requireLogin(): void {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }
    
 
    public function renderHTML($fileName, $data = []) {

        if (!file_exists($fileName)) {
            $this->mostrarError("La vista solicitada no existe: " . basename($fileName), 500);
            return;
        }
        

        $helpersPath = VIEWS_DIR . '/helpers/main_helper.php';
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }

        
        /*****************************************************
         * TAREA 3
         * 
         * Comenta las siguientes líneas de código justificando la necesidad de utilizar ob_start() 
         * 
         * FIN TAREA
        */ 

        ob_start();
        include $fileName;
        $content = ob_get_clean();

        $titulo_pagina = $data['titulo_pagina'] ?? $data['titulo'] ?? 'Agenda de Contactos';


        $layoutPath = VIEWS_DIR . '/layouts/base_view.php';
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            echo $content; // Fallback si no hay layout
        }
    }
    

    protected function redirect($url) {
        $fullUrl = (strpos($url, 'http') === 0) ? $url : BASE_URL . $url;
        header('Location: ' . $fullUrl);
        exit;
    }

    public function mostrarError($mensaje, $codigo = 404) {
        http_response_code($codigo);
        $data = [
            'titulo'  => 'Ups! Algo ha ido mal',
            'mensaje' => $mensaje,
            'codigo'  => $codigo
        ];
        // Si es un 404, intentamos obtener algunos contactos recientes para ayudar al usuario
        if ($codigo === 404) {
            try {
                $svc = new ContactoService();
                $data['ultimos_contactos'] = $svc->getUltimosContactos(3);
                $data['total_contactos'] = $svc->getTotalContactos();
            } catch (\Exception $e) {
                // Silencioso: si falla el servicio no rompemos la página de error
            }
        }

        include VIEWS_DIR . '/errors/general_error.php';
        exit;
    }

    public function mostrarErrorDB($mensaje) {
        http_response_code(500);
        $data = [
            'titulo'  => 'Error de Base de Datos',
            'mensaje' => $mensaje
        ];
        include VIEWS_DIR . '/errors/db_error.php';
        exit;
    }
}