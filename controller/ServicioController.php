<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Servicio.php';

class ServicioController
{
    public Servicio $modelo;
    public string $titulo = 'Administración de Servicios de Reparación';
    public array $campos = [
        'nombre' => 'text',
        'precio' => 'number',
    ];

    public function __construct()
    {
        $this->modelo = new Servicio(db());
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
                notificar('Servicio eliminado.');
            } else {
                if (empty(trim($_POST['nombre'] ?? ''))) {
                    throw new RuntimeException('El nombre del servicio es obligatorio.');
                }
                $datos = validarDatos($this->campos, $_POST);
                $this->modelo->guardar($datos, $id);
                notificar($id > 0 ? 'Servicio actualizado correctamente.' : 'Nuevo servicio agregado.');
            }

            redirigir('servicios.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se puede eliminar un servicio que ya forma parte de órdenes de trabajo.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}

