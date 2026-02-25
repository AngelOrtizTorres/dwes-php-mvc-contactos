# Sistema de Gestión de Contactos en PHP (Arquitectura MVC)

## Documentación Hito 0: Infraestructura y servidor.

### Seguridad: ¿Por qué configuramos el DocumentRoot en `/public` y no en la raíz del proyecto donde están las carpetas `app` o `config`?

Configurar el DocumentRoot en `/public` es fundamental para proteger la estructura interna del proyecto. De esta forma, solo los archivos públicos como assets (CSS, JS, imágenes) y el archivo `index.php` son accesibles desde el navegador, mientras que carpetas sensibles como `app` y `config` quedan fuera del alcance directo de usuarios externos. Esto previene posibles ataques, como la exposición de código fuente o configuraciones privadas, y refuerza la seguridad al limitar el acceso únicamente a los recursos necesarios para ejecutar la aplicación.

### Git: ¿Por qué es una mala práctica subir la carpeta `vendor/` o el archivo `.env` a GitHub?

Subir la carpeta `vendor/` a GitHub es una mala práctica porque contiene dependencias externas que pueden ser fácilmente instaladas mediante Composer, lo que evita aumentar innecesariamente el tamaño del repositorio y posibles conflictos de versiones. Por otro lado, el archivo `.env` almacena información sensible como credenciales, claves API y configuraciones específicas del entorno; compartirlo públicamente puede exponer datos privados y comprometer la seguridad de la aplicación.

### Organización: ¿Qué diferencia esperas encontrar entre los archivos guardados en `app/Controllers` y los guardados en `views/`?

- `app/Controllers`: Aquí se almacena la lógica de la aplicación y el manejo de las peticiones del usuario. Los controladores reciben las solicitudes, procesan los datos y deciden qué vista mostrar o qué acción realizar.

- `views/`: En esta carpeta se guardan las plantillas encargadas de mostrar la información al usuario. Las vistas contienen el código HTML y PHP necesario para presentar los datos procesados por los controladores de forma visual y estructurada.
