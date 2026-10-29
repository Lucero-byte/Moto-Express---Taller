<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <div class="form-group">
        <label for="nombre">Nombre Completo del Mecánico *</label>
        <input type="text" id="nombre" name="nombre" value="<?= e($edit['nombre'] ?? '') ?>" placeholder="Ej. Roberto Flores" required>
    </div>

    <div class="form-group">
        <label for="especialidad">Especialidad Técnica *</label>
        <input type="text" id="especialidad" name="especialidad" value="<?= e($edit['especialidad'] ?? '') ?>" placeholder="Ej. Inyección Electrónica, Frenos, Suspensión..." required>
    </div>

    <div class="form-actions">
        <button type="submit" name="accion" value="guardar" class="btn btn-primary">
            <?= svgIcon('save', 'btn-icon') ?>
            <span><?= $edit ? 'Actualizar Mecánico' : 'Guardar Mecánico' ?></span>
        </button>
        <?php if ($edit): ?>
            <a href="mecanicos.php" class="btn btn-secondary">Cancelar Edición</a>
        <?php else: ?>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
        <?php endif; ?>
    </div>
</form>
