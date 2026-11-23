<?php
require_once __DIR__ . '/../../includes/funciones.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = conectarDB();
$tituloModel = new Titulo($db);
$titulos = $tituloModel->getAll();

$base         = '../../';
$pagina       = 'titulos';
$tituloPagina = 'Títulos';
include __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h4 mb-0">Listado de títulos</h2>
  <a href="crear.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Nuevo título</a>
</div>

<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-striped table-hover align-middle mb-0">
      <thead class="table-dark">
        <tr>
          <th>ID</th><th>Título</th><th>Tipo</th><th>Editorial</th><th class="text-end">Precio</th><th>Publicación</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($titulos as $t): ?>
          <tr>
            <td><?= e($t['id_titulo']) ?></td>
            <td><?= e($t['titulo']) ?></td>
            <td><?= e($t['tipo']) ?></td>
            <td><?= e($t['nombre_pub']) ?></td>
            <td class="text-end">$<?= number_format((float)$t['precio'], 2) ?></td>
            <td><?= e($t['fecha_pub']) ?></td>
            <td class="text-end text-nowrap">
              <a href="editar.php?id=<?= urlencode($t['id_titulo']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
              <a href="eliminar.php?id=<?= urlencode($t['id_titulo']) ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$titulos): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No hay títulos registrados.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
