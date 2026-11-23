<?php
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Tienda.php';
require_once __DIR__ . '/models/Titulo.php';

$db = conectarDB();
$tiendaModel = new Tienda($db);
$tituloModel = new Titulo($db);

$totalTiendas  = $tiendaModel->contar();
$totalTitulos  = $tituloModel->contar();
$totalEditores = count($tituloModel->getPublicadores());

$base         = '';
$pagina       = 'inicio';
$tituloPagina = 'Inicio';
include __DIR__ . '/includes/header.php';
?>

<div class="mb-4">
  <h2 class="h4">Bienvenido a BookManager</h2>
  <p class="text-muted mb-0">Sistema de gestión de librería: administra tus tiendas y tu inventario de títulos.</p>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card stat-card h-100"><div class="card-body d-flex justify-content-between align-items-center">
      <div><div class="text-uppercase small text-muted fw-bold">Tiendas</div><div class="stat-number"><?= $totalTiendas ?></div></div>
      <i class="bi bi-shop stat-icon"></i>
    </div></div>
  </div>
  <div class="col-md-4">
    <div class="card stat-card green h-100"><div class="card-body d-flex justify-content-between align-items-center">
      <div><div class="text-uppercase small text-muted fw-bold">Títulos</div><div class="stat-number"><?= $totalTitulos ?></div></div>
      <i class="bi bi-journal-bookmark stat-icon"></i>
    </div></div>
  </div>
  <div class="col-md-4">
    <div class="card stat-card orange h-100"><div class="card-body d-flex justify-content-between align-items-center">
      <div><div class="text-uppercase small text-muted fw-bold">Editoriales</div><div class="stat-number"><?= $totalEditores ?></div></div>
      <i class="bi bi-building stat-icon"></i>
    </div></div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card h-100"><div class="card-body">
      <h5 class="card-title"><i class="bi bi-shop"></i> Tiendas</h5>
      <p class="card-text text-muted">Consulta, registra, edita y elimina las tiendas de la red de distribución.</p>
      <a href="views/tiendas/index.php" class="btn btn-primary">Ir a Tiendas</a>
    </div></div>
  </div>
  <div class="col-md-6">
    <div class="card h-100"><div class="card-body">
      <h5 class="card-title"><i class="bi bi-journal-bookmark"></i> Títulos</h5>
      <p class="card-text text-muted">Gestiona el inventario de libros y asígnales su editorial.</p>
      <a href="views/titulos/index.php" class="btn btn-primary">Ir a Títulos</a>
    </div></div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
