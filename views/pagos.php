<?php
require_once __DIR__ . '/../controller/PagoController.php';

exigirSesion();
$controller = new PagoController();
$error = $controller->procesar();

$edit = isset($_GET['editar']) ? $controller->modelo->obtener((int) $_GET['editar']) : false;
$rows = $controller->modelo->listarConOrden();
$campos = $controller->campos;
$titulo = $controller->titulo;

// Lista de órdenes para asociar pago
$ordenes = db()->query(
    "SELECT ot.id, ot.fecha, ot.diagnostico, ot.estado, v.placa, c.nombre AS cliente_nombre 
     FROM ordenes_trabajo ot 
     JOIN cita ci ON ot.cita_id = ci.id 
     JOIN vehiculo v ON ci.vehiculo_id = v.id 
     JOIN cliente c ON v.cliente_id = c.id 
     ORDER BY ot.id DESC LIMIT 500"
)->fetchAll();

require __DIR__ . '/pagos/index.php';

