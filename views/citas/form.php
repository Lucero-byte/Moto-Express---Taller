<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <div class="form-group">
        <label for="vehiculo_id">Vehículo (Placa / Propietario) *</label>
        <select id="vehiculo_id" name="vehiculo_id" required>
            <option value="">-- Seleccione Vehículo --</option>
            <?php foreach ($vehiculos as $v): ?>
                <option value="<?= e($v['id']) ?>" <?= (isset($edit['vehiculo_id']) && $edit['vehiculo_id'] == $v['id']) ? 'selected' : '' ?>>
                    [<?= e($v['placa']) ?>] <?= e($v['marca']) ?> <?= e($v['modelo']) ?> - Cliente: <?= e($v['cliente_nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="mecanico_id">Mecánico Asignado *</label>
        <select id="mecanico_id" name="mecanico_id" required>
            <option value="">-- Seleccione Mecánico --</option>
            <?php foreach ($mecanicos as $m): ?>
                <option value="<?= e($m['id']) ?>" <?= (isset($edit['mecanico_id']) && $edit['mecanico_id'] == $m['id']) ? 'selected' : '' ?>>
                    <?= e($m['nombre']) ?> (<?= e($m['especialidad']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="fecha">Fecha y Hora de la Cita *</label>
        <?php
        $fechaVal = $edit['fecha'] ?? '';
        if ($fechaVal) {
            $fechaVal = str_replace(' ', 'T', substr($fechaVal, 0, 16));
        }
        ?>
        <input type="datetime-local" id="fecha" name="fecha" value="<?= e($fechaVal) ?>" required>
    </div>

    <div class="form-actions">
        <button type="submit" name="accion" value="guardar" class="btn btn-primary">
            <?= svgIcon('save', 'btn-icon') ?>
            <span><?= $edit ? 'Actualizar Cita' : 'Agendar Cita' ?></span>
        </button>
        <?php if ($edit): ?>
            <a href="citas.php" class="btn btn-secondary">Cancelar Edición</a>
        <?php else: ?>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
        <?php endif; ?>
    </div>
</form>
