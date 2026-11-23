<?php
// Formulario compartido por crear.php y editar.php
// Variables esperadas: $datos (array), $editando (bool), $error (string), $publicadores (array)
?>
<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<form method="post" class="card card-body" novalidate>
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label" for="id_titulo">ID del título</label>
      <input type="text" class="form-control" id="id_titulo" name="id_titulo" maxlength="6"
             placeholder="Ej. BU1032" value="<?= e($datos['id_titulo']) ?>" <?= $editando ? 'readonly' : 'required' ?>>
    </div>
    <div class="col-md-8">
      <label class="form-label" for="titulo">Título</label>
      <input type="text" class="form-control" id="titulo" name="titulo" maxlength="100"
             value="<?= e($datos['titulo']) ?>" required>
    </div>

    <div class="col-md-4">
      <label class="form-label" for="tipo">Tipo</label>
      <input type="text" class="form-control" id="tipo" name="tipo" maxlength="20" list="tipos"
             value="<?= e($datos['tipo']) ?>">
      <datalist id="tipos">
        <option value="business"><option value="psychology"><option value="trad_cook">
        <option value="mod_cook"><option value="popular_comp">
      </datalist>
    </div>

    <!-- Reto: el <select> carga las editoriales desde la tabla publicadores -->
    <div class="col-md-4">
      <label class="form-label" for="id_pub">Editorial</label>
      <select class="form-select" id="id_pub" name="id_pub" required>
        <option value="">-- Selecciona una editorial --</option>
        <?php foreach ($publicadores as $p): ?>
          <option value="<?= e($p['id_pub']) ?>" <?= (string)$datos['id_pub'] === (string)$p['id_pub'] ? 'selected' : '' ?>>
            <?= e($p['nombre_pub']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label" for="precio">Precio</label>
      <div class="input-group">
        <span class="input-group-text">$</span>
        <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio"
               value="<?= e($datos['precio']) ?>" required>
      </div>
    </div>

    <div class="col-md-4">
      <label class="form-label" for="fecha_pub">Fecha de publicación</label>
      <input type="date" class="form-control" id="fecha_pub" name="fecha_pub" value="<?= e($datos['fecha_pub']) ?>">
    </div>
    <div class="col-md-8">
      <label class="form-label" for="notas">Notas</label>
      <textarea class="form-control" id="notas" name="notas" rows="2" maxlength="200"><?= e($datos['notas']) ?></textarea>
    </div>
  </div>

  <div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </div>
</form>
