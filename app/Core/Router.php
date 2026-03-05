<?php
/*****************************************************
 * TAREA 1: Bloque de documentación
 *
 * CLASE ROUTER
 *
 * Gestiona la definición y la correspondencia de las rutas de la aplicación.
 * Responsabilidades:
 * 1. Registrar rutas para diferentes métodos HTTP (GET, POST).
 * 2. Convertir las rutas amigables (ej: /user/{id}) en expresiones regulares.
 * 3. Comparar la URI de la petición actual con las rutas registradas para encontrar una coincidencia.
 */

/*****************************************************
 * TAREA 2: Espacio de nombres
*/
namespace App\Core;

class Router
{
    /**
     * Almacena todas las rutas registradas en la aplicación.
     */
    private $routes = [];

    /**
     * Almacena el prefijo de la URL si el proyecto está en un subdirectorio.
     */
    private $basePath = '';

    /**
     * TAREA 3: Comentarios de métodos
     * Establece la ruta base si la aplicación no está en la raíz del dominio.     */
    public function setBasePath($basePath) {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * Registra una nueva ruta para el método HTTP GET.
     */
    public function get(string $path, array $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Registra una nueva ruta para el método HTTP POST.
     */
    public function post(string $path, array $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Método privado que añade la ruta al array de rutas.
     */
    private function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[] = [
            'method'      => strtoupper($method),
            'path'        => $path, // Guardamos la ruta original para referencia
            'pattern'     => $this->convertPathToRegex($path), // La ruta convertida a regex
            'handler'     => $handler,
        ];
    }

    /**
     * Convierte una ruta con parámetros (ej: /user/{id}) a una expresión regular.
     *
     */
    private function convertPathToRegex($path)
    {
        // Escapa los caracteres especiales de la ruta para usarlos en la regex.
        $pattern = preg_quote($path, '#');
        // Reemplaza los parámetros {param} por grupos de captura con nombre.
        $pattern = preg_replace('/\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\}/', '(?P<$1>[^/]+)', $pattern);
        // Devuelve la regex completa, anclada al inicio y al final.
        return '#^' . $pattern . '$#';
    }

    /**
     * Busca una coincidencia entre la petición actual y las rutas registradas.
     *
     */
    public function match(string $method, string $uri): ?array
    {
        $method = strtoupper($method);
        $path   = $this->cleanUri($uri); 

        /*****************************************************
         * TAREA 4: Implementación del algoritmo de coincidencia
        */
        foreach ($this->routes as $route) {
            // 1. Comparamos el método HTTP. Si no coincide, pasamos a la siguiente ruta.
            if ($route['method'] !== $method) {
                continue;
            }

            // 2. Usamos preg_match para ver si la URI coincide con el patrón de la ruta.
            if (preg_match($route['pattern'], $path, $matches)) {
                $params = [];
                // 3. Extraemos los parámetros con nombre de la URL (ej: 'id' => 123).
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }
                
                // 4. Devolvemos el manejador y los parámetros extraídos.
                return [
                    'handler' => $route['handler'],
                    'params'  => $params,
                ];
            }
        }

        // 5. Si el bucle termina, no se encontró ninguna ruta.
        return null;
    }

    /**
     * Limpia la URI para eliminar el query string y la base path.
     *
     */
    private function cleanUri($uri)
    {
        // Nos quedamos solo con la parte de la ruta, ignorando parámetros GET (?id=1).
        $path = parse_url($uri, PHP_URL_PATH);
        
        // Si la aplicación está en un subdirectorio, lo eliminamos de la ruta.
        if ($this->basePath && strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
        }
        
        // Devolvemos la ruta normalizada o '/' si está vacía.
        return $path ?: '/';
    }
}