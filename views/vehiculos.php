<?php
require_once __DIR__ . '/../controller/VehiculoController.php';

exigirSesion();
$controller = new VehiculoController();
$error = $controller->procesar();

$edit = isset($_GET['editar']) ? $controller->modelo->obtener((int) $_GET['editar']) : false;
$rows = $controller->modelo->listarConCliente();
$campos = $controller->campos;
$titulo = $controller->titulo;

// Lista de clientes para el select
$clientes = db()->query("SELECT id, nombre FROM cliente ORDER BY nombre ASC LIMIT 500")->fetchAll();

require __DIR__ . '/vehiculos/index.php';

