<?php
/**
 * Bootstrap del microframework de la práctica DWES
 *
 * Establece constantes de rutas, carga configuración
 * y el autoloader de Composer, y prepara ajustes básicos
 * para la aplicación.
 *
 * TAREAS aplicadas: 1,2,3,4,5,6,7 (comentado código según TAREAs).
 */



// Definición de rutas de la aplicación (TAREA 2)
require_once __DIR__ . '/config/config.php';
require_once VENDOR_DIR . '/autoload.php';
require_once APP_DIR . '/helpers/diasTranscurridos.php';

/**
 * TAREA 4
 *
 * Comenta el siguiente código *
 *
 * FIN TAREA
 */
/*
if (file_exists(APP_DIR . '/helpers/helpers.php')) {
    require_once APP_DIR . '/helpers/helpers.php';
}
*/


/**
 * TAREA 5
 *
 * Comenta el siguiente código *
 *
 * FIN TAREA
 */
use Dotenv\Dotenv;
try {
    $dotenv = Dotenv::createImmutable(APP_ROOT);
    $dotenv->load();
    // Compatibilidad: mapear claves comunes (DB_HOST) a las claves esperadas (DBHOST)
    // Asignar variables desde el .env (Dotenv ya las carga en $_ENV)
    $_ENV['DBHOST'] = $_ENV['DB_HOST'] ?? '';
    $_ENV['DBNAME'] = $_ENV['DB_NAME'] ?? '';
    $_ENV['DBUSER'] = $_ENV['DB_USER'] ?? '';
    $_ENV['DBPASS'] = $_ENV['DB_PASS'] ?? '';
    $_ENV['DBPORT'] = $_ENV['DB_PORT'] ?? '';

    // Validación explícita de variables críticas
    $missing = [];
    foreach (['DBHOST','DBNAME','DBUSER','DBPASS'] as $k) {
        if (empty($_ENV[$k])) $missing[] = $k;
    }
    if (!empty($missing)) {
        throw new Exception('Faltan variables de entorno: ' . implode(', ', $missing));
    }
} catch (Exception $e) {
    die('Fallo crítico en configuración: ' . $e->getMessage());
}


/**
 * TAREA 6
 *
 * Comenta el siguiente código *
 *
 * FIN TAREA
 */
/*
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
if (APP_ENV === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', 0);
}
*/

/**
 * TAREA 7
 *
 * Comenta el siguiente código *
 *
 * FIN TAREA
 */
/*
ini_set('log_errors', 1);
ini_set('error_log', APP_ROOT . '/logs/php_errors.log');
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
date_default_timezone_set($_ENV['TIMEZONE'] ?? 'Europe/Madrid');

// 7. MANTENIMIENTO DE DIRECTORIOS
$requiredDirs = [APP_ROOT . '/logs', APP_ROOT . '/cache', PUBLIC_DIR . '/uploads/contactos'];
foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}
*/



// 9. URL BASE PARA VISTAS
// Fase 11: Definición de URL BASE
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptDir = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));

// Eliminamos barras finales sobrantes para evitar el error de doble barra //
$baseUrl = rtrim($protocol . $host . $scriptDir, '/\\');
define('BASE_URL', $baseUrl);