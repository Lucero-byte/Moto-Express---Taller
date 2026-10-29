<?php
require_once __DIR__ . '/BaseModel.php';

class OrdenTrabajo extends BaseModel
{
    protected string $tabla = 'ordenes_trabajo';
    protected array $columnas = ['cita_id', 'diagnostico', 'estado', 'fecha'];

    public function listarConRelaciones(): array
    {
        $sql = "SELECT ot.*, 
                       ci.fecha AS cita_fecha,
                       v.placa AS vehiculo_placa, 
                       v.marca AS vehiculo_marca, 
                       v.modelo AS vehiculo_modelo,
                       cl.nombre AS cliente_nombre,
                       m.nombre AS mecanico_nombre
                FROM ordenes_trabajo ot
                LEFT JOIN cita ci ON ot.cita_id = ci.id
                LEFT JOIN vehiculo v ON ci.vehiculo_id = v.id
                LEFT JOIN cliente cl ON v.cliente_id = cl.id
                LEFT JOIN mecanico m ON ci.mecanico_id = m.id
                ORDER BY ot.id DESC LIMIT 500";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerConDetalles(int $id): array|false
    {
        $sql = "SELECT ot.*, 
                       ci.fecha AS cita_fecha,
                       v.placa AS vehiculo_placa, 
                       v.marca AS vehiculo_marca, 
                       v.modelo AS vehiculo_modelo,
                       cl.nombre AS cliente_nombre,
                       m.nombre AS mecanico_nombre
                FROM ordenes_trabajo ot
                LEFT JOIN cita ci ON ot.cita_id = ci.id
                LEFT JOIN vehiculo v ON ci.vehiculo_id = v.id
                LEFT JOIN cliente cl ON v.cliente_id = cl.id
                LEFT JOIN mecanico m ON ci.mecanico_id = m.id
                WHERE ot.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function obtenerServicios(int $orden_id): array
    {
        $sql = "SELECT s.* FROM orden_servicio os 
                JOIN servicio s ON os.servicio_id = s.id 
                WHERE os.orden_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orden_id]);
        return $stmt->fetchAll();
    }

    public function obtenerRepuestos(int $orden_id): array
    {
        $sql = "SELECT r.*, ord_rep.cantidad, (r.precio * ord_rep.cantidad) AS subtotal 
                FROM orden_repuesto ord_rep 
                JOIN repuesto r ON ord_rep.repuesto_id = r.id 
                WHERE ord_rep.orden_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orden_id]);
        return $stmt->fetchAll();
    }

    public function sincronizarServicios(int $orden_id, array $servicios): void
    {
        $del = $this->db->prepare("DELETE FROM orden_servicio WHERE orden_id = ?");
        $del->execute([$orden_id]);

        if (!empty($servicios)) {
            $ins = $this->db->prepare("INSERT INTO orden_servicio (orden_id, servicio_id) VALUES (?, ?)");
            foreach ($servicios as $sId) {
                if ((int)$sId > 0) {
                    $ins->execute([$orden_id, (int)$sId]);
                }
            }
        }
    }

    public function sincronizarRepuestos(int $orden_id, array $repuestos): void
    {
        $del = $this->db->prepare("DELETE FROM orden_repuesto WHERE orden_id = ?");
        $del->execute([$orden_id]);

        if (!empty($repuestos)) {
            $ins = $this->db->prepare("INSERT INTO orden_repuesto (orden_id, repuesto_id, cantidad) VALUES (?, ?, ?)");
            foreach ($repuestos as $rep) {
                $rId = (int)($rep['id'] ?? 0);
                $cant = max(1, (int)($rep['cantidad'] ?? 1));
                if ($rId > 0) {
                    $ins->execute([$orden_id, $rId, $cant]);
                }
            }
        }
    }

    public function calcularTotal(int $orden_id): float
    {
        $totalServicios = (float)$this->db->query(
            "SELECT COALESCE(SUM(s.precio), 0) FROM orden_servicio os JOIN servicio s ON os.servicio_id = s.id WHERE os.orden_id = $orden_id"
        )->fetchColumn();

        $totalRepuestos = (float)$this->db->query(
            "SELECT COALESCE(SUM(r.precio * ord_rep.cantidad), 0) FROM orden_repuesto ord_rep JOIN repuesto r ON ord_rep.repuesto_id = r.id WHERE ord_rep.orden_id = $orden_id"
        )->fetchColumn();

        return round($totalServicios + $totalRepuestos, 2);
    }
}

