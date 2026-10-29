<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <div class="form-group">
        <label for="nombre">Nombre Completo *</label>
        <input type="text" id="nombre" name="nombre" value="<?= e($edit['nombre'] ?? '') ?>" placeholder="Ej. Juan Pérez" required>
    </div>

    <div class="form-group">
        <label for="telefono">Teléfono / Celular</label>
        <input type="text" id="telefono" name="telefono" value="<?= e($edit['telefono'] ?? '') ?>" placeholder="Ej. 987654321">
    </div>

    <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" value="<?= e($edit['email'] ?? '') ?>" placeholder="cliente@correo.com">
    </div>

    <div class="form-actions">
        <button type="submit" name="accion" value="guardar" class="btn btn-primary">
            <?= svgIcon('save', 'btn-icon') ?>
            <span><?= $edit ? 'Actualizar Cliente' : 'Guardar Cliente' ?></span>
        </button>
        <?php if ($edit): ?>
            <a href="clientes.php" class="btn btn-secondary">Cancelar Edición</a>
        <?php else: ?>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
        <?php endif; ?>
    </div>
</form>
