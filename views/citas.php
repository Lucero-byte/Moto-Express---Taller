<?php
require_once __DIR__ . '/../controller/CitaController.php';

exigirSesion();
$controller = new CitaController();
$error = $controller->procesar();

$edit = isset($_GET['editar']) ? $controller->modelo->obtener((int) $_GET['editar']) : false;
$rows = $controller->modelo->listarConRelaciones();
$campos = $controller->campos;
$titulo = $controller->titulo;

// Selects
$vehiculos = db()->query(
    "SELECT v.id, v.placa, v.marca, v.modelo, c.nombre AS cliente_nombre 
     FROM vehiculo v 
     JOIN cliente c ON v.cliente_id = c.id 
     ORDER BY v.placa ASC LIMIT 500"
)->fetchAll();

$mecanicos = db()->query("SELECT id, nombre, especialidad FROM mecanico ORDER BY nombre ASC LIMIT 500")->fetchAll();

require __DIR__ . '/citas/index.php';

