# Hito 2: El arranque. Bootstrap y configuración

**Separación de responsabilidades:** ¿Por qué es mejor tener rutas como `VIEWS_DIR` en un `config.php` en lugar de mezcladas en `bootstrap.php`?
- Centraliza la configuración: un único punto de verdad facilita cambiar rutas sin tocar la lógica de arranque.
- Facilita entornos: permite sobreescribir valores por entorno (desarrollo, testing, producción) sin alterar el bootstrap.
- Mejora la mantenibilidad y las pruebas: el bootstrap queda para inicializar servicios; la config para parámetros modificables.
- Reduce efectos secundarios: evita que la inicialización ejecute lógica por accidente al cambiar una ruta.

**Entorno de errores:** ¿Qué peligro supone dejar la librería Whoops activada cuando `APP_ENV=production`?
- Whoops muestra trazas completas, variables de entorno y rutas de archivos en la pantalla: esto puede revelar credenciales, estructuras internas y puntos de entrada a atacantes.
- En producción debe usarse una página de error genérica y registro seguro (logs), no un depurador interactivo.

**Automatización:** El bootstrap crea carpetas automáticamente. ¿Cómo ayuda esto a otro desarrollador que descargue el proyecto por primera vez desde GitHub?
- Evita pasos manuales: el desarrollador no necesita crear `uploads`, `cache` o `logs` a mano.
- Reduce errores de arranque: previene fallos por directorios inexistentes (subida de ficheros, caché, logs).
- Mejora onboarding: facilita que la app funcione tras clonar y configurar el `.env`, acelerando pruebas locales.

**Variables críticas:** En el bloque `try-catch` del Dotenv se usa el método `required()`. ¿Qué ocurre si borras la variable `DBNAME` de tu `.env` e intentas arrancar la app?
- `required()` hace que Dotenv lance una excepción al faltar la variable requerida; la aplicación fallará al arrancar y el error será capturado por el `catch`.
- Resultado práctico: la app no iniciará con una configuración de base de datos incompleta (evita comportamientos silenciosos y datos corruptos).
- Recomendación: documentar las variables requeridas y proporcionar valores por defecto razonables o mensajes claros de error para facilitar la resolución.
