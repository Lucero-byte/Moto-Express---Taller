<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha y Hora</th>
                <th>Vehículo</th>
                <th>Propietario</th>
                <th>Mecánico Asignado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">
                        No hay citas agendadas en este momento.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($rows as $fila): ?>
                    <tr>
                        <td><span class="id-tag">#<?= e($fila['id']) ?></span></td>
                        <td><strong><?= date('d/m/Y H:i', strtotime($fila['fecha'])) ?></strong></td>
                        <td>
                            <span class="plate-badge"><?= e($fila['vehiculo_placa']) ?></span>
                            <span style="color: var(--text-secondary); font-size: 0.82rem; margin-left: 4px;">
                                <?= e($fila['vehiculo_marca']) ?> <?= e($fila['vehiculo_modelo']) ?>
                            </span>
                        </td>
                        <td><?= e($fila['cliente_nombre']) ?></td>
                        <td><?= e($fila['mecanico_nombre']) ?></td>
                        <td>
                            <div class="table-actions">
                                <a href="citas.php?editar=<?= e($fila['id']) ?>" class="btn-action btn-action-edit" title="Editar">
                                    <?= svgIcon('edit', 'action-svg') ?>
                                    <span>Editar</span>
                                </a>
                                <form method="post" onsubmit="return confirm('¿Está seguro de cancelar y eliminar esta cita?');">
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
