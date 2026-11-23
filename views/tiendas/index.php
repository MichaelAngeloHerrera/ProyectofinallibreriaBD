<?php
require_once __DIR__ . '/../../includes/funciones.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = conectarDB();
$tiendaModel = new Tienda($db);
$tiendas = $tiendaModel->getAll();

$base         = '../../';
$pagina       = 'tiendas';
$tituloPagina = 'Tiendas';
include __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h4 mb-0">Listado de tiendas</h2>
  <a href="crear.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Nueva tienda</a>
</div>

<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-striped table-hover align-middle mb-0">
      <thead class="table-dark">
        <tr>
          <th>ID</th><th>Nombre</th><th>Dirección</th><th>Ciudad</th><th>Estado</th><th>C.P.</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tiendas as $t): ?>
          <tr>
            <td><?= e($t['id_tienda']) ?></td>
            <td><?= e($t['nombre_tienda']) ?></td>
            <td><?= e($t['direcc_tienda']) ?></td>
            <td><?= e($t['ciudad']) ?></td>
            <td><?= e($t['estado']) ?></td>
            <td><?= e($t['codigo_postal']) ?></td>
            <td class="text-end text-nowrap">
              <a href="editar.php?id=<?= urlencode($t['id_tienda']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Editar</a>
              <a href="eliminar.php?id=<?= urlencode($t['id_tienda']) ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$tiendas): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No hay tiendas registradas.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
