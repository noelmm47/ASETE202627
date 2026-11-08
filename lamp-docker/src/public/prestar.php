<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Includes/auth.php';
//require_once __DIR__ . '/../app/Includes/exceptions.php';

use App\Exceptions\MaterialNoDisponibleException;

use App\Exceptions\MaterialNoEncontradoException;

use App\Exceptions\LimitePrestamosException;

use App\Includes\auth;

use App\Repos\MaterialRepo;

use App\Services\PrestamoService;

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit;
}

$id = (int)($_POST['material_id'] ?? 0);

try {
    $repo = new MaterialRepo();
    //echo "<script>console.log(Préstamos: " . $repo ->contarPrestamosActivos(currentUser()['id']) . ");</script>";
    if($repo -> contarPrestamosActivos(currentUser()['id']) >= 3)
        throw new LimitePrestamosException();
    $service = new PrestamoService($repo);
    $service->prestar($id, currentUser()['id']);

    header('Location: /material.php?id=' . $id);
    exit;

} catch (MaterialNoEncontradoException | LimitePrestamosException $e) {
    http_response_code(404);
    $error = $e->getMessage();
} catch (MaterialNoDisponibleException $e) {
    http_response_code(400);
    $error = $e->getMessage();
}

require __DIR__ . '/../app/Includes/header.php';
echo '<h1>No se pudo completar el préstamo</h1>';
echo '<p>' . htmlspecialchars($error) . '</p>';
echo '<a href="/index.php">Volver al catálogo</a>';
require __DIR__ . '/../app/Includes/footer.php';