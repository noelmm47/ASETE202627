<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Includes/auth.php';
//require_once __DIR__ . '/../app/Includes/exceptions.php';

requireLogin();

use App\Exceptions\MaterialNoEncontradoException;

use App\Repos\MaterialRepo;

$id = (int)($_GET['id'] ?? 0);
try {
    $repo = new MaterialRepo();
    $material = $repo->findById($id);
} catch (MaterialNoEncontradoException $e) {
    http_response_code(404);
    require __DIR__ . '/../app/Includes/header.php';
    echo '<h1>Material no encontrado</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<a href="/index.php">Volver al catálogo</a>';
    require __DIR__ . '/../app/Includes/footer.php';
    exit;
}

require __DIR__ . '/../app/Includes/header.php';
?>
<h1><?= htmlspecialchars($material->mostrarFicha()) ?></h1>

<dl>
  <dt>Tipo</dt><dd><?= htmlspecialchars($material->tipoMaterial()) ?></dd>
  <dt>Autor</dt><dd><?= htmlspecialchars($material->getAutor()) ?></dd>
  <dt>Año</dt><dd><?= $material->getAnio() ?? '—' ?></dd>
  <dt>Antigüedad</dt><dd><?= $material->antiguedad() ?> años</dd>
  <dt>Días de préstamo</dt><dd><?= $material->diasPrestamo() ?></dd>
  <dt>Añadido al catálogo</dt><dd><?= $material->fechaFormateada() ?></dd>
  <dt>Estado</dt><dd><?= $material->isDisponible() ? 'Disponible' : 'Prestado' ?></dd>
</dl>

<?php //if ($material->isDisponible()): ?>
  <form method="POST" action="prestar.php"> <!-- FORM Prestar -->
    <input type="hidden" name="material_id" value="<?= $material->getId() ?>">
    <button <?php if ($repo->contarPrestamosActivos(currentUser()['id'])>2
                      || !$material->isDisponible()) echo 'disabled="disabled"';
          //Si tiene demasiados préstamos o no está disponible?>>
      Pedir prestado</button>
    <?php
      if($repo->contarPrestamosActivos(currentUser()['id'])>2)
        echo "<p>Ha alcanzado el máximo de préstamos.</p>";
    ?>
  </form>
  <br>
  <form method="POST" action="devolver.php"> <!-- FORM devolver -->
    <input type="hidden" name="material_id" value="<?= $material->getId() ?>">
    <button <?php if (!($repo->estaPrestadoPor(currentUser()['id'], $id))) echo 'disabled="disabled"';
    //Si el usuario no es quien lo ha tomado prestado, se desabilita el botón?>>
      Devolver</button>
    <?php
      if(!$material->isDisponible())
        echo "<p>El material ha sido prestado, no está disponible.</p>";
    ?>
  </form>
<?php //endif; ?>

<?php require __DIR__ . '/../app/Includes/footer.php'; ?>