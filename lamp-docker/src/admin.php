<?php
require_once __DIR__ . '/includes/auth.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    if ($id !== currentUser()['id']) {
        $stmt = db()->prepare('DELETE FROM usuarios WHERE id = ?');
        $stmt->execute([$id]);
    }
    header('Location: admin.php');
    exit;
}

require __DIR__ . '/includes/header.php';

$usuarios = db()->query('SELECT id, email, rol, creado_en FROM usuarios ORDER BY id')->fetchAll();
?>
<h1>Panel de administración</h1>
<table>
  <tr><th>ID</th><th>Email</th><th>Rol</th><th>Creado</th><th>Acciones</th><th>Número de tareas</th></tr>
  <?php foreach ($usuarios as $u): ?>
    <tr>
      <td><?= $u['id'] ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= $u['rol'] ?></td>
      <td><?= $u['creado_en'] ?></td>
      <td>
        <?php if ($u['id'] !== currentUser()['id']): ?>
          <form method="POST" style="display:inline"
                onsubmit="return confirm('¿Borrar usuario?')">
            <input type="hidden" name="delete_id" value="<?= $u['id'] ?>">
            <button>Borrar</button>
          </form>
        <?php else: ?>
          <em>(tú)</em>
        <?php endif; ?>
      </td>
      <td>
        <?php $smtm = db() -> prepare("SELECT COUNT(*) FROM tareas WHERE usuario_id= ? AND completada = 0");
          $smtm -> execute([$u['id']]);
          echo ($smtm -> fetchColumn());
        ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php require __DIR__ . '/includes/footer.php'; ?>