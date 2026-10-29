<?php
/**
 * Modelo de Usuario
 * Gestión de usuarios para inicio de sesión y registro
 */

class Usuario
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function porEmail(string $email): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM `usuario` WHERE `email` = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function crear(string $nombre, string $email, string $password): int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('INSERT INTO `usuario` (`nombre`, `email`, `password`) VALUES (?, ?, ?)');
        $stmt->execute([$nombre, $email, $hash]);
        return (int) $this->db->lastInsertId();
    }
}

