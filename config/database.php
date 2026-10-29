<?php
/**
 * Configuración y Conexión a la Base de Datos MySQL mediante PDO
 * Taller Automotriz "Motor Express"
 */

class Database
{
    private string $host = 'localhost';
    private string $name = 'motor_express';
    private string $user = 'root';
    private string $password = '';

    public function conectar(): PDO
    {
        try {
            $pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->name};charset=utf8mb4",
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
            return $pdo;
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . htmlspecialchars($e->getMessage()));
        }
    }
}

