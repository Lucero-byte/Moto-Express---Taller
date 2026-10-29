<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Vehiculo.php';

class VehiculoController
{
    public Vehiculo $modelo;
    public string $titulo = 'Registro y Gestión de Vehículos';
    public array $campos = [
        'cliente_id' => 'cliente_id',
        'placa' => 'text',
        'marca' => 'text',
        'modelo' => 'text',
        'anio' => 'integer',
    ];

    public function __construct()
    {
        $this->modelo = new Vehiculo(db());
    }

    public function procesar(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return '';
        }

        try {
            validarCsrf();
            $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 0;
            $accion = $_POST['accion'] ?? '';

            if ($accion === 'eliminar') {
                $this->modelo->eliminar($id);
                notificar('Vehículo eliminado correctamente.');
            } else {
                if (empty(trim($_POST['placa'] ?? '')) || empty(trim($_POST['marca'] ?? '')) || empty(trim($_POST['modelo'] ?? ''))) {
                    throw new RuntimeException('Placa, marca y modelo son campos obligatorios.');
                }
                $anio = (int)($_POST['anio'] ?? 0);
                if ($anio < 1900 || $anio > (date('Y') + 1)) {
                    throw new RuntimeException('El año de fabricación no es válido.');
                }

                $datos = validarDatos($this->campos, $_POST);
                $datos['placa'] = strtoupper(trim($datos['placa']));
                $this->modelo->guardar($datos, $id);
                notificar($id > 0 ? 'Vehículo modificado con éxito.' : 'Vehículo registrado con éxito.');
            }

            redirigir('vehiculos.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'Error: La placa ya podría estar registrada en el sistema o hubo un error de integridad.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}

