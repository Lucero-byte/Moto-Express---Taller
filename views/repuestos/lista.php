<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Repuesto</th>
                <th>Precio Unitario</th>
                <th>Stock Disponible</th>
                <th>Estado Inventario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">
                        No hay repuestos registrados en este momento.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($rows as $fila): ?>
                    <tr>
                        <td><span class="id-tag">#<?= e($fila['id']) ?></span></td>
                        <td><strong><?= e($fila['nombre']) ?></strong></td>
                        <td><span class="price-text">S/ <?= number_format($fila['precio'], 2) ?></span></td>
                        <td><?= e($fila['stock']) ?> unidades</td>
                        <td>
                            <?php if ($fila['stock'] <= 5): ?>
                                <span class="badge-status badge-cancelada">Crítico (<?= e($fila['stock']) ?>)</span>
                            <?php elseif ($fila['stock'] <= 15): ?>
                                <span class="badge-status badge-pendiente">Stock Medio</span>
                            <?php else: ?>
                                <span class="badge-status badge-completada">Disponible</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="repuestos.php?editar=<?= e($fila['id']) ?>" class="btn-action btn-action-edit" title="Editar">
                                    <?= svgIcon('edit', 'action-svg') ?>
                                    <span>Editar</span>
                                </a>
                                <form method="post" onsubmit="return confirm('¿Está seguro de eliminar este repuesto?');">
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
