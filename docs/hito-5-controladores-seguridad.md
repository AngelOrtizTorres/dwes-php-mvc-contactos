# Hito 5: Controladores y Seguridad

## Preguntas y respuestas

### Herencia: ¿Por qué es útil que todos los controladores hereden de BaseController? ¿Qué código nos estamos ahorrando repetir en IndexController y ContactoController?

La herencia permite que todos los controladores compartan funcionalidades comunes como el renderizado de vistas, manejo de errores y redirecciones. Así, evitamos repetir código en cada controlador y centralizamos la lógica compartida en BaseController.

### Buffers de salida: ¿Para qué sirve ob_start()? ¿Qué pasaría si hiciéramos un include de la vista directamente sin usar el buffer?

ob_start() se utiliza para capturar la salida de las vistas antes de enviarla al navegador, permitiendo modificar o gestionar el contenido antes de mostrarlo. Si incluyéramos la vista directamente sin buffer, cualquier error o contenido se enviaría inmediatamente al navegador, dificultando el control de la respuesta.

### Seguridad en POST: ¿Por qué en métodos como storeAction o updateAction comprobamos obligatoriamente que el método de la petición sea POST?

Es fundamental comprobar que el método de la petición sea POST para evitar que datos sensibles sean enviados mediante GET, lo que podría exponer información en la URL y permitir ataques CSRF.

### Limpieza de datos: El controlador usa un método llamado sanitizeForOutput. ¿Por qué no debemos mostrar directamente en el HTML lo que el usuario escribió en un formulario?

El método sanitizeForOutput se usa para limpiar los datos antes de mostrarlos en el HTML, previniendo ataques XSS. Nunca debemos mostrar directamente lo que el usuario escribió, ya que podría incluir scripts maliciosos.
