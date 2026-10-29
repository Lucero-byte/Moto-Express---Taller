<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha Pago</th>
                <th>N° Orden</th>
                <th>Vehículo</th>
                <th>Cliente</th>
                <th>Método de Pago</th>
                <th>Monto Cobrado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 24px;">
                        No hay pagos registrados en este momento.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($rows as $fila): ?>
                    <tr>
                        <td><span class="id-tag">#<?= e($fila['id']) ?></span></td>
                        <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                        <td><span class="id-tag">Orden #<?= e($fila['orden_id']) ?></span></td>
                        <td><span class="plate-badge"><?= e($fila['vehiculo_placa']) ?></span></td>
                        <td><?= e($fila['cliente_nombre']) ?></td>
                        <td>
                            <span class="badge-status badge-metodo">
                                <?php
                                echo match($fila['metodo']) {
                                    'efectivo' => 'Efectivo',
                                    'tarjeta_credito' => 'Tarjeta Crédito',
                                    'tarjeta_debito' => 'Tarjeta Débito',
                                    'yape' => 'Yape / Plin',
                                    default => e($fila['metodo'])
                                };
                                ?>
                            </span>
                        </td>
                        <td><strong class="price-text" style="font-size: 0.95rem;">S/ <?= number_format($fila['monto'], 2) ?></strong></td>
                        <td>
                            <div class="table-actions">
                                <a href="pagos.php?editar=<?= e($fila['id']) ?>" class="btn-action btn-action-edit" title="Editar">
                                    <?= svgIcon('edit', 'action-svg') ?>
                                    <span>Editar</span>
                                </a>
                                <form method="post" onsubmit="return confirm('¿Está seguro de eliminar este registro de pago?');">
                                    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                                    <input type="hidden" name="id" value="<?= e($fila['id']) ?>">
                                    <button type="submit" name="accion" value="eliminar" class="btn-action btn-action-danger" title="Eliminar">
                                        <?= svgIcon('trash', 'action-svg') ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
