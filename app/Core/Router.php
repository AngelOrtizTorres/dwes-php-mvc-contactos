<?php
/**
 * Router
 *
 * Clase encargada de definir, almacenar y resolver las rutas de la aplicación.
 * Permite registrar rutas GET y POST, asociarlas a controladores y acciones,
 * y realizar el matching de la ruta solicitada con las rutas definidas.
 *
 * @package App\Core
 */


namespace App\Core;


class Router
{
    /*****************************************************
     * TAREA 3
     * 
     * Comenta adecuadamente cada uno de los métodos de la clase.
     * 
     * FIN TAREA
    */
    private $routes = [];
    private $basePath = '';

    /**
     * Establece el path base para todas las rutas.
     * @param string $basePath
     */
    public function setBasePath($basePath) {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * Registra una ruta GET.
     * @param string $path
     * @param array $handler
     * @param array $middlewares
     */
    public function get(string $path, array $handler, array $middlewares = []): void {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    /**
     * Registra una ruta POST.
     * @param string $path
     * @param array $handler
     * @param array $middlewares
     */
    public function post(string $path, array $handler, array $middlewares = []): void {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }


    /**
     * Añade una ruta a la colección interna.
     * @param string $method
     * @param string $path
     * @param array $handler
     * @param array $middlewares
     */
    private function addRoute(string $method, string $path, array $handler, array $middlewares = []): void
    {
        $normalizedPath = $this->basePath . '/' . ltrim($path, '/');
        $pattern = $this->convertPathToRegex($normalizedPath);

        $this->routes[] = [
            'method'      => strtoupper($method),
            'pattern'     => $pattern,
            'handler'     => $handler,
            'middlewares' => $middlewares,
        ];
    }

    /**
     * Convierte una ruta con parámetros en una expresión regular.
     * @param string $path
     * @return string
     */
    private function convertPathToRegex($path)
    {
        $pattern = preg_quote($path, '#');
        $pattern = preg_replace('/\\{([a-zA-Z_][a-zA-Z0-9_]*)\\}/', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $pattern . '$#';
    }

    /**
     * Busca una ruta que coincida con el método y URI dados.
     * @param string $method
     * @param string $uri
     * @return array|null
     */
    public function match(string $method, string $uri): ?array
    {
        $method = strtoupper($method);
        $path   = $this->cleanUri($uri); 

        // Recorre todas las rutas registradas
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['pattern'], $path, $matches)) {
                // Extrae los parámetros nombrados
                $params = [];
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $params[$key] = $value;
                    }
                }
                return [
                    'handler' => $route['handler'],
                    'params'  => $params,
                    'middlewares' => $route['middlewares'],
                ];
            }
        }
        // No se encontró coincidencia
        return null;
    }

    /**
     * Limpia y normaliza el URI recibido.
     * @param string $uri
     * @return string
     */
    private function cleanUri($uri)
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = '/' . trim($path, '/');
        
        if ($this->basePath && strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
            $path = '/' . trim($path, '/');
        }
        
        return $path === '' ? '/' : $path;
    }
}