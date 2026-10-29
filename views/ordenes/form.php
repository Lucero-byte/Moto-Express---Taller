<form method="post" class="form-grid">
    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
    <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">

    <div class="form-group" style="grid-column: span 2;">
        <label for="cita_id">Cita Asociada (Vehículo / Mecánico / Fecha) *</label>
        <select id="cita_id" name="cita_id" required>
            <option value="">-- Seleccione Cita --</option>
            <?php foreach ($citas as $c): ?>
                <option value="<?= e($c['id']) ?>" <?= (isset($edit['cita_id']) && $edit['cita_id'] == $c['id']) ? 'selected' : '' ?>>
                    Cita #<?= e($c['id']) ?> - [<?= e($c['placa']) ?> <?= e($c['marca']) ?>] Cliente: <?= e($c['cliente_nombre']) ?> | Mecánico: <?= e($c['mecanico_nombre']) ?> (<?= date('d/m/Y H:i', strtotime($c['fecha'])) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="fecha">Fecha de Emisión *</label>
        <input type="date" id="fecha" name="fecha" value="<?= e($edit['fecha'] ?? date('Y-m-d')) ?>" required>
    </div>

    <div class="form-group">
        <label for="estado">Estado de la Reparación *</label>
        <?php $estadoActual = $edit['estado'] ?? 'pendiente'; ?>
        <select id="estado" name="estado" required>
            <option value="pendiente" <?= $estadoActual === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="en_proceso" <?= $estadoActual === 'en_proceso' ? 'selected' : '' ?>>En Proceso</option>
            <option value="completada" <?= $estadoActual === 'completada' ? 'selected' : '' ?>>Completada</option>
            <option value="cancelada" <?= $estadoActual === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
        </select>
    </div>

    <div class="form-group full-width">
        <label for="diagnostico">Diagnóstico de Fallas Mecánicas y Trabajo a Realizar *</label>
        <textarea id="diagnostico" name="diagnostico" rows="3" placeholder="Describa el diagnóstico, fallas detectadas y trabajo solicitado..." required><?= e($edit['diagnostico'] ?? '') ?></textarea>
    </div>

    <!-- SECCIÓN SERVICIOS -->
    <div class="form-group full-width order-subpanel">
        <div class="order-subpanel-header">
            <?= svgIcon('servicios', 'subpanel-svg') ?>
            <h4>Servicios de Reparación a Incluir</h4>
        </div>
        <p class="order-subpanel-desc">Marque los servicios realizados o cotizados para esta orden.</p>
        <div class="selection-grid">
            <?php foreach ($servicios as $s): ?>
                <label class="selection-item">
                    <input type="checkbox" name="servicios[]" value="<?= e($s['id']) ?>" <?= in_array($s['id'], $serviciosSeleccionados) ? 'checked' : '' ?>>
                    <span><?= e($s['nombre']) ?> <strong>(S/ <?= number_format($s['precio'], 2) ?>)</strong></span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- SECCIÓN REPUESTOS -->
    <div class="form-group full-width order-subpanel">
        <div class="order-subpanel-header">
            <?= svgIcon('repuestos', 'subpanel-svg') ?>
            <h4>Repuestos y Autopartes Utilizados</h4>
        </div>
        <p class="order-subpanel-desc">Seleccione los repuestos y especifique la cantidad utilizada.</p>
        <div class="selection-grid">
            <?php foreach ($repuestos as $r): ?>
                <?php 
                $usado = isset($repuestosSeleccionados[$r['id']]); 
                $cant = $usado ? $repuestosSeleccionados[$r['id']] : 1;
                ?>
                <div class="selection-item-qty">
                    <label class="selection-qty-label">
                        <input type="checkbox" name="repuestos[<?= e($r['id']) ?>][activo]" value="1" <?= $usado ? 'checked' : '' ?>>
                        <span><?= e($r['nombre']) ?><br><small class="text-muted">S/ <?= number_format($r['precio'], 2) ?> &middot; Stock: <?= e($r['stock']) ?></small></span>
                    </label>
                    <div class="qty-input-wrap">
                        <input type="number" name="repuestos[<?= e($r['id']) ?>][cantidad]" value="<?= e($cant) ?>" min="1" max="100" title="Cantidad">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" name="accion" value="guardar" class="btn btn-primary">
            <?= svgIcon('save', 'btn-icon') ?>
            <span><?= $edit ? 'Actualizar Orden de Trabajo' : 'Generar Orden de Trabajo' ?></span>
        </button>
        <?php if ($edit): ?>
            <a href="ordenes.php" class="btn btn-secondary">Cancelar Edición</a>
        <?php else: ?>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
        <?php endif; ?>
    </div>
</form>
