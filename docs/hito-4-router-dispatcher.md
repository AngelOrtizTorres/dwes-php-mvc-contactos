# Hito 4: Router y Dispatcher

## Responsabilidades

Dividir el trabajo entre Router y Dispatcher sigue el principio de responsabilidad única. El Router se encarga de analizar la petición y decidir qué controlador y acción deben ejecutarse, mientras que el Dispatcher se encarga de instanciar el controlador y ejecutar la acción. Si el Router también instanciara los controladores, se mezclarían responsabilidades y el código sería menos mantenible y más difícil de testear.

## Dinamicidad

El Dispatcher utiliza variables para crear objetos dinámicamente (`new $controllerName()`). Esto permite añadir nuevos controladores sin modificar el Dispatcher, haciendo el sistema más flexible y escalable. Si se usara un switch gigante, cada nuevo controlador requeriría modificar el Dispatcher, lo que va en contra de la extensibilidad y el principio abierto/cerrado.

## Limpieza de URL

Cuando un usuario accede a `/contactos/crear?origen=web`, el Router debe ignorar la parte de la query string (`?origen=web`) para encontrar la ruta correcta. Si no lo hiciera, no coincidiría con la ruta definida y devolvería un error 404, aunque la ruta base exista. Limpiar la URL garantiza que la lógica de enrutamiento funcione correctamente independientemente de los parámetros adicionales.
