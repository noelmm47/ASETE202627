# Reflexión — Actividad 2.2 (autoloading, namespaces y excepciones)

**Nombre:** Noel Morato Muñoz

## 1. Namespaces: ¿qué problema resuelven? Pon un ejemplo de colisión.

Los namespaces definen un lugar lógico de almacenamiento de manera que desde otros archivos se pueda acceder fácilmente al archivo. Esto es especialmente útil cuando tienes archivos que se llaman igual; su localización lógica los diferencia entre sí y permite usar ambos sin confundirlos.

## 2. PSR-4: explica cómo Composer sabe que `App\Classes\Libro` está en `app/Classes/Libro.php`.

Composer conoce que App\Classes\Libro está en app/Classes/Libro.php porque en el archivo Composer.json hay un campo llamado psr-4 en el que se define la correspondencia entre App\ y app/. Gracias a Composer.json se consigue localizar físicamente los archivos a través de sus namespaces.

## 3. Autoloading: ¿qué diferencia hay entre `require_once` manual y el cargador de Composer?

El require_once manual carga todo el código del archivo especificado a partir de la línea en la que se encuentre la instrucción. El cargador del Composer, por lo que entiendo, carga automáticamente lo que se necesita de los archivos que se incluyen con el "use".

## 4. Excepciones: ¿qué ventaja tiene lanzar `MaterialNoEncontradoException` en lugar de devolver `null`?

Al lanzar MaterialNoEncontradoException el programa tiene una idea más concreta del problema y se puede manejar de forma concreta para que, en vez de mostrar un fatal error, muestre un mensaje amigable al usuario.

## 5. Try/catch: ¿dónde capturas las excepciones y por qué ahí? ¿Qué pasa si no las capturas?

Las excepciones se deben capturar en los puntos de entrada de la web o aplicación, porque funcionan como los controladores del conjunto de archivo que se usan (no estoy seguro, pero deduzco que es por ser los que inician las llamadas de los otros archivos y donde van a llegar todas las excepciones sin hacer un enredo de try-catch en varios lugares diferentes).
Si no se capturan, sale un fatal error que no queremos que le salga al cliente.

## 6. `Throwable` vs `Exception`: ¿cuándo usarías cada uno?

Throwable es la clase que abarca tanto Errores como Exception, es decir, todo tipo de errores que afectan al programa. Las Exceptions son un tipo de Throwable que se dan por errores del programa en sí o el dominio.
Por tanto, usaría un Throwable en un catch en el que quiero controlar todo tipo de Errores y Excepciones, mientras que usaría Exception y sus subclases para controlar excepciones cada vez más específicas de forma más personalizada.

## 7. `finally`: pon un ejemplo de tu código donde se ejecute `finally`. ¿Por qué es útil?

El ejemplo más fácil es el método prestar del archivo PrestamoService.php.
En este método puede darse un MaterialNoDisponibleException, y se utiliza un try-finally para que, haya excepción o no, siempre se escriba en un log el intento de préstamo al final del método.

## 8. PSR-12: cita tres reglas que hayas aplicado y explica por qué mejoran la legibilidad.

1- Espacios en blanco tras el namespace y cada sentencia use.
2- Apertura de llaves en línea siguiente del encabezado de clases y métodos.
3- Especificar el tipo de los parámetros de los métodos y el tipo que devuelven, así como su nivel de seguridad.
Estas mejoran la legibilidad al hacer el código más espaciado entre sentencias y especificar los tipos que esperan los métodos, lo que esperan devolver, y decir explícitamente quiénes pueden acceder a ellos.

## 9. `strict_types`: ¿qué cambia respecto al comportamiento por defecto? ¿Por qué es una buena práctica?

El strict_types detiene la conversión silenciosa de PHP. Por ejemplo, PHP puede convertir el string "3" al int 3 si es lo que pide un método u operación.
Detenerla es una buena práctica porque el mandar una variable de un tipo inválido puede acabar explotando a largo plazo, por mucho que PHP haga conversiones silenciosas, con un fatal error.

## Ampliaciones resueltas

Completé la Ampliación 1. Creé en MaterialRepo la función contarPrestamosActivos pasando por parámetro el id del usuario, y preparando una sentencia sql que devuelve la cantidad de prestamos asociados al id del usuario. Este método es invocado de prestar.php y lanza una excepción si la cantidad de prestamos es igual o mayor a 3. Esta excepción es LimitePrestamosException.

Lo siguiente no es una ampliación de la tarea, pero algo que debería haber hecho hace varias tareas: Devoluciones. Creé devolver.php, con la función opuesta a prestar.php.
Para devolver un material, borro la fila de la tabla Prestamos con el mismo id de material que el material prestado y el mismo id de usuario que el usuario actual. Esto implica que solo el usuario que ha cogido prestado el material puede devolverlo, y nadie más.

## Autoevaluación

He aprendido porqué son importantes los namespaces, el estándar PSR-4 y cómo aplicar excepciones en un programa PHP.

Sin embargo, a la hora de comprobar la integración del programa tuve errores y muchos problemas para acceder al error_log. Tuve que comentar la sentencia de inclusión del archivo exceptions.php para que me explicase en pantalla el problema.
En conclusión, necesito apoyo para manejar el error_log porque sigo sin encontrarlo incluso tras buscar por internet.

También quiero mencionar que acabo de implementar las devoluciones, porque creo que solo se ha propuesto como ampliación opcional en tareas previas, y para hacerlas borro los préstamos de las Bases de Datos.
Entiendo que se podría querer que permanezca un historial de préstamos por lo que sea. ¿Podría entonces, en vez de condicionar el botón de prestar por el contenido de la Base de Datos, condicionarlo por el estado de la variable "disponible" del material? Suena menos fiable, pero no se me ocurre otra manera igual de simple. Si no, tendría que comprobar tanto la tabla de préstamos como una tabla de devoluciones... qué también podría querer en un caso real mantener el historial, así que tendría el mismo problema.
