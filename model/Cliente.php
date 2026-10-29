<?php
require_once __DIR__ . '/BaseModel.php';

class Cliente extends BaseModel
{
    protected string $tabla = 'cliente';
    protected array $columnas = ['nombre', 'telefono', 'email'];
}

