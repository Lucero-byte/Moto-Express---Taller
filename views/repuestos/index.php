<?php
require __DIR__ . '/../layout.php';
layoutStart($titulo, 'repuestos');
?>

<div class="panel-header">
    <div class="panel-header-title">
        <?= svgIcon('repuestos', 'panel-icon') ?>
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
            <h3><?= $edit ? 'Editar Repuesto #' . e($edit['id']) : 'Registrar Nuevo Repuesto' ?></h3>
        </div>
    </div>
    <?php require __DIR__ . '/form.php'; ?>
</section>

<!-- LISTA DE REGISTROS -->
<section class="panel">
    <div class="panel-header">
        <div class="panel-header-title">
            <?= svgIcon('repuestos', 'panel-icon-sm') ?>
            <h3>Inventario de Repuestos</h3>
        </div>
        <span class="badge-count"><?= count($rows) ?> registros</span>
    </div>
    <?php require __DIR__ . '/lista.php'; ?>
</section>

<?php layoutEnd('repuestos'); ?>
