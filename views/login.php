<?php
require_once __DIR__ . '/../controller/AuthController.php';

$error = '';
$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        validarCsrf();
        $accion = $_POST['accion'] ?? '';

        if ($accion === 'registro') {
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $auth->registrar($nombre, $email, $password);
            notificar('Cuenta creada exitosamente. Ya puedes iniciar sesión.', 'notice');
            redirigir('login.php');
        } elseif ($accion === 'login') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($auth->ingresar($email, $password)) {
                redirigir('home.php');
            } else {
                $error = 'Correo electrónico o contraseña incorrectos.';
            }
        }
    } catch (Throwable $ex) {
        $error = $ex->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso | Motor Express</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="auth-card">
            <!-- Brand & Encabezado Minimalista -->
            <div class="auth-brand">
                <div class="auth-logo-badge">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                </div>
                <h2>Motor Express</h2>
                <p>Gestión Integral para Taller Automotriz</p>
            </div>

            <!-- Notificaciones Flash / Errores -->
            <?php if (!empty($_SESSION['flash'])): ?>
                <?php
                $flash = $_SESSION['flash'];
                $msg = is_array($flash) ? ($flash['mensaje'] ?? '') : $flash;
                unset($_SESSION['flash']);
                ?>
                <div class="alert alert-notice">
                    <span class="alert-icon"><?= svgIcon('check', 'alert-svg') ?></span>
                    <span class="alert-text"><?= e($msg) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error">
                    <span class="alert-icon"><?= svgIcon('alert', 'alert-svg') ?></span>
                    <span class="alert-text"><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Switcher de Pestañas Moderno -->
            <div class="auth-tabs" role="tablist">
                <button type="button" class="auth-tab-btn active" id="tab-login-btn" onclick="cambiarTab('login')">
                    Iniciar Sesión
                </button>
                <button type="button" class="auth-tab-btn" id="tab-registro-btn" onclick="cambiarTab('registro')">
                    Crear Cuenta
                </button>
            </div>

            <!-- Formulario de Inicio de Sesión -->
            <form method="post" id="form-login" class="auth-form">
                <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                <input type="hidden" name="accion" value="login">

                <div class="form-group">
                    <label for="login-email">Correo Electrónico</label>
                    <input type="email" id="login-email" name="email" placeholder="nombre@correo.com" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="login-password">Contraseña</label>
                    <input type="password" id="login-password" name="password" placeholder="••••••••" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Acceder al Sistema
                </button>
            </form>

            <!-- Formulario de Registro de Cuenta -->
            <form method="post" id="form-registro" class="auth-form" style="display: none;">
                <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                <input type="hidden" name="accion" value="registro">

                <div class="form-group">
                    <label for="reg-nombre">Nombre Completo</label>
                    <input type="text" id="reg-nombre" name="nombre" placeholder="Ej. Juan Pérez" required autocomplete="name">
                </div>

                <div class="form-group">
                    <label for="reg-email">Correo Electrónico</label>
                    <input type="email" id="reg-email" name="email" placeholder="nombre@correo.com" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="reg-password">Contraseña <small style="color: var(--text-muted);">(mínimo 6 caracteres)</small></label>
                    <input type="password" id="reg-password" name="password" placeholder="••••••••" minlength="6" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Registrar Cuenta
                </button>
            </form>

            <div class="auth-footer-note">
                <span>Plataforma con arquitectura MVC &middot; SENATI</span>
            </div>
        </div>
    </div>

    <script>
        function cambiarTab(tab) {
            const formLogin = document.getElementById('form-login');
            const formReg = document.getElementById('form-registro');
            const btnLogin = document.getElementById('tab-login-btn');
            const btnReg = document.getElementById('tab-registro-btn');

            if (tab === 'login') {
                formLogin.style.display = 'flex';
                formReg.style.display = 'none';
                btnLogin.classList.add('active');
                btnReg.classList.remove('active');
            } else {
                formLogin.style.display = 'none';
                formReg.style.display = 'flex';
                btnReg.classList.add('active');
                btnLogin.classList.remove('active');
            }
        }
    </script>
</body>
</html>
