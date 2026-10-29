<?php
/**
 * Controlador de Autenticación
 * Gestiona el inicio de sesión, registro de nuevos usuarios y cierre de sesión
 */

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Usuario.php';

class AuthController
{
    private Usuario $modelo;

    public function __construct()
    {
        $this->modelo = new Usuario(db());
    }

    public function ingresar(string $email, string $password): bool
    {
        $usuario = $this->modelo->porEmail($email);

        if (!$usuario) {
            return false;
        }

        // Verificación con hash bcrypt o compatibilidad con texto plano de pruebas
        $esValida = password_verify($password, $usuario['password']) || ($usuario['password'] === $password);

        if (!$esValida) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['uid'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['email'] = $usuario['email'];
        return true;
    }

    public function registrar(string $nombre, string $email, string $password): void
    {
        if (trim($nombre) === '') {
            throw new RuntimeException('Debe proporcionar su nombre completo.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('El correo electrónico no es válido.');
        }

        if (strlen($password) < 6) {
            throw new RuntimeException('La contraseña debe tener un mínimo de 6 caracteres.');
        }

        if ($this->modelo->porEmail($email)) {
            throw new RuntimeException('Ya existe una cuenta registrada con este correo electrónico.');
        }

        $this->modelo->crear($nombre, $email, $password);
    }

    public function cerrarSesion(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }
}

