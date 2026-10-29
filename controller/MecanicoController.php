<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Mecanico.php';

class MecanicoController
{
    public Mecanico $modelo;
    public string $titulo = 'Gestión de Mecánicos y Especialidades';
    public array $campos = [
        'nombre' => 'text',
        'especialidad' => 'text',
    ];

    public function __construct()
    {
        $this->modelo = new Mecanico(db());
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
                notificar('Mecánico eliminado del registro.');
            } else {
                if (empty(trim($_POST['nombre'] ?? '')) || empty(trim($_POST['especialidad'] ?? ''))) {
                    throw new RuntimeException('El nombre y la especialidad son obligatorios.');
                }
                $datos = validarDatos($this->campos, $_POST);
                $this->modelo->guardar($datos, $id);
                notificar($id > 0 ? 'Datos del mecánico actualizados.' : 'Mecánico registrado exitosamente.');
            }

            redirigir('mecanicos.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la acción debido a relaciones activas en el sistema.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}

