<?php
require __DIR__ . '/../layout.php';
layoutStart($titulo, 'vehiculos');
?>

<div class="panel-header">
    <div class="panel-header-title">
        <?= svgIcon('vehiculos', 'panel-icon') ?>
        <h2><?= e($titulo) ?></h2>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-error">
        <span class="alert-icon"><?= svgIcon('alert', 'alert-svg') ?></span>
        <span class="alert-text"><?= e($error) ?></span>
    </div>
<?php endif; ?>

<!-- FORMULARIO CRUD -->
<section class="panel">
    <div class="panel-header">
        <div class="panel-header-title">
            <?= svgIcon($edit ? 'edit' : 'plus', 'panel-icon-sm') ?>
            <h3><?= $edit ? 'Editar Vehículo Placa ' . e($edit['placa']) : 'Registrar Nuevo Vehículo' ?></h3>
        </div>
    </div>
    <?php require __DIR__ . '/form.php'; ?>
</section>

<!-- LISTA DE REGISTROS -->
<section class="panel">
    <div class="panel-header">
        <div class="panel-header-title">
            <?= svgIcon('vehiculos', 'panel-icon-sm') ?>
            <h3>Vehículos Registrados</h3>
        </div>
        <span class="badge-count"><?= count($rows) ?> registros</span>
    </div>
    <?php require __DIR__ . '/lista.php'; ?>
</section>

<?php layoutEnd('vehiculos'); ?>
