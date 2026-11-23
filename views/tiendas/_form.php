<?php
// Formulario compartido por crear.php y editar.php
// Variables esperadas: $datos (array), $editando (bool), $error (string)
?>
<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<form method="post" class="card card-body" novalidate>
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label" for="id_tienda">ID de la tienda</label>
      <input type="number" min="1" class="form-control" id="id_tienda" name="id_tienda"
             value="<?= e($datos['id_tienda']) ?>" <?= $editando ? 'readonly' : 'required' ?>>
    </div>
    <div class="col-md-8">
      <label class="form-label" for="nombre_tienda">Nombre de la tienda</label>
      <input type="text" class="form-control" id="nombre_tienda" name="nombre_tienda" maxlength="60"
             value="<?= e($datos['nombre_tienda']) ?>" required>
    </div>
    <div class="col-12">
      <label class="form-label" for="direcc_tienda">Dirección</label>
      <input type="text" class="form-control" id="direcc_tienda" name="direcc_tienda" maxlength="60"
             value="<?= e($datos['direcc_tienda']) ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label" for="ciudad">Ciudad</label>
      <input type="text" class="form-control" id="ciudad" name="ciudad" maxlength="40"
             value="<?= e($datos['ciudad']) ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label" for="estado">Estado</label>
      <input type="text" class="form-control" id="estado" name="estado" maxlength="2"
             value="<?= e($datos['estado']) ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label" for="codigo_postal">Código postal</label>
      <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" maxlength="10"
             value="<?= e($datos['codigo_postal']) ?>">
    </div>
  </div>
  <div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </div>
</form>
