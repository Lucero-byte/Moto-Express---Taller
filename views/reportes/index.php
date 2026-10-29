<?php
require __DIR__ . '/../layout.php';
layoutStart($titulo, 'reportes');
?>

<div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div class="panel-header-title">
        <?= svgIcon('reportes', 'panel-icon') ?>
        <h2><?= e($titulo) ?></h2>
    </div>
    <button onclick="window.print()" class="btn btn-secondary">
        <?= svgIcon('printer', 'btn-icon') ?>
        <span>Imprimir Reporte</span>
    </button>
</div>

<!-- ================================================================= -->
<!-- REPORTE 1: INGRESOS OBTENIDOS -->
<!-- ================================================================= -->
<section class="panel">
    <div class="panel-header">
        <div class="panel-header-title">
            <?= svgIcon('dollar', 'panel-icon-sm') ?>
            <h3>1. Reporte de Ingresos Obtenidos</h3>
        </div>
        <div class="report-highlight">
            <span class="report-highlight-label">Total Recaudado</span>
            <span class="report-highlight-val">S/ <?= number_format($ingresosData['total'], 2) ?></span>
        </div>
    </div>

    <p class="section-subtitle" style="margin-bottom: 16px;">
        Desglose de recaudación según el método de pago utilizado por los clientes (Efectivo, Tarjetas y Yape):
    </p>

    <!-- Tarjetas de resumen por método -->
    <div class="method-cards-grid">
        <?php foreach ($ingresosData['por_metodo'] as $pm): ?>
            <?php
            $nombreMetodo = match($pm['metodo']) {
                'efectivo' => 'Efectivo',
                'tarjeta_credito' => 'Tarjeta de Crédito',
                'tarjeta_debito' => 'Tarjeta de Débito',
                'yape' => 'Yape / Plin',
                default => ucwords(str_replace('_', ' ', $pm['metodo']))
            };
            ?>
            <div class="method-card">
                <span class="method-card-title"><?= e($nombreMetodo) ?></span>
                <div class="method-card-amount">S/ <?= number_format($pm['total'], 2) ?></div>
                <span class="method-card-sub"><?= e($pm['operaciones']) ?> cobro(s) registrados</span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Detalle de pagos -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>N° Orden</th>
                    <th>Placa</th>
                    <th>Cliente</th>
                    <th>Método</th>
                    <th>Monto Cobrado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ingresosData['detalle'])): ?>
                    <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">No hay ingresos registrados aún.</td></tr>
                <?php else: ?>
                    <?php foreach ($ingresosData['detalle'] as $d): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($d['fecha'])) ?></td>
                            <td><span class="id-tag">#<?= e($d['orden_id']) ?></span></td>
                            <td><span class="plate-badge"><?= e($d['placa']) ?></span></td>
                            <td><?= e($d['cliente_nombre']) ?></td>
                            <td>
                                <span class="badge-status badge-metodo">
                                    <?= ucwords(str_replace('_', ' ', $d['metodo'])) ?>
                                </span>
                            </td>
                            <td><strong class="price-text">S/ <?= number_format($d['monto'], 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- ================================================================= -->
<!-- REPORTE 2: REPARACIONES PENDIENTES -->
<!-- ================================================================= -->
<section class="panel">
    <div class="panel-header">
        <div class="panel-header-title">
            <?= svgIcon('clock', 'panel-icon-sm') ?>
            <h3>2. Reporte de Reparaciones Pendientes</h3>
        </div>
        <span class="badge-status badge-pendiente">
            <?= count($reparacionesPendientes) ?> órdenes pendientes / en proceso
        </span>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>N° Orden</th>
                    <th>Fecha Ingreso</th>
                    <th>Vehículo</th>
                    <th>Cliente</th>
                    <th>Mecánico Asignado</th>
                    <th>Diagnóstico de Falla</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reparacionesPendientes)): ?>
                    <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 24px;">No hay reparaciones pendientes en cola.</td></tr>
                <?php else: ?>
                    <?php foreach ($reparacionesPendientes as $rp): ?>
                        <tr>
                            <td><span class="id-tag">#<?= e($rp['id']) ?></span></td>
                            <td><?= date('d/m/Y', strtotime($rp['fecha'])) ?></td>
                            <td>
                                <span class="plate-badge"><?= e($rp['placa']) ?></span>
                                <span style="font-size: 0.8rem; color: var(--text-secondary); margin-left: 4px;">
                                    <?= e($rp['marca']) ?> <?= e($rp['modelo']) ?>
                                </span>
                            </td>
                            <td><?= e($rp['cliente_nombre']) ?></td>
                            <td><?= e($rp['mecanico_nombre']) ?></td>
                            <td style="max-width: 280px;"><span style="font-size: 0.84rem;"><?= e($rp['diagnostico']) ?></span></td>
                            <td>
                                <span class="badge-status <?= $rp['estado'] === 'pendiente' ? 'badge-pendiente' : 'badge-en_proceso' ?>">
                                    <?= ucwords(str_replace('_', ' ', $rp['estado'])) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- ================================================================= -->
<!-- REPORTE 3: SERVICIOS REALIZADOS -->
<!-- ================================================================= -->
<section class="panel">
    <div class="panel-header">
        <div class="panel-header-title">
            <?= svgIcon('servicios', 'panel-icon-sm') ?>
            <h3>3. Reporte de Servicios de Mantenimiento Realizados</h3>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Servicio Automotriz</th>
                    <th>Tarifa Base</th>
                    <th>Veces Realizado</th>
                    <th>Ingreso Total Generado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($serviciosRealizados)): ?>
                    <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 24px;">No hay historial de servicios registrados en órdenes.</td></tr>
                <?php else: ?>
                    <?php foreach ($serviciosRealizados as $sr): ?>
                        <tr>
                            <td><strong><?= e($sr['nombre']) ?></strong></td>
                            <td>S/ <?= number_format($sr['precio'], 2) ?></td>
                            <td><span class="badge-status badge-en_proceso"><?= e($sr['total_veces']) ?> ejecución(es)</span></td>
                            <td><strong class="price-text">S/ <?= number_format($sr['total_generado'], 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- ================================================================= -->
<!-- REPORTE 4: VEHÍCULOS ATENDIDOS -->
<!-- ================================================================= -->
<section class="panel">
    <div class="panel-header">
        <div class="panel-header-title">
            <?= svgIcon('vehiculos', 'panel-icon-sm') ?>
            <h3>4. Reporte de Historial de Vehículos Atendidos</h3>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Marca y Modelo</th>
                    <th>Año</th>
                    <th>Propietario / Cliente</th>
                    <th>Teléfono</th>
                    <th>Total Visitas</th>
                    <th>Última Atención</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vehiculosAtendidos)): ?>
                    <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 24px;">No hay registros de vehículos atendidos aún.</td></tr>
                <?php else: ?>
                    <?php foreach ($vehiculosAtendidos as $va): ?>
                        <tr>
                            <td><span class="plate-badge"><?= e($va['placa']) ?></span></td>
                            <td><?= e($va['marca']) ?> <?= e($va['modelo']) ?></td>
                            <td><?= e($va['anio']) ?></td>
                            <td><?= e($va['cliente_nombre']) ?></td>
                            <td><?= e($va['telefono'] ?? '-') ?></td>
                            <td><span class="badge-status badge-completada"><?= e($va['total_visitas']) ?> orden(es)</span></td>
                            <td><?= date('d/m/Y', strtotime($va['ultima_visita'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php layoutEnd('reportes'); ?>
