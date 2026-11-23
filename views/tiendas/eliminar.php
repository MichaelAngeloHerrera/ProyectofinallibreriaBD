<?php
require_once __DIR__ . '/../../includes/funciones.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = conectarDB();
$tiendaModel = new Tienda($db);

$id = $_POST['id'] ?? $_GET['id'] ?? '';
$tienda = $tiendaModel->getById($id);
if (!$tienda) {
    setFlash('danger', 'La tienda solicitada no existe.');
    header('Location: index.php');
    exit;
}

// Ejecución: solo por POST (después de confirmar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tiendaModel->delete($tienda['id_tienda']);
        setFlash('success', 'Tienda eliminada correctamente.');
    } catch (RuntimeException $ex) {
        setFlash('danger', $ex->getMessage());
    }
    header('Location: index.php');
    exit;
}

$base         = '../../';
$pagina       = 'tiendas';
$tituloPagina = 'Eliminar tienda';
include __DIR__ . '/../../includes/header.php';
?>

<div class="card border-danger" style="max-width: 640px;">
  <div class="card-header bg-danger text-white"><i class="bi bi-exclamation-triangle"></i> Confirmar eliminación</div>
  <div class="card-body">
    <p>¿Seguro que deseas eliminar esta tienda? Esta acción no se puede deshacer.</p>
    <ul>
      <li><strong>ID:</strong> <?= e($tienda['id_tienda']) ?></li>
      <li><strong>Nombre:</strong> <?= e($tienda['nombre_tienda']) ?></li>
      <li><strong>Dirección:</strong> <?= e($tienda['direcc_tienda']) ?>, <?= e($tienda['ciudad']) ?>, <?= e($tienda['estado']) ?></li>
    </ul>
    <form method="post" class="d-flex gap-2">
      <input type="hidden" name="id" value="<?= e($tienda['id_tienda']) ?>">
      <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Sí, eliminar</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
