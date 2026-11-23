<?php
// Cabecera + menú lateral. Cada vista define antes: $base, $pagina y $tituloPagina.
require_once __DIR__ . '/funciones.php';
$base         = $base ?? '';
$pagina       = $pagina ?? '';
$tituloPagina = $tituloPagina ?? 'BookManager';
$flash        = getFlash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($tituloPagina) ?> | BookManager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= $base ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
<div id="wrapper">

  <!-- Menú lateral -->
  <nav id="sidebar">
    <a class="sidebar-brand" href="<?= $base ?>index.php">
      <i class="bi bi-book-half"></i> BookManager
    </a>
    <ul class="nav flex-column mt-3">
      <li class="nav-item">
        <a class="nav-link <?= $pagina === 'inicio' ? 'active' : '' ?>" href="<?= $base ?>index.php">
          <i class="bi bi-speedometer2"></i> Inicio
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $pagina === 'tiendas' ? 'active' : '' ?>" href="<?= $base ?>views/tiendas/index.php">
          <i class="bi bi-shop"></i> Tiendas
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $pagina === 'titulos' ? 'active' : '' ?>" href="<?= $base ?>views/titulos/index.php">
          <i class="bi bi-journal-bookmark"></i> Títulos
        </a>
      </li>
    </ul>
  </nav>

  <!-- Contenido -->
  <div id="content">
    <header class="topbar d-flex align-items-center">
      <button class="btn btn-outline-secondary btn-sm me-3 d-md-none" id="toggleSidebar" type="button">
        <i class="bi bi-list"></i>
      </button>
      <h1 class="h5 mb-0"><?= e($tituloPagina) ?></h1>
    </header>

    <main class="p-4">
      <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['tipo']) ?> alert-dismissible fade show" role="alert">
          <?= e($flash['mensaje']) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
