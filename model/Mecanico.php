<?php
require_once __DIR__ . '/BaseModel.php';

class Mecanico extends BaseModel
{
    protected string $tabla = 'mecanico';
    protected array $columnas = ['nombre', 'especialidad'];
}

