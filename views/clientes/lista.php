<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">
                        No hay clientes registrados en este momento.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($rows as $fila): ?>
                    <tr>
                        <td><span class="id-tag">#<?= e($fila['id']) ?></span></td>
                        <td><strong><?= e($fila['nombre']) ?></strong></td>
                        <td><?= e($fila['telefono'] ?? '-') ?></td>
                        <td><?= e($fila['email'] ?? '-') ?></td>
                        <td>
                            <div class="table-actions">
                                <a href="clientes.php?editar=<?= e($fila['id']) ?>" class="btn-action btn-action-edit" title="Editar">
                                    <?= svgIcon('edit', 'action-svg') ?>
                                    <span>Editar</span>
                                </a>
                                <form method="post" onsubmit="return confirm('¿Está seguro de eliminar a este cliente? Se eliminarán también sus vehículos asociados.');">
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
