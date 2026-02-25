# Hito 8: Buenas prácticas en vistas y experiencia de usuario

## Preguntas y respuestas

### DRY (Don't Repeat Yourself): ¿Qué ventaja tiene haber separado el nav_view.php del resto de las páginas si mañana decidimos cambiar el color de la barra de navegación?

Separar nav_view.php permite modificar la barra de navegación en un solo archivo y que el cambio se refleje en todas las páginas, evitando repetir el mismo código y facilitando el mantenimiento.

### Seguridad en la Vista: En los archivos entregados se usa htmlspecialchars(). ¿Por qué es obligatorio usarlo al imprimir variables como el nombre o el email del contacto?

Es obligatorio para evitar ataques XSS. Si no usamos htmlspecialchars(), un usuario podría inyectar código malicioso que se ejecutaría en el navegador de otros usuarios.

### Inyección de contenido: ¿Cómo sabe el archivo base_view.php qué contenido debe mostrar en la variable $content? (Relaciónalo con el Hito 5 y el Buffer de salida).

base_view.php utiliza la variable $content, que normalmente se genera usando buffers de salida (ob_start y ob_get_clean) en el controlador. Así, el controlador "inyecta" el contenido de la vista específica en la plantilla base, permitiendo separar estructura y contenido.

### Interactividad: Observa cómo se gestionan los mensajes de éxito (success=created). ¿Cómo ayudamos al usuario a saber que su acción ha funcionado sin que tenga que revisar la base de datos?

Mostrando mensajes de éxito visibles en la interfaz tras una acción (por ejemplo, "Contacto creado correctamente"), el usuario recibe feedback inmediato de que su acción ha sido exitosa, mejorando la experiencia y evitando dudas.
