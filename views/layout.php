<?php
/**
 * Plantilla Maestra (Layout)
 * Header de dos niveles, Navbar horizontal, Menú vertical derecho y Footer.
 * Diseño minimalista con iconos vectoriales SVG limpios (sin emojis).
 */

require_once __DIR__ . '/../controller/BaseController.php';

function layoutStart(string $titulo, string $activo = ''): void
{
    $modulos = modulos();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= e($titulo) ?> | Motor Express</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body>
        <!-- HEADER DE DOS NIVELES -->
        <header class="app-header">
            <!-- NIVEL 1: Barra Superior Institucional -->
            <div class="header-top">
                <a href="home.php" class="brand-link">
                    <div class="brand-symbol">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                        </svg>
                    </div>
                    <div class="brand-text">
                        <span class="brand-title">MOTOR EXPRESS</span>
                        <span class="brand-badge">Taller Automotriz</span>
                    </div>
                </a>

                <div class="user-profile">
                    <div class="user-badge">
                        <span class="user-avatar"><?= mb_substr(e($_SESSION['nombre'] ?? 'U'), 0, 1) ?></span>
                        <span class="user-name"><?= e($_SESSION['nombre'] ?? 'Usuario') ?></span>
                    </div>
                    <a href="logout.php" class="btn-logout" title="Cerrar sesión">
                        <?= svgIcon('logout', 'ui-icon-sm') ?>
                        <span>Salir</span>
                    </a>
                </div>
            </div>

            <!-- NIVEL 2: Navbar Horizontal de Navegación -->
            <nav class="header-nav" aria-label="Navegación principal">
                <div class="nav-container">
                    <a href="home.php" class="nav-item <?= $activo === 'home' ? 'active' : '' ?>">
                        <?= svgIcon('home', 'nav-svg') ?>
                        <span>Inicio</span>
                    </a>
                    <?php foreach ($modulos as $key => $m): ?>
                        <a href="<?= e($m['url']) ?>" class="nav-item <?= $activo === $key ? 'active' : '' ?>">
                            <?= svgIcon($m['icono'], 'nav-svg') ?>
                            <span><?= e($m['nombre']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </nav>
        </header>

        <!-- ESTRUCTURA PRINCIPAL (CONTENIDO + MENÚ VERTICAL DERECHO) -->
        <div class="layout-shell">
            <main class="main-content">
                <?php if (!empty($_SESSION['flash'])): ?>
                    <?php
                    $flash = $_SESSION['flash'];
                    $tipo = is_array($flash) ? ($flash['tipo'] ?? 'notice') : 'notice';
                    $msg = is_array($flash) ? ($flash['mensaje'] ?? '') : $flash;
                    unset($_SESSION['flash']);
                    ?>
                    <div class="alert alert-<?= e($tipo) ?>">
                        <span class="alert-icon">
                            <?= $tipo === 'error' ? svgIcon('alert', 'alert-svg') : svgIcon('check', 'alert-svg') ?>
                        </span>
                        <span class="alert-text"><?= e($msg) ?></span>
                    </div>
                <?php endif; ?>
    <?php
}

function layoutEnd(string $activo = ''): void
{
    $modulos = modulos();
    ?>
            </main>

            <!-- MENÚ VERTICAL DERECHO (REQUERIDO) -->
            <aside class="menu-vertical-derecho" aria-label="Menú vertical derecho">
                <div class="aside-header">
                    <span class="aside-title">Acceso Directo</span>
                    <span class="status-indicator" title="Taller Operativo">
                        <span class="status-dot"></span> Operativo
                    </span>
                </div>

                <ul class="vertical-menu-list">
                    <li>
                        <a href="home.php" class="<?= $activo === 'home' ? 'active' : '' ?>">
                            <?= svgIcon('home', 'aside-svg') ?>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <?php foreach ($modulos as $key => $m): ?>
                        <li>
                            <a href="<?= e($m['url']) ?>" class="<?= $activo === $key ? 'active' : '' ?>">
                                <?= svgIcon($m['icono'], 'aside-svg') ?>
                                <span><?= e($m['nombre']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="workshop-summary-box">
                    <div class="summary-item">
                        <span class="summary-label">Atención</span>
                        <span class="summary-value">Lun - Sáb 08:00 - 18:00</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Seguridad</span>
                        <span class="summary-value">PDO Prepared Statements</span>
                    </div>
                </div>
            </aside>
        </div>

        <!-- FOOTER -->
        <footer class="app-footer">
            <div class="footer-container">
                <p><strong>Motor Express</strong> &middot; Sistema de Gestión para Taller de Reparación de Vehículos</p>
                <p class="footer-sub">Arquitectura MVC en PHP &middot; LUCERO &copy; <?= date('Y') ?></p>
            </div>
        </footer>
    </body>
    </html>
    <?php
}
