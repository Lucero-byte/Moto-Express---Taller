<?php
require_once __DIR__ . '/BaseModel.php';

class Servicio extends BaseModel
{
    protected string $tabla = 'servicio';
    protected array $columnas = ['nombre', 'precio'];
}

