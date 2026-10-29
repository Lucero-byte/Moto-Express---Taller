<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <div class="form-group">
        <label for="cliente_id">Propietario / Cliente *</label>
        <select id="cliente_id" name="cliente_id" required>
            <option value="">-- Seleccione Cliente --</option>
            <?php foreach ($clientes as $c): ?>
                <option value="<?= e($c['id']) ?>" <?= (isset($edit['cliente_id']) && $edit['cliente_id'] == $c['id']) ? 'selected' : '' ?>>
                    <?= e($c['nombre']) ?> (ID: #<?= e($c['id']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="placa">Placa del Vehículo *</label>
        <input type="text" id="placa" name="placa" value="<?= e($edit['placa'] ?? '') ?>" placeholder="Ej. ABC-123" maxlength="10" required>
    </div>

    <div class="form-group">
        <label for="marca">Marca *</label>
        <input type="text" id="marca" name="marca" value="<?= e($edit['marca'] ?? '') ?>" placeholder="Ej. Toyota" required>
    </div>

    <div class="form-group">
        <label for="modelo">Modelo *</label>
        <input type="text" id="modelo" name="modelo" value="<?= e($edit['modelo'] ?? '') ?>" placeholder="Ej. Corolla" required>
    </div>

    <div class="form-group">
        <label for="anio">Año de Fabricación *</label>
        <input type="number" id="anio" name="anio" value="<?= e($edit['anio'] ?? date('Y')) ?>" min="1950" max="<?= date('Y') + 1 ?>" required>
    </div>

    <div class="form-actions">
        <button type="submit" name="accion" value="guardar" class="btn btn-primary">
            <?= svgIcon('save', 'btn-icon') ?>
            <span><?= $edit ? 'Actualizar Vehículo' : 'Guardar Vehículo' ?></span>
        </button>
        <?php if ($edit): ?>
            <a href="vehiculos.php" class="btn btn-secondary">Cancelar Edición</a>
        <?php else: ?>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
        <?php endif; ?>
    </div>
</form>
