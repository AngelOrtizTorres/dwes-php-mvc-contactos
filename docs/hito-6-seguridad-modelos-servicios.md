# Hito 6: Seguridad y buenas prácticas en modelos y servicios

## Preguntas y respuestas

### Seguridad (PDO): ¿Por qué debemos usar $stmt->prepare() y pasar los parámetros en un array en lugar de concatenar las variables directamente en el string de la consulta?

Usar $stmt->prepare() y pasar los parámetros en un array previene inyecciones SQL, ya que los valores se envían de forma segura y el motor de la base de datos los trata como datos, no como parte del código SQL. Concatenar variables directamente puede permitir que un usuario malicioso ejecute código no deseado.

### Excepciones: En ContactoModel, cuando ocurre un error, llamamos a $error->logError(). ¿Dónde podemos consultar ese log para saber qué ha fallado exactamente?

El log de errores suele guardarse en la carpeta logs/ del proyecto. Allí se pueden revisar los archivos de log generados para ver detalles de los errores ocurridos y facilitar la depuración.

### Mapeo: ¿Qué ventaja tiene que el ContactoService limpie y formatee los datos antes de enviarlos al controlador?

Permite que el controlador reciba datos ya validados y en el formato adecuado, separando responsabilidades y evitando duplicar lógica de limpieza/formateo en varias partes del código. Así, el controlador puede centrarse en la lógica de presentación.

### Patrón Singleton: ¿Qué pasaría con los recursos del servidor si cada vez que un modelo necesita una consulta creara una nueva conexión new PDO()?

Se consumirían muchos más recursos, ya que cada conexión adicional implica más uso de memoria y procesamiento en el servidor y la base de datos. El patrón Singleton permite reutilizar una única conexión, optimizando el rendimiento y evitando saturar el servidor.
