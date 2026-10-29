<?php
require_once __DIR__ . '/../controller/BaseController.php';
require_once __DIR__ . '/../controller/ReporteController.php';
require_once __DIR__ . '/layout.php';

exigirSesion();

$reporteController = new ReporteController();
$kpis = $reporteController->resumenKpis();
$modulos = modulos();

layoutStart('Panel Principal', 'home');
?>

<!-- BANNER HERO DEL DASHBOARD -->
<div class="dashboard-hero">
    <div class="hero-content">
        <span class="hero-badge">Taller Automotriz &bull; Sistema MVC</span>
        <h1>Panel de Control</h1>
        <p>Control centralizado de clientes, vehículos, órdenes de trabajo, repuestos y finanzas en tiempo real.</p>
    </div>
    <div class="hero-meta">
        <span class="hero-date">
            <?= svgIcon('citas', 'meta-svg') ?>
            <span><?= date('d M, Y') ?></span>
        </span>
    </div>
</div>

<!-- TARJETAS DE INDICADORES (KPIS) -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-title">Clientes</span>
            <span class="kpi-icon-pill"><?= svgIcon('clientes', 'kpi-svg') ?></span>
        </div>
        <div class="kpi-value"><?= e($kpis['clientes']) ?></div>
        <span class="kpi-footnote">Propietarios registrados</span>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-title">Vehículos</span>
            <span class="kpi-icon-pill"><?= svgIcon('vehiculos', 'kpi-svg') ?></span>
        </div>
        <div class="kpi-value"><?= e($kpis['vehiculos']) ?></div>
        <span class="kpi-footnote">Unidades en el sistema</span>
    </div>

    <div class="kpi-card kpi-warning">
        <div class="kpi-header">
            <span class="kpi-title">Reparaciones Pendientes</span>
            <span class="kpi-icon-pill warning"><?= svgIcon('clock', 'kpi-svg') ?></span>
        </div>
        <div class="kpi-value"><?= e($kpis['pendientes']) ?></div>
        <span class="kpi-footnote">Trabajos en taller</span>
    </div>

    <div class="kpi-card kpi-success">
        <div class="kpi-header">
            <span class="kpi-title">Ingresos Recaudados</span>
            <span class="kpi-icon-pill success"><?= svgIcon('dollar', 'kpi-svg') ?></span>
        </div>
        <div class="kpi-value">S/ <?= number_format($kpis['ingresos'], 2) ?></div>
        <span class="kpi-footnote">Total de cobros registrados</span>
    </div>
</div>

<!-- SECCIÓN DE MÓDULOS CON TARJETAS E IMÁGENES -->
<div class="section-title-wrap">
    <div>
        <h2 class="section-title">Módulos Administrativos</h2>
        <p class="section-subtitle">Selecciona una sección operativa para gestionar los registros.</p>
    </div>
</div>

<div class="dashboard-cards-grid">
    <?php foreach ($modulos as $id => $modulo): ?>
        <a class="module-card" href="<?= e($modulo['url']) ?>">
            <div class="card-image-wrap">
                <img src="<?= e($modulo['imagen']) ?>" alt="<?= e($modulo['nombre']) ?>" loading="lazy">
                <span class="card-icon-badge"><?= svgIcon($modulo['icono'], 'card-svg') ?></span>
            </div>
            <div class="card-body">
                <h3><?= e($modulo['nombre']) ?></h3>
                <p><?= e($modulo['desc']) ?></p>
                <div class="card-action-row">
                    <span class="card-link">Administrar</span>
                    <span class="card-arrow"><?= svgIcon('arrow-right', 'arrow-svg') ?></span>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php layoutEnd('home'); ?>
