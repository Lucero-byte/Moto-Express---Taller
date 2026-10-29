<?php
require __DIR__ . '/../layout.php';
layoutStart($titulo, 'citas');
?>

<div class="panel-header">
    <div class="panel-header-title">
        <?= svgIcon('citas', 'panel-icon') ?>
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
            <h3><?= $edit ? 'Reprogramar Cita #' . e($edit['id']) : 'Agendar Nueva Cita' ?></h3>
        </div>
    </div>
    <?php require __DIR__ . '/form.php'; ?>
</section>

<!-- LISTA DE REGISTROS -->
<section class="panel">
    <div class="panel-header">
        <div class="panel-header-title">
            <?= svgIcon('citas', 'panel-icon-sm') ?>
            <h3>Citas Programadas</h3>
        </div>
        <span class="badge-count"><?= count($rows) ?> registros</span>
    </div>
    <?php require __DIR__ . '/lista.php'; ?>
</section>

<?php layoutEnd('citas'); ?>
