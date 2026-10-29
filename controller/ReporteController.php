<?php
/**
 * Controlador de Reportes y Estadísticas
 * Genera reportes de:
 * 1. Servicios realizados
 * 2. Vehículos atendidos
 * 3. Reparaciones pendientes
 * 4. Ingresos obtenidos
 */

require_once __DIR__ . '/BaseController.php';

class ReporteController
{
    private PDO $db;
    public string $titulo = 'Reportes del Taller Motor Express';

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Reporte 1: Servicios realizados con frecuencia e importe
     */
    public function serviciosRealizados(): array
    {
        $sql = "SELECT s.id, s.nombre, s.precio, 
                       COUNT(os.orden_id) AS total_veces, 
                       (COUNT(os.orden_id) * s.precio) AS total_generado
                FROM orden_servicio os
                JOIN servicio s ON os.servicio_id = s.id
                JOIN ordenes_trabajo ot ON os.orden_id = ot.id
                GROUP BY s.id, s.nombre, s.precio
                ORDER BY total_veces DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Reporte 2: Vehículos atendidos con historial y propietario
     */
    public function vehiculosAtendidos(): array
    {
        $sql = "SELECT v.placa, v.marca, v.modelo, v.anio, 
                       c.nombre AS cliente_nombre, c.telefono,
                       COUNT(ot.id) AS total_visitas, 
                       MAX(ot.fecha) AS ultima_visita
                FROM vehiculo v
                JOIN cliente c ON v.cliente_id = c.id
                JOIN cita ci ON ci.vehiculo_id = v.id
                JOIN ordenes_trabajo ot ON ot.cita_id = ci.id
                GROUP BY v.id, v.placa, v.marca, v.modelo, v.anio, c.nombre, c.telefono
                ORDER BY total_visitas DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Reporte 3: Reparaciones actualmente pendientes
     */
    public function reparacionesPendientes(): array
    {
        $sql = "SELECT ot.id, ot.fecha, ot.diagnostico, ot.estado,
                       v.placa, v.marca, v.modelo,
                       c.nombre AS cliente_nombre,
                       m.nombre AS mecanico_nombre
                FROM ordenes_trabajo ot
                JOIN cita ci ON ot.cita_id = ci.id
                JOIN vehiculo v ON ci.vehiculo_id = v.id
                JOIN cliente c ON v.cliente_id = c.id
                JOIN mecanico m ON ci.mecanico_id = m.id
                WHERE ot.estado IN ('pendiente', 'en_proceso')
                ORDER BY ot.fecha ASC, ot.id ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Reporte 4: Ingresos obtenidos totales y desglose por método de pago
     */
    public function ingresosObtenidos(): array
    {
        // Total global
        $total = (float)$this->db->query("SELECT COALESCE(SUM(monto), 0) FROM pago")->fetchColumn();

        // Desglose por método
        $porMetodo = $this->db->query(
            "SELECT metodo, COUNT(*) AS operaciones, COALESCE(SUM(monto), 0) AS total
             FROM pago 
             GROUP BY metodo"
        )->fetchAll();

        // Listado detallado de pagos
        $detalle = $this->db->query(
            "SELECT p.*, v.placa, c.nombre AS cliente_nombre, ot.diagnostico
             FROM pago p
             JOIN ordenes_trabajo ot ON p.orden_id = ot.id
             JOIN cita ci ON ot.cita_id = ci.id
             JOIN vehiculo v ON ci.vehiculo_id = v.id
             JOIN cliente c ON v.cliente_id = c.id
             ORDER BY p.fecha DESC, p.id DESC"
        )->fetchAll();

        return [
            'total' => $total,
            'por_metodo' => $porMetodo,
            'detalle' => $detalle
        ];
    }

    /**
     * Resumen para KPIs del dashboard
     */
    public function resumenKpis(): array
    {
        $totalClientes = (int)$this->db->query("SELECT COUNT(*) FROM cliente")->fetchColumn();
        $totalVehiculos = (int)$this->db->query("SELECT COUNT(*) FROM vehiculo")->fetchColumn();
        $reparacionesPendientes = (int)$this->db->query("SELECT COUNT(*) FROM ordenes_trabajo WHERE estado IN ('pendiente', 'en_proceso')")->fetchColumn();
        $totalIngresos = (float)$this->db->query("SELECT COALESCE(SUM(monto), 0) FROM pago")->fetchColumn();

        return [
            'clientes' => $totalClientes,
            'vehiculos' => $totalVehiculos,
            'pendientes' => $reparacionesPendientes,
            'ingresos' => $totalIngresos,
        ];
    }
}

