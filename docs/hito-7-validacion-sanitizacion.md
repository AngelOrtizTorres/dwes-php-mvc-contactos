# Hito 7: Validación y sanitización de formularios

## Preguntas y respuestas

### Sanitización vs Validación: ¿Cuál es la diferencia? ¿Por qué es necesario limpiar los datos (Sanitizer) antes de comprobar si son válidos (Validator)?

La sanitización consiste en limpiar los datos de entrada para eliminar caracteres peligrosos o no deseados, mientras que la validación verifica que los datos cumplen con los requisitos esperados (formato, longitud, etc.). Es necesario limpiar antes de validar para evitar que datos maliciosos o incorrectos pasen la validación o causen problemas de seguridad.

### XSS (Cross-Site Scripting): ¿Qué ocurriría si no usáramos htmlspecialchars al mostrar los datos que el usuario escribió mal en el formulario?

Si no usamos htmlspecialchars, un usuario podría inyectar código JavaScript malicioso en el formulario, lo que permitiría ataques XSS y pondría en riesgo la seguridad de la aplicación y de los usuarios.

### Experiencia de Usuario: ¿Por qué es importante devolver los datos originales al formulario cuando hay un error (repoblar el formulario) en lugar de dejar los campos vacíos?

Es importante para no frustrar al usuario: si hay un error y los campos quedan vacíos, el usuario tendría que volver a escribir toda la información. Repoblar el formulario mejora la experiencia y reduce errores.

### Responsabilidad: ¿Por qué crees que es mejor tener la validación en clases separadas en lugar de escribir todos los if directamente dentro del Controlador?

Separar la validación en clases especializadas mejora la organización, reutilización y mantenibilidad del código. Así, el controlador se centra en la lógica de flujo y presentación, y la validación puede evolucionar o reutilizarse fácilmente en otros contextos.
