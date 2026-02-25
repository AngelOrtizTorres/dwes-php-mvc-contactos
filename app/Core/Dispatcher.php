<?php
/**
 * Dispatcher
 *
 * Clase responsable de recibir la ruta resuelta por el router y ejecutar el método correspondiente
 * del controlador, pasando los parámetros necesarios. Gestiona también los errores de rutas y controladores.
 *
 * @package App\Core
 */

namespace App\Core;

/*****************************************************
* TAREA 2
* 
* Comenta adecuadamente cada uno de los métodos de la clase.
* 
* FIN TAREA
*/

class Dispatcher 
{
    /**
     * Ejecuta la acción correspondiente a la ruta proporcionada.
     *
     * @param array|null $route Ruta resuelta por el router, con handler y parámetros.
     * @return mixed Resultado de la acción del controlador o gestión de error.
     */
    public function dispatch(?array $route) 
    {
        if (!$route) {
            return $this->handleNotFound();
        }

        [$controllerName, $actionName] = $route['handler'];
        $params = $route['params'] ?? [];

        // Verifica que el controlador exista
        if (!class_exists($controllerName)) {
            return $this->handleError("El controlador '$controllerName' no existe.");
        }

        $controller = new $controllerName();

        // Verifica que la acción exista en el controlador
        if (!method_exists($controller, $actionName)) {
            return $this->handleError("La acción '$actionName' no existe en el controlador.");
        }

        // Ejecuta la acción del controlador con los parámetros
        return call_user_func_array([$controller, $actionName], $params);
    }

    /**
     * Gestiona el caso en que la ruta no existe (404).
     *
     * @return void
     */
    private function handleNotFound() 
    {
        http_response_code(404);
        $errorManager = new \App\Controllers\BaseController();
        $errorManager->mostrarError("Lo sentimos, la página que buscas no existe.", 404);
    }

    /**
     * Gestiona errores internos del despachador (500).
     *
     * @param string $mensaje Mensaje de error a mostrar.
     * @return void
     */
    private function handleError(string $mensaje) 
    {
        http_response_code(500);
        $errorManager = new \App\Controllers\BaseController();
        $errorManager->renderHTML(VIEWS_DIR . '/errors/general_error.php', ['mensaje' => $mensaje]);
    }
}