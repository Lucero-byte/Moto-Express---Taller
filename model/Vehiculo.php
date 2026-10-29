<?php
require_once __DIR__ . '/BaseModel.php';

class Vehiculo extends BaseModel
{
    protected string $tabla = 'vehiculo';
    protected array $columnas = ['cliente_id', 'placa', 'marca', 'modelo', 'anio'];

    public function listarConCliente(): array
    {
        $sql = "SELECT v.*, c.nombre AS cliente_nombre, c.telefono AS cliente_telefono 
                FROM vehiculo v
                LEFT JOIN cliente c ON v.cliente_id = c.id
                ORDER BY v.id DESC LIMIT 500";
        return $this->db->query($sql)->fetchAll();
    }
}

