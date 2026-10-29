<?php
require_once __DIR__ . '/../controller/OrdenTrabajoController.php';

exigirSesion();
$controller = new OrdenTrabajoController();
$error = $controller->procesar();

$editId = isset($_GET['editar']) ? (int) $_GET['editar'] : 0;
$edit = $editId > 0 ? $controller->modelo->obtenerConDetalles($editId) : false;

$serviciosSeleccionados = [];
$repuestosSeleccionados = [];

if ($edit) {
    $serviciosAsignados = $controller->modelo->obtenerServicios($editId);
    $serviciosSeleccionados = array_column($serviciosAsignados, 'id');

    $repuestosAsignados = $controller->modelo->obtenerRepuestos($editId);
    foreach ($repuestosAsignados as $ra) {
        $repuestosSeleccionados[$ra['id']] = $ra['cantidad'];
    }
}

$rows = $controller->modelo->listarConRelaciones();
$titulo = $controller->titulo;

// Listas para selects y checkboxes
$citas = db()->query(
    "SELECT ci.id, ci.fecha, v.placa, v.marca, v.modelo, cl.nombre AS cliente_nombre, m.nombre AS mecanico_nombre 
     FROM cita ci 
     JOIN vehiculo v ON ci.vehiculo_id = v.id 
     JOIN cliente cl ON v.cliente_id = cl.id 
     JOIN mecanico m ON ci.mecanico_id = m.id 
     ORDER BY ci.fecha DESC LIMIT 500"
)->fetchAll();

$servicios = db()->query("SELECT id, nombre, precio FROM servicio ORDER BY nombre ASC")->fetchAll();
$repuestos = db()->query("SELECT id, nombre, precio, stock FROM repuesto ORDER BY nombre ASC")->fetchAll();

require __DIR__ . '/ordenes/index.php';

