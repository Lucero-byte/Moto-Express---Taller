<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>N° Orden</th>
                <th>Fecha</th>
                <th>Vehículo</th>
                <th>Propietario</th>
                <th>Mecánico</th>
                <th>Diagnóstico</th>
                <th>Estado</th>
                <th>Total Est.</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 24px;">
                        No hay órdenes de trabajo registradas en este momento.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($rows as $fila): ?>
                    <?php 
                    $total = $controller->modelo->calcularTotal($fila['id']); 
                    $badgeClase = match($fila['estado']) {
                        'pendiente' => 'badge-pendiente',
                        'en_proceso' => 'badge-en_proceso',
                        'completada' => 'badge-completada',
                        'cancelada' => 'badge-cancelada',
                        default => 'badge-metodo'
                    };
                    ?>
                    <tr>
                        <td><span class="id-tag">#<?= e($fila['id']) ?></span></td>
                        <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                        <td>
                            <span class="plate-badge"><?= e($fila['vehiculo_placa']) ?></span>
                            <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 2px;">
                                <?= e($fila['vehiculo_marca']) ?> <?= e($fila['vehiculo_modelo']) ?>
                            </div>
                        </td>
                        <td><?= e($fila['cliente_nombre']) ?></td>
                        <td><?= e($fila['mecanico_nombre']) ?></td>
                        <td style="max-width: 250px;">
                            <span style="font-size: 0.84rem; color: var(--text-secondary); line-height: 1.4; display: block;">
                                <?= e($fila['diagnostico']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-status <?= $badgeClase ?>">
                                <?= ucwords(str_replace('_', ' ', e($fila['estado']))) ?>
                            </span>
                        </td>
                        <td>
                            <strong>S/ <?= number_format($total, 2) ?></strong>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="ordenes.php?editar=<?= e($fila['id']) ?>" class="btn-action btn-action-edit" title="Editar">
                                    <?= svgIcon('edit', 'action-svg') ?>
                                    <span>Editar</span>
                                </a>
                                <form method="post" onsubmit="return confirm('¿Está seguro de eliminar esta orden de trabajo? Se eliminarán los servicios y repuestos asociados.');">
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
