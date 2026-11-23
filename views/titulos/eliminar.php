<?php
require_once __DIR__ . '/../../includes/funciones.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = conectarDB();
$tituloModel = new Titulo($db);

$id = $_POST['id'] ?? $_GET['id'] ?? '';
$titulo = $tituloModel->getById($id);
if (!$titulo) {
    setFlash('danger', 'El título solicitado no existe.');
    header('Location: index.php');
    exit;
}

// Ejecución: solo por POST (después de confirmar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tituloModel->delete($titulo['id_titulo']);
        setFlash('success', 'Título eliminado correctamente.');
    } catch (RuntimeException $ex) {
        setFlash('danger', $ex->getMessage());
    }
    header('Location: index.php');
    exit;
}

$base         = '../../';
$pagina       = 'titulos';
$tituloPagina = 'Eliminar título';
include __DIR__ . '/../../includes/header.php';
?>

<div class="card border-danger" style="max-width: 640px;">
  <div class="card-header bg-danger text-white"><i class="bi bi-exclamation-triangle"></i> Confirmar eliminación</div>
  <div class="card-body">
    <p>¿Seguro que deseas eliminar este título? Esta acción no se puede deshacer.</p>
    <ul>
      <li><strong>ID:</strong> <?= e($titulo['id_titulo']) ?></li>
      <li><strong>Título:</strong> <?= e($titulo['titulo']) ?></li>
      <li><strong>Editorial:</strong> <?= e($titulo['nombre_pub']) ?></li>
      <li><strong>Precio:</strong> $<?= number_format((float)$titulo['precio'], 2) ?></li>
    </ul>
    <form method="post" class="d-flex gap-2">
      <input type="hidden" name="id" value="<?= e($titulo['id_titulo']) ?>">
      <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Sí, eliminar</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
