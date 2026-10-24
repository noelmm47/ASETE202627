# Reflexion — MiniApp de Biblioteca

**Nombre:** Noel Morato Muñoz

## 1. Encapsulación: ¿por qué los atributos de MaterialBiblioteca son private? Pon un ejemplo de bug que evitarías.

Para que no sean accesibles y modificables por otras clases, incluidas las que heredan de MaterialBiblioteca. Por ejemplo cuando se hidraten los datos no ocurran cambios por parte de otras clases y afecte el resultado de diferentes operaciones o se afecte a la base de datos cuando se haga persistencia.

## 2. Herencia: ¿qué ventaja tiene que Libro, Revista y DVD hereden de MaterialBiblioteca?

Heredan todos sus atributos y métodos, aunque solo pueden acceder directamente a los que tengan nivel de protección protected o public; para los privados tendrán que usar la palabra clave parent e invocar desde él.
De esta manera no hace falta repetir código, hay reutilización.

## 3. Polimorfismo: explica qué pasa cuando el foreach del catálogo llama a $m->mostrarFicha() sin saber el tipo.

Cuando el foreach del catálogo llama a $m->mostrarFicha() sin saber el tipo, ejecuta el método mostrarFicha() dependiendo del tipo que le llegue durante la ejecución. mostrarFicha() es un método de MaterialBiblioteca que se hereda a Libro, Revista y DVD y es sobreescrita en cada una; por lo que dependiendo del tipo dinámico, tendrá un comportamiento u otro.

## 4. Interfaces: ¿qué diferencia hay entre Prestable (interfaz) y Loggable (trait)? ¿Cuándo usarías cada uno?

La interfaz Prestable se define como un contrato; deja cabeceras de funciones para que las implemente la clase que implemente la interfaz. De esta manera, varias clases que implementen la misma interfaz pueden tener objetivos o comportamientos parecidos, pero no iguales.
El trait Loggable funciona como la herencia de una superclase, heredando los atributos y métodos ya implementados cuando usas el trait.

Por tanto, la interfaz la usarías cuando quieres darle un comportamiento concreto a una clase y el trait cuando necesitas usar los atributos y funcionalidades del trait para completar tu clase o varias sin repetir código y sin usar herencia de más de una clase.

## 5. Repositorio: ¿por qué el SQL está en MaterialRepo y no dentro de Libro?

El SQL está en MaterialRepo  y no en Libro ni en ningún otro sitio por cuestiones de modularidad y reutilización. MaterialRepo funciona como la clase de utilidades con funciones de hidratación y persistencia. Al centralizar esas operaciones en una sola clase, accesible para quienes las necesiten, se mantiene un código más limpio y ordenado.

## 6. Integración: ¿cómo se relacionan los objetos con $_SESSION['user'] en prestar.php?

Utilizando los métodos login() y currentUser() de auth.php.
Se requiere confirmar el login para acceder a prestar.php, y utiliza el método currentUser(), qué devuelve el usuario almacenado en la sesión (función de auth.php), para hacer persistencia de objetos.

## 7. Java vs PHP: cita tres diferencias concretas que hayas notado.


1- La forma de acceder a métodos y atributos de un objeto o clase.
2- Uso de 'includes' en vez de imports para señalar la necesidad de propiedades o métodos de otro archivo
3- Uso de 'uses' para la implementación de una interfaz o un trait en una clase.

## 8. Diseño: si mañana añadieras Mapa como nuevo tipo, ¿qué archivos tocarías?

Además de tocar el archivo Mapa.php, porque tendría que crear la clase con sus atributos y métodos; cambiaría MaterialRepo, pues para la persistencia e hidratación hace falta especificar el tipo del material.
