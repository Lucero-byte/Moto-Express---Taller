<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Placa</th>
                <th>Marca / Modelo</th>
                <th>Año</th>
                <th>Cliente / Propietario</th>
                <th>Teléfono Contacto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 24px;">
                        No hay vehículos registrados en este momento.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($rows as $fila): ?>
                    <tr>
                        <td><span class="id-tag">#<?= e($fila['id']) ?></span></td>
                        <td><span class="plate-badge"><?= e($fila['placa']) ?></span></td>
                        <td><strong><?= e($fila['marca']) ?></strong> <?= e($fila['modelo']) ?></td>
                        <td><?= e($fila['anio']) ?></td>
                        <td><?= e($fila['cliente_nombre'] ?? 'Sin asignar') ?></td>
                        <td><?= e($fila['cliente_telefono'] ?? '-') ?></td>
                        <td>
                            <div class="table-actions">
                                <a href="vehiculos.php?editar=<?= e($fila['id']) ?>" class="btn-action btn-action-edit" title="Editar">
                                    <?= svgIcon('edit', 'action-svg') ?>
                                    <span>Editar</span>
                                </a>
                                <form method="post" onsubmit="return confirm('¿Está seguro de eliminar este vehículo?');">
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
