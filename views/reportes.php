<?php
require_once __DIR__ . '/../controller/ReporteController.php';

exigirSesion();
$controller = new ReporteController();
$titulo = $controller->titulo;

$serviciosRealizados = $controller->serviciosRealizados();
$vehiculosAtendidos = $controller->vehiculosAtendidos();
$reparacionesPendientes = $controller->reparacionesPendientes();
$ingresosData = $controller->ingresosObtenidos();

require __DIR__ . '/reportes/index.php';

