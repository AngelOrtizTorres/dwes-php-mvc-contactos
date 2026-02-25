# Hito 1: Dependencias y variables de entorno

## Seguridad: Hemos creado un `.env` y un `.env.example`. ¿Por qué es necesario que el `.env.example` sí esté en Git y el `.env` no?

`.env.example` es una plantilla sin valores secretos; debe subirse para que otros desarrolladores sepan qué variables configurar. `.env` contiene credenciales reales y no debe subirse por seguridad.

## Verificación: Si al ejecutar `git status` ves el archivo `.env` en la lista de archivos para agregar, ¿qué significa y qué desastre podrías causar si haces `git push`?

Significa que `.env` está sin ignorar o ha sido añadido al área de staging; si haces `git push` expondrás credenciales (DB, API keys) y podrías comprometer sistemas y datos.

## Autoloading: Gracias al PSR-4, ¿qué ventaja tenemos ahora a la hora de crear nuevas clases en `app/Controllers` respecto al uso tradicional de `require_once`?

PSR-4 permite autoload automático: basta crear la clase con su namespace y Composer la cargará, evitando `require_once` manual y reduciendo errores y boilerplate.

## Dependencias: ¿Para qué sirve el archivo `composer.lock` que se ha generado automáticamente? ¿Debería estar incluido en nuestro `.gitignore`?

`composer.lock` fija las versiones exactas instaladas para reproducibilidad entre entornos. Debe comitearse (no incluirse en `.gitignore`) para garantizar instalaciones iguales en todos los entornos.
