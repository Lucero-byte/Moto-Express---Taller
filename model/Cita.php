<?php
require_once __DIR__ . '/BaseModel.php';

class Cita extends BaseModel
{
    protected string $tabla = 'cita';
    protected array $columnas = ['vehiculo_id', 'mecanico_id', 'fecha'];

    public function listarConRelaciones(): array
    {
        $sql = "SELECT c.*, 
                       v.placa AS vehiculo_placa, 
                       v.marca AS vehiculo_marca, 
                       v.modelo AS vehiculo_modelo,
                       cl.nombre AS cliente_nombre,
                       m.nombre AS mecanico_nombre, 
                       m.especialidad AS mecanico_especialidad
                FROM cita c
                LEFT JOIN vehiculo v ON c.vehiculo_id = v.id
                LEFT JOIN cliente cl ON v.cliente_id = cl.id
                LEFT JOIN mecanico m ON c.mecanico_id = m.id
                ORDER BY c.fecha DESC LIMIT 500";
        return $this->db->query($sql)->fetchAll();
    }
}

