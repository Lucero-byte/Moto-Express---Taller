<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Repuesto.php';

class RepuestoController
{
    public Repuesto $modelo;
    public string $titulo = 'Control de Repuestos e Inventario';
    public array $campos = [
        'nombre' => 'text',
        'precio' => 'number',
        'stock' => 'integer',
    ];

    public function __construct()
    {
        $this->modelo = new Repuesto(db());
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
                notificar('Repuesto eliminado del inventario.');
            } else {
                if (empty(trim($_POST['nombre'] ?? ''))) {
                    throw new RuntimeException('El nombre del repuesto es obligatorio.');
                }
                $datos = validarDatos($this->campos, $_POST);
                $this->modelo->guardar($datos, $id);
                notificar($id > 0 ? 'Repuesto modificado exitosamente.' : 'Repuesto registrado en el inventario.');
            }

            redirigir('repuestos.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se puede eliminar un repuesto que está registrado en órdenes de trabajo.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}

