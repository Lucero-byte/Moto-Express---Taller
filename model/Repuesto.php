<?php
require_once __DIR__ . '/BaseModel.php';

class Repuesto extends BaseModel
{
    protected string $tabla = 'repuesto';
    protected array $columnas = ['nombre', 'precio', 'stock'];
}

