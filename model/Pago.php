<?php
require_once __DIR__ . '/BaseModel.php';

class Pago extends BaseModel
{
    protected string $tabla = 'pago';
    protected array $columnas = ['orden_id', 'monto', 'metodo', 'fecha'];

    public function listarConOrden(): array
    {
        $sql = "SELECT p.*, 
                       ot.diagnostico AS orden_diagnostico,
                       ot.estado AS orden_estado,
                       v.placa AS vehiculo_placa,
                       c.nombre AS cliente_nombre
                FROM pago p
                LEFT JOIN ordenes_trabajo ot ON p.orden_id = ot.id
                LEFT JOIN cita ci ON ot.cita_id = ci.id
                LEFT JOIN vehiculo v ON ci.vehiculo_id = v.id
                LEFT JOIN cliente c ON v.cliente_id = c.id
                ORDER BY p.fecha DESC, p.id DESC LIMIT 500";
        return $this->db->query($sql)->fetchAll();
    }

    public function totalIngresos(): float
    {
        $stmt = $this->db->query("SELECT COALESCE(SUM(monto), 0) FROM pago");
        return (float) $stmt->fetchColumn();
    }

    public function ingresosPorMetodo(): array
    {
        $sql = "SELECT metodo, COUNT(*) AS cantidad, COALESCE(SUM(monto), 0) AS total 
                FROM pago 
                GROUP BY metodo";
        return $this->db->query($sql)->fetchAll();
    }
}

