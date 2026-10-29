<?php
/**
 * Modelo Base para las entidades del sistema
 * Provee métodos CRUD estándar con sentencias preparadas (PDO)
 */
abstract class BaseModel
{
    protected PDO $db;
    protected string $tabla;
    protected array $columnas;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Lista todos los registros ordenados por ID de manera descendente
     */
    public function listar(): array
    {
        $stmt = $this->db->query('SELECT * FROM `' . $this->tabla . '` ORDER BY id DESC LIMIT 500');
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un registro por su ID
     */
    public function obtener(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM `' . $this->tabla . '` WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Inserta o actualiza un registro de forma segura
     */
    public function guardar(array $datos, int $id = 0): int
    {
        $columnas = $this->columnas;
        $valores = array_map(fn($columna) => $datos[$columna] ?? null, $columnas);

        if ($id > 0) {
            $asignaciones = implode(', ', array_map(fn($columna) => "`$columna` = ?", $columnas));
            $stmt = $this->db->prepare("UPDATE `{$this->tabla}` SET $asignaciones WHERE id = ?");
            $stmt->execute([...$valores, $id]);
            return $id;
        } else {
            $nombres = implode(', ', array_map(fn($columna) => "`$columna`", $columnas));
            $marcadores = implode(', ', array_fill(0, count($columnas), '?'));
            $stmt = $this->db->prepare("INSERT INTO `{$this->tabla}` ($nombres) VALUES ($marcadores)");
            $stmt->execute($valores);
            return (int) $this->db->lastInsertId();
        }
    }

    /**
     * Elimina un registro por su ID
     */
    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM `' . $this->tabla . '` WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Cuenta el total de registros en la tabla
     */
    public function contar(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM `' . $this->tabla . '`');
        return (int) $stmt->fetchColumn();
    }
}

