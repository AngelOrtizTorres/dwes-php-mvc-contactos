# Sistema de Gestión de Contactos en PHP (Arquitectura MVC)

## Documentación Hito 0: Infraestructura y servidor.

### Seguridad: ¿Por qué configuramos el DocumentRoot en `/public` y no en la raíz del proyecto donde están las carpetas `app` o `config`?

Configurar el DocumentRoot en `/public` es fundamental para proteger la estructura interna del proyecto. De esta forma, solo los archivos públicos como assets (CSS, JS, imágenes) y el archivo `index.php` son accesibles desde el navegador, mientras que carpetas sensibles como `app` y `config` quedan fuera del alcance directo de usuarios externos. Esto previene posibles ataques, como la exposición de código fuente o configuraciones privadas, y refuerza la seguridad al limitar el acceso únicamente a los recursos necesarios para ejecutar la aplicación.

### Git: ¿Por qué es una mala práctica subir la carpeta `vendor/` o el archivo `.env` a GitHub?

Subir la carpeta `vendor/` a GitHub es una mala práctica porque contiene dependencias externas que pueden ser fácilmente instaladas mediante Composer, lo que evita aumentar innecesariamente el tamaño del repositorio y posibles conflictos de versiones. Por otro lado, el archivo `.env` almacena información sensible como credenciales, claves API y configuraciones específicas del entorno; compartirlo públicamente puede exponer datos privados y comprometer la seguridad de la aplicación.

### Organización: ¿Qué diferencia esperas encontrar entre los archivos guardados en `app/Controllers` y los guardados en `views/`?

- `app/Controllers`: Aquí se almacena la lógica de la aplicación y el manejo de las peticiones del usuario. Los controladores reciben las solicitudes, procesan los datos y deciden qué vista mostrar o qué acción realizar.

- `views/`: En esta carpeta se guardan las plantillas encargadas de mostrar la información al usuario. Las vistas contienen el código HTML y PHP necesario para presentar los datos procesados por los controladores de forma visual y estructurada.

## Documentación Hito 1: Dependencias y variables de entorno.

### Seguridad: Hemos creado un `.env` y un `.env.example`. ¿Por qué es necesario que el `.env.example` sí esté en Git y el `.env` no?

`.env.example` es una plantilla sin valores secretos; debe subirse para que otros desarrolladores sepan qué variables configurar. `.env` contiene credenciales reales y no debe subirse por seguridad.

### Verificación: Si al ejecutar `git status` ves el archivo `.env` en la lista de archivos para agregar, ¿qué significa y qué desastre podrías causar si haces `git push`?

Significa que `.env` está sin ignorar o ha sido añadido al área de staging; si haces `git push` expondrás credenciales (DB, API keys) y podrías comprometer sistemas y datos.

### Autoloading: Gracias al PSR-4, ¿qué ventaja tenemos ahora a la hora de crear nuevas clases en `app/Controllers` respecto al uso tradicional de `require_once`?

PSR-4 permite autoload automático: basta crear la clase con su namespace y Composer la cargará, evitando `require_once` manual y reduciendo errores y boilerplate.

### Dependencias: ¿Para qué sirve el archivo `composer.lock` que se ha generado automáticamente? ¿Debería estar incluido en nuestro `.gitignore`?

`composer.lock` fija las versiones exactas instaladas para reproducibilidad entre entornos. Debe comitearse (no incluirse en `.gitignore`) para garantizar instalaciones iguales en todos los entornos.


