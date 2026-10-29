<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <div class="form-group" style="grid-column: span 2;">
        <label for="orden_id">Orden de Trabajo a Cobrar *</label>
        <select id="orden_id" name="orden_id" required>
            <option value="">-- Seleccione Orden de Trabajo --</option>
            <?php foreach ($ordenes as $o): ?>
                <option value="<?= e($o['id']) ?>" <?= (isset($edit['orden_id']) && $edit['orden_id'] == $o['id']) ? 'selected' : '' ?>>
                    Orden #<?= e($o['id']) ?> - [<?= e($o['placa']) ?>] <?= e($o['cliente_nombre']) ?> - Estado: <?= e($o['estado']) ?> (<?= date('d/m/Y', strtotime($o['fecha'])) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="monto">Monto Cobrado (S/) *</label>
        <input type="number" id="monto" name="monto" value="<?= e($edit['monto'] ?? '') ?>" step="0.01" min="0.01" placeholder="0.00" required>
    </div>

    <div class="form-group">
        <label for="metodo">Método de Pago *</label>
        <?php $metodoActual = $edit['metodo'] ?? 'efectivo'; ?>
        <select id="metodo" name="metodo" required>
            <option value="efectivo" <?= $metodoActual === 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
            <option value="tarjeta_credito" <?= $metodoActual === 'tarjeta_credito' ? 'selected' : '' ?>>Tarjeta de Crédito</option>
            <option value="tarjeta_debito" <?= $metodoActual === 'tarjeta_debito' ? 'selected' : '' ?>>Tarjeta de Débito</option>
            <option value="yape" <?= $metodoActual === 'yape' ? 'selected' : '' ?>>Yape / Plin</option>
        </select>
    </div>

    <div class="form-group">
        <label for="fecha">Fecha del Pago *</label>
        <input type="date" id="fecha" name="fecha" value="<?= e($edit['fecha'] ?? date('Y-m-d')) ?>" required>
    </div>

    <div class="form-actions">
        <button type="submit" name="accion" value="guardar" class="btn btn-primary">
            <?= svgIcon('save', 'btn-icon') ?>
            <span><?= $edit ? 'Actualizar Pago' : 'Registrar Pago' ?></span>
        </button>
        <?php if ($edit): ?>
            <a href="pagos.php" class="btn btn-secondary">Cancelar Edición</a>
        <?php else: ?>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
        <?php endif; ?>
    </div>
</form>
