<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <div class="form-group">
        <label for="nombre">Nombre del Servicio *</label>
        <input type="text" id="nombre" name="nombre" value="<?= e($edit['nombre'] ?? '') ?>" placeholder="Ej. Afinamiento Electrónico" required>
    </div>

    <div class="form-group">
        <label for="precio">Precio / Tarifa (S/) *</label>
        <input type="number" id="precio" name="precio" value="<?= e($edit['precio'] ?? '0.00') ?>" step="0.01" min="0" placeholder="0.00" required>
    </div>

    <div class="form-actions">
        <button type="submit" name="accion" value="guardar" class="btn btn-primary">
            <?= svgIcon('save', 'btn-icon') ?>
            <span><?= $edit ? 'Actualizar Servicio' : 'Guardar Servicio' ?></span>
        </button>
        <?php if ($edit): ?>
            <a href="servicios.php" class="btn btn-secondary">Cancelar Edición</a>
        <?php else: ?>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
        <?php endif; ?>
    </div>
</form>
